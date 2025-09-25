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

            'from' => 'nullable|array',
            'from.address' => 'nullable|email',
            'from.name' => 'nullable|string|max:255',
            
            'to' => 'required|array',
            'to.address' => 'required|email',
            'to.name' => 'nullable|string|max:255',

            'reply_to' => 'nullable|array',
            'reply_to.address' => 'required_with:reply_to|email',
            'reply_to.name' => 'nullable|string|max:255',

            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',

            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // Max 10MB per file

            'parameters' => 'nullable|array',
        ];
    }
}
