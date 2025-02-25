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
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? 'NULL';

        return [
            'name' => 'nullable|string|max:255',
            'phone' => "nullable|string|unique:users,phone,{$userId}",
        ];
    }


    public function messages()
    {
        return [
            'phone.unique' => 'Este número de telefone já está em uso.'
        ];
    }
}
