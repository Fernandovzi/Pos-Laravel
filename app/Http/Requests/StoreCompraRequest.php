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
            'arrayfechavencimiento' => 'nullable|array',
            'arrayfechavencimiento.*' => 'nullable|date',
        ];
    }

    /**
     * Normaliza estructuras para evitar desalineación de índices por entradas vacías.
     */
    protected function prepareForValidation(): void
    {
        $productoIds = array_values(array_filter((array) $this->input('arrayidproducto', []), static fn($value) => $value !== null && $value !== ''));
        $cantidades = array_values(array_filter((array) $this->input('arraycantidad', []), static fn($value) => $value !== null && $value !== ''));

        $this->merge([
            'arrayidproducto' => $productoIds,
            'arraycantidad' => $cantidades,
            'arrayfechavencimiento' => array_values((array) $this->input('arrayfechavencimiento', [])),
        ]);
    }

    /**
     * Garantiza que el detalle de productos esté completo por cada fila.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $productoIds = (array) $this->input('arrayidproducto', []);
            $cantidades = (array) $this->input('arraycantidad', []);

            if (count($productoIds) !== count($cantidades)) {
                $validator->errors()->add('arraycantidad', 'El detalle contiene filas incompletas.');
            }
        });
    }
}
