<?php

namespace App\Http\Requests\Transactions\Selling;

use Illuminate\Foundation\Http\FormRequest;

class SaveSettingPosRequest extends FormRequest
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
            'deposit_to_account_id' => 'required|exists:accounts,id',
            'date_default' => 'nullable|date',
        ];
    }
}
