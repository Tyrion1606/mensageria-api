<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'to' => 'required',
            'message' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'to.required' => 'The recipient field is required.',
            'message.required' => 'The message field is required.',
        ];
    }

}
