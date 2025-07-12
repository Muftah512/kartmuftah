<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

public function rules()
{
    return [
        'name'             => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email',
        'password'         => 'required|string|min:8|confirmed',
        'role'             => 'required|exists:roles,name', 
        'point_of_sale_id' => 'nullable|exists:point_of_sales,id',
        'is_active'        => 'sometimes|boolean',
    ];
}

public function messages()
{
    return [
        'role.required'                   => 'حقل الدور مطلوب.',
        'role.in'                         => 'الدور المختار غير صالح.',
        'point_of_sale_id.required_if'    => 'حدد نقطة البيع للمستخدم صاحب دور نقطة البيع.',
        'point_of_sale_id.exists'         => 'نقطة البيع المحددة غير موجودة.',
    ];
 }
}
