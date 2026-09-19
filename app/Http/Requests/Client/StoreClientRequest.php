<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'website_name' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'string', 'max:500'],
            'domain' => ['nullable', 'string', 'max:255', 'unique:clients,domain'],
            'logo' => ['nullable', 'string', 'max:500'],
            'default_meta_title' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
