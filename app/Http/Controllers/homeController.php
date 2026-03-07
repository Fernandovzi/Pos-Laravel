<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class homeController extends Controller
{
    public function index(): View
    {
        if (!Auth::check()) {
            return view('welcome');
        }

        $productosRegistrados = DB::table('productos')->count();

        $existenciaTotalProductos = DB::table('inventario')->sum('cantidad');

        $pedidosApartados = DB::table('pedidos')
            ->where('estado', 'APARTADO')
            ->count();

        $ventasTotales = DB::table('ventas')->sum('total');

        $produccionInternaPorDia = DB::table('kardex')
            ->selectRaw('DATE(created_at) as fecha, SUM(COALESCE(entrada, 0)) as total')
            ->where('tipo_transaccion', 'PRODUCCION_INTERNA')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha', 'asc')
            ->get()->toArray();

        return view('panel.index', compact(
            'productosRegistrados',
            'existenciaTotalProductos',
            'pedidosApartados',
            'ventasTotales',
            'produccionInternaPorDia'
        ));
    }
}
