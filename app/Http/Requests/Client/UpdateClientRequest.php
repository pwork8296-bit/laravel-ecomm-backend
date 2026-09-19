<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('client') ?? $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'website_name' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'string', 'max:500'],
            'domain' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('clients', 'domain')->ignore($id),
            ],
            'logo' => ['nullable', 'string', 'max:500'],
            'default_meta_title' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
