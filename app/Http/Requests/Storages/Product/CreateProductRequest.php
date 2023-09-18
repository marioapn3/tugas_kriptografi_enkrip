<?php

namespace App\Http\Requests\Storages\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'purchase_price' => 'required|integer',
            'sale_price' => 'required|integer',
            'purchase_account' => 'required|integer|exists:accounts,id',
            'sale_account' => 'required|integer|exists:accounts,id',
            'inventory_account' => 'required|integer|exists:accounts,id',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
