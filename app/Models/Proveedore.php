<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Proveedore extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id'];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }


    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
    }

    /**
     * Obtener la razon social, tipo y número de documento del proveedor
     */
    public function getNombreDocumentoAttribute(): string
    {
        return $this->persona->razon_social;
    }
}
