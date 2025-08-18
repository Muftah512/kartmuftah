<?php
// app/Http/Controllers/Pos/ProfileController.php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\UpdateAvatarRequest;
use App\Http\Requests\Pos\UpdatePasswordRequest;
use App\Http\Requests\Pos\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    private function ctx(): array
    {
        $name = request()->route()->getName(); // مثل: 'pos.profile.edit'
        $prefix = strtok($name, '.');          // pos | accountant | admin

        $layout = match ($prefix) {
            'admin'      => 'layouts.admin',
            'accountant' => 'layouts.accountant',
            default      => 'layouts.pos',
        };

        return compact('prefix', 'layout');
    }

    public function edit()
    {
        $user = auth()->user();
        $pos  = method_exists($user, 'pointOfSale') ? $user->pointOfSale : null;

        $ctx = $this->ctx();
        // نعيد نفس الفيو للجميع (ملف واحد ديناميكي)
        return view('pos.profile.edit', array_merge($ctx, compact('user', 'pos')));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $user->name = (string) $request->string('name');
        $user->save();

        $posName = $request->string('pos_name');
        if ($posName && method_exists($user, 'pointOfSale') && $user->pointOfSale) {
            $user->pointOfSale->name = (string) $posName;
            $user->pointOfSale->save();
        }

        return back()->with('success', 'تم تحديث البيانات بنجاح.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->string('password'));
        $user->save();

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        $user = auth()->user();
        $file = $request->file('avatar');

        foreach (['webp','jpg','jpeg','png'] as $ext) {
            $old = "avatars/{$user->id}.{$ext}";
            if (Storage::disk('local')->exists($old)) {
                Storage::disk('local')->delete($old);
            }
        }

        $ext = strtolower($file->getClientOriginalExtension());
        if (! in_array($ext, ['webp','jpg','jpeg','png'])) {
            $ext = 'jpg';
        }

        Storage::disk('local')->putFileAs('avatars', $file, "{$user->id}.{$ext}");

        return back()->with('success', 'تم تحديث الصورة الشخصية (خاصة بحسابك فقط).');
    }

    public function deleteAvatar(Request $request)
    {
        $user = auth()->user();
        $deleted = false;
        foreach (['webp','jpg','jpeg','png'] as $ext) {
            $p = "avatars/{$user->id}.{$ext}";
            if (Storage::disk('local')->exists($p)) {
                Storage::disk('local')->delete($p);
                $deleted = true;
            }
        }
        return back()->with('success', $deleted ? 'تم حذف الصورة.' : 'لا توجد صورة محفوظة.');
    }

    public function showAvatar(): StreamedResponse
    {
        $user = auth()->user();
        $path = $this->findAvatarPath($user->id);

        if (! $path) {
            $url = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff&size=128&bold=true';
            return redirect()->away($url);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $mime = match ($ext) {
            'webp' => 'image/webp',
            'png'  => 'image/png',
            default => 'image/jpeg',
        };

        return response()->stream(function () use ($path) {
            echo Storage::disk('local')->get($path);
        }, 200, [
            'Content-Type'        => $mime,
            'Cache-Control'       => 'private, no-store, max-age=0',
            'X-Private-Avatar'    => '1',
        ]);
    }

    private function findAvatarPath(int $userId): ?string
    {
        foreach (['webp','jpg','jpeg','png'] as $ext) {
            $p = "avatars/{$userId}.{$ext}";
            if (Storage::disk('local')->exists($p)) {
                return $p;
            }
        }
        return null;
    }
}
