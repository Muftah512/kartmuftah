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
        'role'             => 'required|in:admin,accountant,pos',
        'point_of_sale_id' => 'nullable|exists:point_of_sales,id',
        'password'         => 'required|confirmed|min:6',
        'password_confirmation' => 'required|string|min:6',
    ];
  }
}
