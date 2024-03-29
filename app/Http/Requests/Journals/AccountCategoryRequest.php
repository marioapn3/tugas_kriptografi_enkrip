<?php

namespace App\Http\Requests\Journals;

use Illuminate\Foundation\Http\FormRequest;

class AccountCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'required|max:100|unique:account_categories,code,' . $this->id,
            'classification_id' => 'required|exists:classifications,id'
        ];
    }
}
