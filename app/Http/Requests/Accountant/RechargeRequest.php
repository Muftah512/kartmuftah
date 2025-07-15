<?php

namespace App\Http\Requests\Accountant;

use Illuminate\Foundation\Http\FormRequest;

class RechargeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('accountant');
    }

    public function rules(): array
    {
        return [
            'pos_id' => ['required', 'integer', 'exists:point_of_sales,id'],
            'amount'           => ['required', 'numeric', 'min:1'],
            'payment_method'   => ['required', 'in:cash,bank_transfer,card'],
            'notes'            => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'pos_id.required' => 'حقل نقطة البيع مطلوب.',
            'pos_id.integer'  => 'نقطة البيع غير صحيحة.',
            'pos_id.exists'   => 'نقطة البيع المختارة غير موجودة.',
            'amount.required'           => 'المبلغ مطلوب.',
            'amount.numeric'            => 'المبلغ يجب أن يكون رقمًا.',
            'amount.min'                => 'المبلغ يجب أن يكون على الأقل 1.',
            'payment_method.required'   => 'طريقة الدفع مطلوبة.',
            'payment_method.in'         => 'طريقة الدفع غير صالحة.',
        ];
    }
}
