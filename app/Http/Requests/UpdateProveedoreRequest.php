<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProveedoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'rfc' => $this->rfc ? strtoupper(trim((string) $this->rfc)) : null,
            'regimen_fiscal' => $this->regimen_fiscal ? strtoupper(trim((string) $this->regimen_fiscal)) : null,
            'uso_cfdi' => $this->uso_cfdi ? strtoupper(trim((string) $this->uso_cfdi)) : null,
            'codigo_postal_fiscal' => $this->codigo_postal_fiscal ? trim((string) $this->codigo_postal_fiscal) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $proveedore = $this->route('proveedore');

        return [
            'razon_social' => 'required|max:255',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:15',
            'email' => 'nullable|max:255|email',
            'rfc' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
                Rule::unique('personas', 'rfc')
                    ->ignore($proveedore->persona->id)
                    ->where(fn ($query) => $query->where('rfc', '!=', 'XAXX010101000')),
            ],
            'regimen_fiscal' => 'nullable|string|size:3|exists:sat_regimenes_fiscales,clave',
            'codigo_postal_fiscal' => 'nullable|string|size:5',
            'uso_cfdi' => 'nullable|string|size:4|exists:sat_usos_cfdi,clave',
        ];
    }
}
