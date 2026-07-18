<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Guest checkout is allowed
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            // Must look like a real phone (digits, optional +, spaces/dashes) since
            // fulfilment is phone/WhatsApp-based — rejects garbage like "abc".
            'phone'       => ['required', 'string', 'max:20', 'regex:/^\+?[0-9][0-9\s\-]{8,19}$/'],
            'address'     => 'required|string|max:1000',
            'city'        => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'notes'       => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Please enter your full name.',
            'email.required'   => 'Please enter your email address.',
            'phone.required'   => 'Please enter your phone number.',
            'phone.regex'      => 'Please enter a valid phone number.',
            'address.required' => 'Please enter your delivery address.',
        ];
    }
}
