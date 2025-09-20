<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'setting_id' => 'required|string',
            'template_id' => 'required|string',

            'from' => 'required|array',
            'from.address' => 'required|email',
            'from.name' => 'nullable|string|max:255',
            
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',

            'parameters' => 'nullable|array',
        ];
    }
}
