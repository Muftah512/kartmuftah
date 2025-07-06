<?php

namespace App\Http\Requests\Accountant;

use Illuminate\Foundation\Http\FormRequest;

class RechargeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pos_id' => 'required|exists:points_of_sale,id',
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|in:cash,bank_transfer'
        ];
    }
}