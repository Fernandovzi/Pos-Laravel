<?php

namespace App\Models;

use App\Enums\TipoPersonaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tipo' => TipoPersonaEnum::class,
    ];

    public function regimenFiscalCatalogo(): BelongsTo
    {
        return $this->belongsTo(SatRegimenFiscal::class, 'regimen_fiscal', 'clave');
    }

    public function usoCfdiCatalogo(): BelongsTo
    {
        return $this->belongsTo(SatUsoCfdi::class, 'uso_cfdi', 'clave');
    }

    public function proveedore(): HasOne
    {
        return $this->hasOne(Proveedore::class);
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }
}
