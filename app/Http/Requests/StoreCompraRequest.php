<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_hora' => 'required|date|date_format:Y-m-d\\TH:i',
            'arrayidproducto' => 'required|array|min:1',
            'arrayidproducto.*' => 'required|exists:productos,id',
            'arraycantidad' => 'required|array|min:1',
            'arraycantidad.*' => 'required|integer|min:1',
            'arraypreciocompra' => 'required|array|min:1',
            'arraypreciocompra.*' => 'required|numeric|min:0.01',
            'arrayfechavencimiento' => 'nullable|array',
            'arrayfechavencimiento.*' => 'nullable|date',
        ];
    }
}
