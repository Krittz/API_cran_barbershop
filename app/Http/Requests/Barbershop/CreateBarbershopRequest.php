<?php

namespace App\Http\Requests\Barbershop;

use Illuminate\Foundation\Http\FormRequest;

class CreateBarbershopRequest extends FormRequest
{

    public function authorize(): bool
    {
        return in_array($this->user()->role, ['barber', 'adm']);
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255|unique:barbershops,address',
            'phone' => 'required|string|unique:barbershops,phone',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'address.required' => 'O endereço é obrigatório',
            'address.unique' => 'Erro ao processar o cadastro. Endereço inválido.',
            'phone.required' => 'O telefone é obrigatório',
            'phone.unique' => 'Erro ao processar o cadastro. Telefone inválido.',
        ];
    }
}
