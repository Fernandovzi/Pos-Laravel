<?php

namespace App\Models;

use App\Enums\MetodoPagoEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaPago extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'metodo_pago' => MetodoPagoEnum::class,
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }
}
