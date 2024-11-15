<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgreementRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'agree' => ['required', 'accepted'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
