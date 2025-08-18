<?php

namespace App\Http\Requests\Pos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'الرجاء اختيار صورة.',
            'avatar.image' => 'الملف يجب أن يكون صورة.',
            'avatar.mimes' => 'الامتدادات المسموحة: JPG, JPEG, PNG, WEBP.',
            'avatar.max' => 'الحد الأقصى للصورة 2MB.',
        ];
    }
}
