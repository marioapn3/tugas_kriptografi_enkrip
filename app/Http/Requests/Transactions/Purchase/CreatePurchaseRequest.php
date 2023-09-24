<?php

namespace App\Http\Requests\Transactions\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'no_transaction' => 'string|nullable|unique:purchases,no_transaction',
            'date' => 'date|required',
            'description' => 'string|nullable',
            'account_id' => 'required|exists:accounts,id',
            'supplier_id' => 'required|exists:contacts,id',
            'purchase_details' => 'required|array',
            'purchase_details.*.product_id' => 'required|exists:products,id',
            'purchase_details.*.quantity' => 'required|numeric',
            'purchase_details.*.price_per_unit' => 'required|numeric',
            'purchase_details.*.total_price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'purchase_details.*.product_id.required' => 'Account is required',
            'purchase_details.*.product_id.exists' => 'Account is not exists',
            'purchase_details.*.quantity.required' => 'Quantity is required',
            'purchase_details.*.quantity.numeric' => 'Quantity must be numeric',
            'purchase_details.*.price_per_unit.required' => 'Price is required',
            'purchase_details.*.price_per_unit.numeric' => 'Price must be numeric',
            'purchase_details.*.total_price.required' => 'Total Price is required',
            'purchase_details.*.total_price.numeric' => 'Total Price must be numeric',
        ];
    }
}
