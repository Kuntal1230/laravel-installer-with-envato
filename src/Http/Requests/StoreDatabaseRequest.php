<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatabaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driver' => ['required', 'string', 'in:mysql,pgsql,sqlsrv'],
            'host' => ['required', 'string'],
            'port' => ['required', 'numeric'],
            'database' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['nullable', 'string'],
            'force' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
