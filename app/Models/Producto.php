<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function compras(): BelongsToMany
    {
        return $this->belongsToMany(Compra::class)
            ->withTimestamps()
            ->withPivot('cantidad', 'precio_compra', 'fecha_vencimiento');
    }

    public function ventas(): BelongsToMany
    {
        return $this->belongsToMany(Venta::class)
            ->withTimestamps()
            ->withPivot('cantidad', 'precio_venta');
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class)
            ->withTimestamps()
            ->withPivot('cantidad', 'precio');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // public function marca(): BelongsTo
    // {
    //     return $this->belongsTo(Marca::class);
    // }

    public function proveedore(): BelongsTo
    {
        return $this->belongsTo(Proveedore::class, 'proveedor_id');
    }

    public function presentacione(): BelongsTo
    {
        return $this->belongsTo(Presentacione::class);
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class);
    }

    public function kardex(): HasMany
    {
        return $this->hasMany(Kardex::class);
    }

    protected static function booted()
    {
        static::creating(function ($producto) {
            //Si no se propociona un código, generar uno único
            if (empty($producto->codigo)) {
                $producto->codigo = self::generateUniqueCode();
            }
        });
    }

    /**
     * Genera un código único para el producto
     */

    private static function generateUniqueCode(): string
    {
        return DB::transaction(function () {

            $lastCode = self::lockForUpdate()
                ->selectRaw('MAX(CAST(codigo AS UNSIGNED)) as codigo')
                ->value('codigo');

            $next = $lastCode ? $lastCode + 1 : 1;

            return str_pad($next, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Accesor para obtener el código y nombre
     */
    public function getNombreCompletoAttribute(): string
    {
        return "Código: {$this->codigo} - {$this->nombre}";
    }
}
