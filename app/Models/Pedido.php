<?php

namespace App\Models;

use App\Enums\EstadoPedidoEnum;
use App\Observers\PedidoObserver;
use App\Models\Proveedore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(PedidoObserver::class)]
class Pedido extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'estado' => EstadoPedidoEnum::class,
        'fecha_apartado' => 'datetime',
        'fecha_entrega_estimada' => 'datetime',
    ];

    /**
     * Genera el folio incremental diario para pedidos.
     */
    public static function generarFolio(): string
    {
        $prefijo = 'PED-' . now()->format('Ymd');
        $ultimo = static::query()->where('folio', 'like', $prefijo . '-%')->latest('id')->first();
        $secuencia = $ultimo ? ((int) substr($ultimo->folio, -4)) + 1 : 1;

        return sprintf('%s-%04d', $prefijo, $secuencia);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function proveedore(): BelongsTo
    {
        return $this->belongsTo(Proveedore::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad', 'precio')
            ->withTimestamps();
    }

    public function getFechaFormatAttribute(): string
    {
        return Carbon::parse($this->fecha_apartado)->format('d/m/Y H:i');
    }
}
