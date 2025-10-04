<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\WishPurchase;
use App\Models\Provider;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseReportController extends Controller
{
    public function index(Request $request)
    {
        $purchases = collect();
        $selectedProvider = null;

        if ($request->proceso == 4) {
            // --- Factura de Compra
            $query = Purchase::with(['provider', 'branch']);

            // Filtro por fecha
            if ($request->date_range) {
                [$start, $end] = preg_split('/\s*-\s*/', trim($request->date_range));
                $from_date  = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
                $until_date = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();
                $query->whereBetween('date', [$from_date, $until_date]);
            }

            // Filtro por proveedor
            if ($request->filled('provider_id')) {
                $query->where('provider_id', $request->provider_id);
                $selectedProvider = Provider::find($request->provider_id);
            }

            $purchases = $query->orderBy('date', 'desc')->paginate(20);

        } elseif ($request->proceso == 1) {
            // --- Pedido de Compra
            $query = WishPurchase::with(['branch']); 

            // Filtro por fecha
            if ($request->date_range) {
                [$start, $end] = preg_split('/\s*-\s*/', trim($request->date_range));
                $from_date  = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
                $until_date = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();
                $query->whereBetween('date', [$from_date, $until_date]);
            }

            $purchases = $query->orderBy('date', 'desc')->paginate(20);

        } elseif ($request->proceso == 3) {
            // --- Orden de Compra
            $query = PurchaseOrder::with(['provider', 'branch']);

            // Filtro por fecha
            if ($request->date_range) {
                [$start, $end] = preg_split('/\s*-\s*/', trim($request->date_range));
                $from_date  = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
                $until_date = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();
                $query->whereBetween('date', [$from_date, $until_date]);
            }

            // Filtro por proveedor
            if ($request->filled('provider_id')) {
                $query->where('provider_id', $request->provider_id);
                $selectedProvider = Provider::find($request->provider_id);
            }

            $purchases = $query->orderBy('date', 'desc')->paginate(20);
        }

        return view('pages.purchase-report.index', compact('purchases', 'selectedProvider'));
    }

    public function exportExcel(Request $request)
    {
        if ($request->proceso == 4) {
            // --- Factura de Compra
            $query = Purchase::with(['provider', 'branch']);

            if ($request->filled('provider_id')) {
                $query->where('provider_id', $request->provider_id);
            }

            if ($request->filled('date_range')) {
                [$start, $end] = preg_split('/\s*-\s*/', trim($request->date_range));
                $startDate = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
                $endDate   = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            $purchases = $query->orderBy('date', 'desc')->get();

            $data = $purchases->map(function ($purchase) {
                return [
                    'Sucursal'   => $purchase->branch->name ?? '-',
                    'Fecha'      => optional($purchase->date)->format('d/m/Y'),
                    'Condición'  => config('constants.invoice_condition.' . $purchase->condition),
                    'Tipo'       => config('constants.type_purchases.' . $purchase->type),
                    'Número'     => $purchase->number,
                    'RUC'        => $purchase->ruc,
                    'Proveedor'  => $purchase->provider->name ?? '-',
                    'Monto'      => number_format($purchase->amount, 2, ',', '.'),
                    'Estado'     => config('constants.purchase-status.' . $purchase->status),
                ];
            });

            $filename = 'Reporte_Factura_Compras.xlsx';

        } elseif ($request->proceso == 1) {
            // --- Pedido de Compra     
            $query = WishPurchase::with(['branch']);

            if ($request->filled('date_range')) {
                [$start, $end] = preg_split('/\s*-\s*/', trim($request->date_range));
                $startDate = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
                $endDate   = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            $purchases = $query->orderBy('date', 'desc')->get();

            $data = $purchases->map(function ($purchase) {
                return [
                    'ID'        => $purchase->id,
                    'Sucursal'  => $purchase->branch->name ?? '-',
                    'Fecha'     => optional($purchase->date)->format('d/m/Y'),
                    'Estado'    => config('constants.purchase-status.' . $purchase->status),
                ];
            });

            $filename = 'Reporte_Pedido_Compras.xlsx';

        } elseif ($request->proceso == 3) {
            // --- Orden de Compra
            $query = PurchaseOrder::with(['provider', 'branch']);

            if ($request->filled('provider_id')) {
                $query->where('provider_id', $request->provider_id);
            }

            if ($request->filled('date_range')) {
                [$start, $end] = preg_split('/\s*-\s*/', trim($request->date_range));
                $startDate = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
                $endDate   = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            $purchases = $query->orderBy('date', 'desc')->get();

            $data = $purchases->map(function ($purchase) {
                return [
                    'Nro°'      => $purchase->number,
                    'Proveedor' => $purchase->provider->name ?? '-',
                    'Sucursal'  => $purchase->branch->name ?? '-',
                    'Fecha'     => optional($purchase->date)->format('d/m/Y'),
                    'Estado'    => config('constants.purchase-status.' . $purchase->status),
                ];
            });

            $filename = 'Reporte_Orden_Compras.xlsx';
        } else {
            return back()->with('error', 'Proceso no válido para exportar.');
        }

        if ($data->isEmpty()) {
            return back()->with('error', 'No hay datos para exportar.');
        }

        return Excel::download(new PurchasesExport($data), $filename);
    }
}







