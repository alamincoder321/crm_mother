<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'invoice' => 'required',
            'type' => 'required',
            'date' => 'required',
            'amount' => 'required',
        ];

        if ($this->type == 'customer') {
            $rules['customer_id'] = 'required';
        }
        if ($this->type == 'supplier') {
            $rules['supplier_id'] = 'required';
        }
        if ($this->payment_method == 'bank') {
            $rules['bank_id'] = 'required';
        }

        return $rules;
    }
}
