<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', 'min:6', 'confirmed'],
            'position'     => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Ism kiritish shart.',
            'email.required'     => 'Email kiritish shart.',
            'email.unique'       => 'Bu email allaqachon ro\'yxatdan o\'tgan.',
            'password.required'  => 'Parol kiritish shart.',
            'password.min'       => 'Parol kamida 6 ta belgidan iborat bo\'lishi kerak.',
            'password.confirmed' => 'Parollar mos kelmadi.',
        ];
    }
}
