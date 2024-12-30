<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est requis',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'email.required' => 'L\'email est requis',
            'email.email' => 'L\'email doit être une adresse email valide',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères',
            'email.unique' => 'Cet email est déjà utilisé',
        ];
    }
}
