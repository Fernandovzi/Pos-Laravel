<?php

namespace App\Http\Requests;

use App\Enums\TipoPersonaEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StorePersonaRequest extends FormRequest
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
        return [
            'razon_social' => 'required|max:255',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:15',
            'tipo' => ['required', new Enum(TipoPersonaEnum::class)],
            'email' => 'nullable|max:255|email',
            'rfc' => ['nullable', 'string', 'size:13', 'regex:/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/', Rule::unique('personas', 'rfc')],
            'regimen_fiscal' => 'nullable|string|size:3',
            'codigo_postal_fiscal' => 'nullable|string|size:5',
            'uso_cfdi' => 'nullable|string|size:4',
        ];
    }
}
