namespace App\Http\Controllers;

use Evollabs\LaravelRouteros\Facades\Routeros;
use Illuminate\Support\Facades\Log;

class MikroTikController extends Controller
{
    // إنشاء مستخدم جديد في MikroTik
    public function createUser($username, $package, $validityDays)
    {
        try {
            $response = Routeros::connect()->write('/ip/hotspot/user/add', [
                'name' => $username,
                'profile' => $package,
                'limit-uptime' => $validityDays . 'd',
                'disabled' => 'no',
            ])->read();

            return !empty($response);
        } catch (\Exception $e) {
            Log::error('MikroTik Create User Error: ' . $e->getMessage());
            return false;
        }
    }

    // إعادة شحن مستخدم موجود
    public function rechargeUser($username, $package, $validityDays)
    {
        try {
            // إيجاد المستخدم
            $users = Routeros::connect()
                ->write('/ip/hotspot/user/print', [
                    '?name' => $username,
                ])->read();

            if (empty($users)) {
                Log::error('MikroTik User not found: ' . $username);
                return false;
            }

            // تحديث الباقة
            $response = Routeros::connect()->write('/ip/hotspot/user/set', [
                '.id' => $users[0]['.id'],
                'profile' => $package,
                'limit-uptime' => $validityDays . 'd',
            ])->read();

            return !empty($response);
        } catch (\Exception $e) {
            Log::error('MikroTik Recharge Error: ' . $e->getMessage());
            return false;
        }
    }

    // التحقق من وجود مستخدم
    public function userExists($username)
    {
        try {
            $users = Routeros::connect()
                ->write('/ip/hotspot/user/print', [
                    '?name' => $username,
                ])->read();

            return !empty($users);
        } catch (\Exception $e) {
            Log::error('MikroTik User Check Error: ' . $e->getMessage());
            return false;
        }
    }
}