<?php

namespace App\Http\Requests;

class StoreProveedoreRequest extends StorePersonaRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return parent::rules() + [
            'porcentaje_comision' => ['required', 'numeric', 'between:0,100'],
        ];
    }
}
