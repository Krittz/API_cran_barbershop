<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('user')->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|unique:users,phone,' . $this->route('user')->id,
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'A senha é obrigatória para confirmar a alteração.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'phone.unique' => 'Este número de telefone já está em uso.'
        ];
    }
}
