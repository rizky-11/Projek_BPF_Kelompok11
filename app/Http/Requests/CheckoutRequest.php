<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'customer_email' => 'nullable|email|max:150',
            'delivery_method' => 'required|in:ambil,antar',
            'shipping_address' => 'required_if:delivery_method,antar|nullable|string|max:500',
            'customer_note' => 'nullable|string|max:500',
            'website' => 'nullable|max:0', // honeypot: harus kosong
        ];
    }
}
