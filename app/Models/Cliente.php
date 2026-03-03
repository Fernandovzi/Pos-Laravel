<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id'];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Obtener la razón social y RFC (si existe) del cliente.
     */
    public function getNombreDocumentoAttribute(): string
    {
        if (!empty($this->persona->rfc)) {
            return $this->persona->razon_social . ' - RFC: ' . $this->persona->rfc;
        }

        return $this->persona->razon_social;
    }
}
