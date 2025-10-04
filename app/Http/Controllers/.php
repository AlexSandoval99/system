<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class SalessReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::with('client');

        // --- Filtrar por cliente ---
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // --- Filtrar por periodo ---
        $periodo = $request->periodo;

        if ($periodo === 'diario') {
            $desde = $request->fecha_desde ?? today()->toDateString();
            $hasta = $request->fecha_hasta ?? today()->toDateString();
            $query->whereBetween('date', [$desde, $hasta]);
        }
        elseif ($periodo === 'mensual') {
            $mes = $request->mes ?? now()->month;
            $year = $request->anio ?? now()->year;
            $query->whereMonth('date', $mes)->whereYear('date', $year);
        }
        elseif ($periodo === 'anual') {
            $year = $request->anio ?? now()->year;
            $query->whereYear('date', $year);
        }

        $sales = $query->orderBy('date', 'desc')->paginate(10);

        return view('pages.sales-report.index', compact('sales'));
    }
}

