<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSMTPRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driver' => ['required', 'string', 'in:smtp'],
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'encryption' => ['required', 'string', 'in:tls,ssl,starttls'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
