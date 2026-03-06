<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAjusteInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'producto_id' => 'required|exists:productos,id',
            'cantidad_fisica' => 'required|integer|min:0',
            'motivo' => 'nullable|string|max:500',
            'confirmar_ajuste' => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'confirmar_ajuste.accepted' => 'Debe confirmar el ajuste antes de aplicarlo.',
        ];
    }
}
