<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedore_id' => ['required', 'exists:proveedores,id'],
            'persona_recojo' => ['required', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'fecha_entrega_estimada' => ['nullable', 'date'],
            'subtotal' => ['required', 'numeric', 'min:0.01'],
            'impuesto' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0.01'],
            'arrayidproducto' => ['required', 'array', 'min:1'],
            'arrayidproducto.*' => ['required', 'exists:productos,id'],
            'arraycantidad' => ['required', 'array', 'min:1'],
            'arraycantidad.*' => ['required', 'integer', 'min:1'],
            'arrayprecio' => ['required', 'array', 'min:1'],
            'arrayprecio.*' => ['required', 'numeric', 'min:0'],
        ];
    }
}
