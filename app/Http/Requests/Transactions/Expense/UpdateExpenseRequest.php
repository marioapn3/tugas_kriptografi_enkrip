<?php

namespace App\Http\Requests\Transactions\Expense;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'payment_account' => 'required|exists:accounts,id',
            'expense_details' => 'required|array',
            'expense_details.*.expense_account' => 'required|exists:accounts,id',
            'expense_details.*.description' => 'required|string',
            'expense_details.*.total_expense' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'expense_details.*.expense_account.required' => 'Expense Account is required',
            'expense_details.*.expense_account.exists' => 'Expense Account is not exists',
            'expense_details.*.description.required' => 'Description is required',
            'expense_details.*.total_expense.required' => 'Total Price is required',
            'expense_details.*.total_expense.numeric' => 'Total Price must be numeric',
        ];
    }
}
