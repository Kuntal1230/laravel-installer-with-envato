<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'envato_username' => ['required', 'string', 'max:255'],
            'purchase_code' => ['required', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
