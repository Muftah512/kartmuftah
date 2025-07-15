<?php

namespace App\Http\Requests\Accountant;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pos_id' => 'required|exists:point_of_sales,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'pos_id.required' => 'نقطة البيع مطلوبة',
            'amount.required' => 'المبلغ مطلوب',
            'description.required' => 'وصف الفاتورة مطلوب',
            'due_date.required' => 'تاريخ الاستحقاق مطلوب',
        ];
    }
}