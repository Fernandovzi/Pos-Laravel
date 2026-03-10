<?php

namespace App\Http\Controllers;

use App\Enums\TipoPersonaEnum;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedoreRequest;
use App\Models\Persona;
use App\Models\Proveedore;
use App\Models\SatRegimenFiscal;
use App\Models\SatUsoCfdi;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class proveedorController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-proveedore|crear-proveedore|editar-proveedore|eliminar-proveedore', ['only' => ['index']]);
        $this->middleware('permission:crear-proveedore', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-proveedore', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-proveedore', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $proveedores = Proveedore::with('persona')->latest()->get();

        return view('proveedore.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $optionsTipoPersona = TipoPersonaEnum::cases();
        $regimenesFiscales = SatRegimenFiscal::where('estado', 1)->orderBy('clave')->get();
        $usosCfdi = SatUsoCfdi::where('estado', 1)->orderBy('clave')->get();

        return view('proveedore.create', compact('optionsTipoPersona', 'regimenesFiscales', 'usosCfdi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request): void {
                $persona = Persona::create($request->validated());
                $persona->proveedore()->create([]);
            });

            ActivityLogService::log('Creacion de proveedor', 'Proveedores', $request->validated());

            return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado');
        } catch (Throwable $e) {
            Log::error('Error al crear al proveedor', ['error' => $e->getMessage()]);

            return redirect()->route('proveedores.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedore $proveedore): View
    {
        $proveedore->load('persona');
        $regimenesFiscales = SatRegimenFiscal::where('estado', 1)->orderBy('clave')->get();
        $usosCfdi = SatUsoCfdi::where('estado', 1)->orderBy('clave')->get();

        return view('proveedore.edit', compact('proveedore', 'regimenesFiscales', 'usosCfdi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedoreRequest $request, Proveedore $proveedore): RedirectResponse
    {
        try {
            $proveedore->persona->update($request->validated());
            ActivityLogService::log('Edición de proveedor', 'Proveedores', $request->validated());

            return redirect()->route('proveedores.index')->with('success', 'Proveedor editado');
        } catch (Throwable $e) {
            Log::error('Error al editar al proveedor', ['error' => $e->getMessage()]);

            return redirect()->route('proveedores.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $persona = Persona::findOrfail($id);

            $nuevoEstado = $persona->estado == 1 ? 0 : 1;
            $persona->update(['estado' => $nuevoEstado]);
            $message = $nuevoEstado == 1 ? 'Proveedor restaurado' : 'Proveedor eliminado';

            ActivityLogService::log($message, 'Proveedores', [
                'persona_id' => $id,
                'estado' => $nuevoEstado,
            ]);

            return redirect()->route('proveedores.index')->with('success', $message);
        } catch (Throwable $e) {
            Log::error('Error al eliminar/restaurar al proveedor', ['error' => $e->getMessage()]);

            return redirect()->route('proveedores.index')->with('error', 'Ups, algo falló');
        }
    }
}
