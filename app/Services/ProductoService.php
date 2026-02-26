<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductoService
{
    /**
     * Crear un Registro
     */
    public function crearProducto(array $data): Producto
    {
        return Producto::create([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'img_path' => isset($data['img_path'])
                ? $this->storeImage($data['img_path'])
                : null,
            'proveedore_id' => $data['proveedore_id'],
            'categoria_id' => $data['categoria_id'],
            'presentacione_id' => $data['presentacione_id'],
            'costo' => $data['costo'],
            'precio' => $data['precio'],
        ]);
    }

    /**
     * Editar un registro
     */
    public function editarProducto(array $data, Producto $producto): Producto
    {
        $producto->update([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'img_path' => isset($data['img_path'])
                ? $this->updateImage($data['img_path'], $producto->img_path)
                : $producto->img_path,
            'proveedore_id' => $data['proveedore_id'],
            'categoria_id' => $data['categoria_id'],
            'presentacione_id' => $data['presentacione_id'],
            'costo' => $data['costo'],
            'precio' => $data['precio'],

        ]);

        return $producto;
    }

    /**
     * Guarda una imagen en el Storage
     * 
     */
    private function storeImage(UploadedFile $image): string
    {
        $name = uniqid() . '.' . $image->getClientOriginalExtension();

        $image->storeAs('productos', $name, 'public');

        return 'storage/productos/' . $name;
    }


    /**
     * Edita una imagen en el Storage
     * 
     */

    private function updateImage(UploadedFile $image, string $oldPath): string
    {
        if (!empty($oldPath)) {
            $relativePath = str_replace('storage/', '', $oldPath);

            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        return $this->storeImage($image);
    }
}
