<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AccountUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => ['required', 'sometimes', 'string', 'max:255'],
            'last_name' => ['required', 'sometimes', 'string', 'max:255'],
            'email' => ['required', 'sometimes', 'email', 'unique:users,email,'.auth()->guard()->id()],
            'password' => ['nullable', Password::defaults()],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
        ];
    }
}
