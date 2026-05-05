<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdateProductoRequest extends FormRequest
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
        $producto = $this->route('producto');
        return [
            'codigo' => 'nullable|unique:productos,codigo,'.$producto->id.'|max:50',
            'nombre' => 'required|unique:productos,nombre,'.$producto->id.'|max:255',
            'descripcion' => 'nullable|max:255',
            'img_path' => ['nullable', function ($attribute, $value, $fail) {
                if (!$value instanceof UploadedFile) {
                    $fail('El campo '.$attribute.' debe ser un archivo válido.');
                    return;
                }

                if (!$value->isValid()) {
                    $fail('El archivo cargado en '.$attribute.' no es válido.');
                    return;
                }

                if ($value->getSize() > 2048 * 1024) {
                    $fail('El campo '.$attribute.' no debe superar los 2MB.');
                }

                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $extension = strtolower((string) $value->getClientOriginalExtension());

                if (!in_array($extension, $allowedExtensions, true)) {
                    $fail('El campo '.$attribute.' debe tener una extensión válida: png, jpg o jpeg.');
                }
            }],
            'proveedore_id' => 'nullable|integer|exists:proveedores,id',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0'
        ];
    }

    public function attributes()
    {
        return [
            'proveedore_id' => 'proveedor',
            'presentacione_id' => 'presentación',
            'categoria_id' => 'categoria'
        ];
    }

    public function messages()
    {
        return [
            //'codigo.required' => 'Se necesita un campo código'
        ];
    }
}
