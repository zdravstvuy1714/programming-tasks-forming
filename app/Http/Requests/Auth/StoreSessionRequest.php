<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'         => ['required'],
            'password'      => ['required'],
            'device_name'   => ['required'],
        ];
    }
}
