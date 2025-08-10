<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MikroTikService;

class PackageController extends Controller
{
    /**
     * قد تكون null إذا فشل الاتصال بالراوتر.
     * @var MikroTikService|null
     */
    protected $mikroTikService;

    public function __construct()
    {
        try {
            $this->mikroTikService = new MikroTikService();
        } catch (\Exception $e) {
            Log::error('MikroTik connection error: ' . $e->getMessage());
            $this->mikroTikService = null;
        }
    }

    public function index()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();
        $mikrotikProfiles = $this->getMikrotikProfiles();
        return view('admin.packages.index', compact('packages', 'mikrotikProfiles'));
    }

    public function create()
    {
        $mikrotikProfiles = $this->getMikrotikProfiles();
        return view('admin.packages.create', compact('mikrotikProfiles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'validity_days'    => 'required|integer|min:1',
            'size_mb'          => 'required|integer|min:1',
            // بدون مسافات منعًا للمشاكل في UM
            'mikrotik_profile' => ['required','string','regex:/^\S+$/'],
        ], [
            'mikrotik_profile.regex' => 'اسم بروفايل MikroTik يجب أن يكون بدون مسافات.'
        ]);

        // تنظيف بسيط
        $data['name'] = trim($data['name']);
        $data['mikrotik_profile'] = trim($data['mikrotik_profile']);

        // تحقق أن البروفايل موجود فعلًا
        $mikrotikProfiles = $this->getMikrotikProfiles();
        $profileExists = collect($mikrotikProfiles)->contains('name', $data['mikrotik_profile']);

        if (!$profileExists) {
            return back()->withInput()->withErrors([
                'mikrotik_profile' => 'بروفايل MikroTik المحدد غير موجود في الخادم.'
            ]);
        }

        Package::create($data);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'تم إنشاء الباقة بنجاح');
    }

    public function edit(Package $package)
    {
        $mikrotikProfiles = $this->getMikrotikProfiles();
        return view('admin.packages.edit', compact('package', 'mikrotikProfiles'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'validity_days'    => 'required|integer|min:1',
            'size_mb'          => 'required|integer|min:1',
            'mikrotik_profile' => ['required','string','regex:/^\S+$/'],
        ], [
            'mikrotik_profile.regex' => 'اسم بروفايل MikroTik يجب أن يكون بدون مسافات.'
        ]);

        $data['name'] = trim($data['name']);
        $data['mikrotik_profile'] = trim($data['mikrotik_profile']);

        $mikrotikProfiles = $this->getMikrotikProfiles();
        $profileExists = collect($mikrotikProfiles)->contains('name', $data['mikrotik_profile']);

        if (!$profileExists) {
            return back()->withInput()->withErrors([
                'mikrotik_profile' => 'بروفايل MikroTik المحدد غير موجود في الخادم.'
            ]);
        }

        $package->update($data);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'تم تحديث الباقة بنجاح');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'تم حذف الباقة بنجاح');
    }

    /**
     * سحب بروفايلات MikroTik مع معالجة أي أخطاء بهدوء.
     */
    private function getMikrotikProfiles(): array
    {
        if (!$this->mikroTikService) {
            return [];
        }

        try {
            return $this->mikroTikService->getProfiles();
        } catch (\Exception $e) {
            Log::error('MikroTik profiles fetch error: ' . $e->getMessage());
            return [];
        }
    }
}
