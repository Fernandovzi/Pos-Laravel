<?php

namespace App\Http\Controllers;

use App\Enums\MetodoPagoEnum;
use App\Http\Requests\StoreMovimientoRequest;
use App\Models\Caja;
use App\Models\Movimiento;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class MovimientoController extends Controller
{
    function __construct()
    {
        $this->middleware('check_movimiento_caja_user', ['only' => ['index', 'create', 'store']]);
    }

    public function index(Request $request): View
    {
        $caja = Caja::with('movimientos')->findOrFail($request->caja_id);

        $totalesPorMetodo = $caja->movimientos
            ->filter(fn ($movimiento) => $movimiento->tipo?->value === 'VENTA')
            ->groupBy(fn ($movimiento) => $movimiento->metodo_pago?->value)
            ->map(fn ($movimientos) => round((float) $movimientos->sum('monto'), 2));

        return view('movimiento.index', compact('caja', 'totalesPorMetodo'));
    }

    public function create(Request $request): View
    {
        $caja_id = $request->get('caja_id');
        $optionsMetodoPago = MetodoPagoEnum::cashRegisterMethods();

        return view('movimiento.create', compact('optionsMetodoPago', 'caja_id'));
    }

    public function store(StoreMovimientoRequest $request): RedirectResponse
    {
        try {
            Movimiento::create($request->validated());
            ActivityLogService::log('Creación de movimiento', 'Movimientos', $request->validated());
            return redirect()->route('movimientos.index', ['caja_id' => $request->caja_id])
                ->with('success', 'retiro registrado');
        } catch (Throwable $e) {
            Log::error('Error al crear el movimiento', ['error' => $e->getMessage()]);
            return redirect()->route('movimientos.index', ['caja_id' => $request->caja_id])
                ->with('error', 'Ups, algo falló');
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
