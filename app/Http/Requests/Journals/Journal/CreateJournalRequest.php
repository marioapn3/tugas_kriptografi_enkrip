<?php

namespace App\Http\Requests\Journals\Journal;

use Illuminate\Foundation\Http\FormRequest;

class CreateJournalRequest extends FormRequest
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
            'no_transaction' => 'string|nullable|unique:journals,no_transaction',
            'date' => 'date|required',
            'description' => 'string|nullable',
            'journal_entries' => 'required|array',
            'journal_entries.*.account_id' => 'required|exists:accounts,id',
            'journal_entries.*.debit' => 'required|numeric',
            'journal_entries.*.credit' => 'required|numeric',
        ];
    }
}
