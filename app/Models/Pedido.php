<?php

namespace App\Models;

use App\Enums\EstadoPedidoEnum;
use App\Observers\PedidoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

#[ObservedBy(PedidoObserver::class)]
class Pedido extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'estado' => EstadoPedidoEnum::class,
    ];

    /**
     * Genera el folio incremental para pedidos con prefijo P.
     */
    public static function generarFolio(): string
    {
        return DB::transaction(function () {
            $ultimoConsecutivo = static::query()
                ->lockForUpdate()
                ->where('folio', 'like', 'P%')
                ->selectRaw('MAX(CAST(SUBSTRING(folio, 2) AS UNSIGNED)) as consecutivo')
                ->value('consecutivo');

            $nuevoConsecutivo = ($ultimoConsecutivo ?? 0) + 1;

            return 'P' . str_pad((string) $nuevoConsecutivo, 5, '0', STR_PAD_LEFT);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function proveedore(): BelongsTo
    {
        return $this->belongsTo(Proveedore::class, 'proveedore_id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)
            ->withTimestamps()
            ->withPivot('cantidad', 'precio');
    }
}
