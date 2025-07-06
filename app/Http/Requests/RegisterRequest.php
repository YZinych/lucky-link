<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+?[0-9\s\-\(\)]+$/'
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
