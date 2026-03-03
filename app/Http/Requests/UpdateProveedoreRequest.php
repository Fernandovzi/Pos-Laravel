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
            'rfc' => ['nullable', 'string', 'size:13', 'regex:/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/', Rule::unique('personas', 'rfc')->ignore($proveedore->persona->id)],
            'regimen_fiscal' => 'nullable|string|size:3',
            'codigo_postal_fiscal' => 'nullable|string|size:5',
            'uso_cfdi' => 'nullable|string|size:4',
        ];
    }
}
