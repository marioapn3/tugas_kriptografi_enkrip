<?php

namespace App\Http\Requests\Transactions\Selling;

use Illuminate\Foundation\Http\FormRequest;

class CreateSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'no_transaction' => 'string|nullable|unique:journals,no_transaction',
            'date' => 'date|required',
            'description' => 'string|nullable',
            'customer_id' => 'required|exists:contacts,id',
            'account_id' => 'required|exists:accounts,id',
            'product_entries' => 'required|array',
            'product_entries.*.product_id' => 'required|exists:products,id',
            'product_entries.*.qty' => 'required|numeric',
            'product_entries.*.price' => 'required|numeric',
        ];
    }


    public function messages()
    {
        return [
            'product_entries.*.product_id.required' => 'Product is required',
            'product_entries.*.product_id.exists' => 'Product is not exists',
            'product_entries.*.qty.required' => 'Quantity is required',
            'product_entries.*.qty.numeric' => 'Quantity must be numeric',
            'product_entries.*.price.required' => 'Price is required',
            'product_entries.*.price.numeric' => 'Credit must be numeric',
        ];
    }
}
