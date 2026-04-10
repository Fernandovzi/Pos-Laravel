<?php

namespace App\Models;

use App\Enums\MetodoPagoEnum;
use App\Observers\VentaObsever;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[ObservedBy(VentaObsever::class)]
class Venta extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'metodo_pago' => MetodoPagoEnum::class,
    ];

    protected $attributes = [
        'estado' => 'ACTIVA',
    ];

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comprobante(): BelongsTo
    {
        return $this->belongsTo(Comprobante::class);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)
            ->withTimestamps()
            ->withPivot('cantidad', 'precio_original', 'precio_venta', 'descuento_porcentaje');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(VentaPago::class);
    }

     /**
     * Obtener solo la fecha
     * @return string
     */
    public function getFechaAttribute(): string
    {
        return Carbon::parse($this->fecha_hora)->format('d-m-Y');
    }

    /**
     * Obtener solo la hora
     * @return string
     */
    public function getHoraAttribute(): string
    {
        return Carbon::parse($this->fecha_hora)->format('H:i');
    }


    /**
     * Generar el número de venta
     */
    public function generarNumeroVenta(string $tipoComprobante): string
    {
        $prefijo = str_contains(strtoupper($tipoComprobante), 'FACTURA') ? 'F' : 'T';

        return DB::transaction(function () use ($prefijo) {
            $ultimoConsecutivo = self::query()
                ->lockForUpdate()
                ->where('numero_comprobante', 'like', $prefijo . '%')
                ->selectRaw('MAX(CAST(SUBSTRING(numero_comprobante, 2) AS UNSIGNED)) as consecutivo')
                ->value('consecutivo');

            $nuevoConsecutivo = ($ultimoConsecutivo ?? 0) + 1;

            return $prefijo . str_pad((string) $nuevoConsecutivo, 5, '0', STR_PAD_LEFT);
        });
    }
}
