<?php

namespace App\Http\Requests\Accountant;

use Illuminate\Foundation\Http\FormRequest;

class StorePointOfSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:points_of_sale,name',
            'location' => 'required|string|max:255',
            'supervisor_id' => 'required|exists:users,id',
            'pos_user_name' => 'required|string|max:255',
            'pos_user_email' => 'required|email|unique:users,email',
            'pos_user_password' => 'required|string|min:8|confirmed'
        ];
    }
}