<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Package;

class MikroTikService
{
    protected $client;
    protected $customerName;
    protected $verifyAfterAssign;

    public function __construct()
    {
        try {
            $config = Config::get('services.mikrotik');

            if (empty($config['host']) || empty($config['username']) || empty($config['password'])) {
                throw new Exception('MikroTik configuration is missing.');
            }

            $this->customerName       = $config['customer_name'] ?? 'admin';
            $this->verifyAfterAssign  = (bool)($config['verify_after_assign'] ?? false); // اجعله false للسرعة
            $port = (int) ($config['port'] ?? ($config['ssl'] ? 8729 : 8728));
            $ssl  = $config['ssl'] ?? false;

            $this->client = new Client([
                'host'    => $config['host'],
                'user'    => $config['username'],
                'pass'    => $config['password'],
                'port'    => $port,
                'timeout' => 10,   // مهلة أصغر لتجربة سلسة
                'ssl'     => $ssl,
            ]);
        } catch (Exception $e) {
            Log::error('MikroTik API Connection Error: ' . $e->getMessage());
            throw new Exception('MikroTik API Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء مستخدم باسم فقط، ثم إسناد/تفعيل البروفايل فورًا باستخدام ret كـ .id (numbers).
     */
    public function createUser(string $username, Package $package, string $password = ''): bool
    {
        try {
            // 1) add user (بدون profile)
            $add = (new Query('/tool/user-manager/user/add'))
                ->equal('customer', $this->customerName)
                ->equal('username', $username);

            if ($password !== '') {
                $add->equal('password', $password);
            }

            $respAdd = $this->client->query($add)->read();
            Log::info('UM add user response', ['response' => $respAdd]);

            if ($this->isErrorResponse($respAdd)) {
                Log::error('UM add user failed', ['username' => $username, 'resp' => $respAdd]);
                return false;
            }

            // استخرج ret = .id للمستخدم الجديد
            $userId = $this->extractRet($respAdd);
            if (!$userId) {
                Log::error('UM createUser: ret (.id) not found after add', ['username' => $username, 'resp' => $respAdd]);
                return false;
            }

            // 2) إسناد البروفايل مباشرة باستخدام numbers=<ret>
            $ok = $this->assignProfileById($userId, $username, $package);
            Log::info('UM assign profile result', ['username' => $username, 'ok' => $ok]);

            return $ok;
        } catch (Exception $e) {
            Log::error('UM Exception (createUser): ' . $e->getMessage(), ['username' => $username]);
            return false;
        }
    }

    /**
     * شحن = نفس آلية الإسناد، لكن هنا نحتاج نحدد المستخدم.
     * سنستخدم numbers=[find username="..."] مرة واحدة فقط، ثم ننفذ الإسناد.
     * (لو عندك ret محفوظ محليًا، مرره لنسخة ById لتكون أسرع.)
     */
    public function rechargeUser(string $username, Package $package): bool
    {
        $profile = $package->mikrotik_profile ?? $package->name;

        try {
            $numbersExpr = sprintf('[find username="%s"]', $username);

            // (اختياري) تنظيف بروفايلات سابقة
            $clear = (new Query('/tool/user-manager/user/clear-profiles'))
                ->equal('numbers', $numbersExpr);
            $r0 = $this->client->query($clear)->read();
            Log::info('UM clear-profiles (recharge) response', ['response' => $r0]);

            // create-and-activate-profile
            $q = (new Query('/tool/user-manager/user/create-and-activate-profile'))
                ->equal('customer', $this->customerName)
                ->equal('profile', $profile)
                ->equal('numbers', $numbersExpr);

            $r = $this->client->query($q)->read();
            Log::info('UM create-and-activate-profile (recharge, numbers=) response', ['response' => $r]);

            if ($this->isErrorResponse($r)) {
                // فرصة بدون customer
                $q2 = (new Query('/tool/user-manager/user/create-and-activate-profile'))
                    ->equal('profile', $profile)
                    ->equal('numbers', $numbersExpr);
                $r2 = $this->client->query($q2)->read();
                Log::info('UM create-and-activate-profile (recharge, no-customer) response', ['response' => $r2]);

                if ($this->isErrorResponse($r2)) {
                    return false;
                }
            }

            // تحقق اختياري لتقليل الزمن
            if ($this->verifyAfterAssign) {
                return $this->userHasProfile($username, $profile);
            }
            return true;

        } catch (Exception $e) {
            Log::error('UM Exception (rechargeUser): ' . $e->getMessage(), ['username' => $username, 'profile' => $profile]);
            return false;
        }
    }

    /**
     * إسناد/تفعيل البروفايل باستخدام .id مباشرة (الأسرع دائمًا).
     */
    protected function assignProfileById(string $userId, string $username, Package $package): bool
    {
        $profile = $package->mikrotik_profile ?? $package->name;

        try {
            // (اختياري) نظّف البروفايلات السابقة
            $clear = (new Query('/tool/user-manager/user/clear-profiles'))
                ->equal('numbers', $userId);
            $r0 = $this->client->query($clear)->read();
            Log::info('UM clear-profiles response', ['response' => $r0]);

            // create-and-activate-profile بالـ numbers=<.id>
            $q1 = (new Query('/tool/user-manager/user/create-and-activate-profile'))
                ->equal('customer', $this->customerName)
                ->equal('profile', $profile)
                ->equal('numbers', $userId);

            $r1 = $this->client->query($q1)->read();
            Log::info('UM create-and-activate-profile (numbers id + customer) response', ['response' => $r1]);

            if ($this->isErrorResponse($r1)) {
                // فرصة بدون customer
                $q2 = (new Query('/tool/user-manager/user/create-and-activate-profile'))
                    ->equal('profile', $profile)
                    ->equal('numbers', $userId);
                $r2 = $this->client->query($q2)->read();
                Log::info('UM create-and-activate-profile (numbers id only) response', ['response' => $r2]);

                if ($this->isErrorResponse($r2)) {
                    // فرصة أخيرة: set profile
                    $q3 = (new Query('/tool/user-manager/user/set'))
                        ->equal('numbers', $userId)
                        ->equal('profile', $profile);
                    $r3 = $this->client->query($q3)->read();
                    Log::info('UM user/set profile (numbers id) response', ['response' => $r3]);

                    if ($this->isErrorResponse($r3)) {
                        Log::error('UM assignProfileById failed', ['username' => $username, 'profile' => $profile, 'id' => $userId]);
                        return false;
                    }
                }
            }

            // تحقق اختياري للتقليل من زمن التنفيذ
            if ($this->verifyAfterAssign) {
                return $this->userHasProfile($username, $profile);
            }

            return true;

        } catch (Exception $e) {
            Log::error('UM Exception (assignProfileById): ' . $e->getMessage(), ['username' => $username, 'profile' => $profile, 'id' => $userId]);
            return false;
        }
    }

    public function suspendUser(string $username): bool
    {
        try {
            $numbersExpr = sprintf('[find username="%s"]', $username);
            $resp = $this->client->query(
                (new Query('/tool/user-manager/user/disable'))->equal('numbers', $numbersExpr)
            )->read();
            return !$this->isErrorResponse($resp);
        } catch (Exception $e) {
            Log::error('UM Exception (suspendUser): ' . $e->getMessage(), ['username' => $username]);
            return false;
        }
    }

    public function activateUser(string $username): bool
    {
        try {
            $numbersExpr = sprintf('[find username="%s"]', $username);
            $resp = $this->client->query(
                (new Query('/tool/user-manager/user/enable'))->equal('numbers', $numbersExpr)
            )->read();
            return !$this->isErrorResponse($resp);
        } catch (Exception $e) {
            Log::error('UM Exception (activateUser): ' . $e->getMessage(), ['username' => $username]);
            return false;
        }
    }

    public function getProfiles(): array
    {
        try {
            $q = (new Query('/tool/user-manager/profile/print'))
                ->equal('.proplist', '.id,name,validity,price');
            $profiles = $this->client->query($q)->read();

            if ($this->isErrorResponse($profiles)) {
                Log::error('UM getProfiles failed', ['response' => $profiles]);
                return [];
            }

            return array_map(function ($p) {
                return [
                    'id'       => $p['.id'] ?? null,
                    'name'     => $p['name'] ?? '',
                    'validity' => $p['validity'] ?? '',
                    'price'    => $p['price'] ?? 0,
                ];
            }, $profiles);
        } catch (Exception $e) {
            Log::error('UM Exception (getProfiles): ' . $e->getMessage());
            return [];
        }
    }

    public function testQuery(Query $query): array
    {
        return $this->client->query($query)->read();
    }

    /** استخراج ret (.id) من استجابة add */
    protected function extractRet(array $resp): ?string
    {
        // شكل شائع: ['after' => ['ret' => '*XYZ']]
        if (isset($resp['after']['ret']) && is_string($resp['after']['ret'])) {
            return $resp['after']['ret'];
        }
        // أحيانًا يكون كمصفوفة عناصر
        foreach ($resp as $row) {
            if (is_array($row) && isset($row['ret']) && is_string($row['ret'])) {
                return $row['ret'];
            }
        }
        return null;
    }

    /** تحقق اختياري من تفعيل البروفايل */
    protected function userHasProfile(string $username, string $expectedProfile): bool
    {
        try {
            $q = (new Query('/tool/user-manager/user/print'))
                ->equal('.proplist', 'username,actual-profile,profile')
                ->where('username', $username);

            $rows = $this->client->query($q)->read();

            if ($this->isErrorResponse($rows) || empty($rows)) {
                // Fallback: اطلب الكل وفلتر
                $qAll = (new Query('/tool/user-manager/user/print'))
                    ->equal('.proplist', 'username,actual-profile,profile');
                $rows = $this->client->query($qAll)->read();
                if ($this->isErrorResponse($rows) || empty($rows)) {
                    return false;
                }
                $rows = array_values(array_filter($rows, fn($r) =>
                    isset($r['username']) && trim((string)$r['username']) === $username
                ));
                if (empty($rows)) return false;
            }

            $row    = is_array($rows) ? (is_array(reset($rows)) ? reset($rows) : $rows) : [];
            $actual = $row['actual-profile'] ?? ($row['profile'] ?? null);
            if (!$actual) return false;

            return trim((string)$actual) === trim((string)$expectedProfile);
        } catch (Exception $e) {
            Log::error('UM Exception (userHasProfile): ' . $e->getMessage(), ['username' => $username]);
            return false;
        }
    }

    /** كشف الأخطاء: !trap أو after.message أو message منفرد. */
    protected function isErrorResponse(array $response): bool
    {
        foreach ($response as $item) {
            if (is_array($item) && isset($item['!trap'])) {
                return true;
            }
        }
        if (isset($response['after']['message']) && $response['after']['message']) {
            return true;
        }
        if (count($response) === 1 && isset($response[0]['message'])) {
            return true;
        }
        return false;
    }

    // للتوافق إن احتجته
    protected function hasTrap(array $response): bool
    {
        foreach ($response as $item) {
            if (isset($item['!trap'])) {
                return true;
            }
        }
        return false;
    }
}
