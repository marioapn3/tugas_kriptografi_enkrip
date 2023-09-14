<?php

namespace App\Http\Requests\Contacts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
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
        $id = $this->contact->id;
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:contacts,email,' . $id,
            'phone_number' => 'nullable|string|max:255|unique:contacts,phone_number,' . $id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'portal_code' => 'nullable|string|max:255',
        ];
    }
}
