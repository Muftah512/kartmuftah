<?php 

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

public function rules()
{
    $userId = $this->route('user')->id;

    return [
        'name'             => 'required|string|max:255',
        'email'            => "required|email|unique:users,email,{$userId}",
        'password'         => 'nullable|string|min:8|confirmed',
        'role'             => 'required|in:admin,accountant,pos',
        'point_of_sale_id' => 'nullable|exists:point_of_sales,id',
        'is_active'        => 'sometimes|boolean',
    ];
 }
}