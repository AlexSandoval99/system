<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\WishPurchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function compras()
    {
        $subModulos = [
            'pedido' => 'Pedido de Compras',
            'orden' => 'Orden de Compras',
            'factura' => 'Factura Compras',
            'inventario' => 'Inventario',
            'existencia' => 'Existencia',
            'libro' => 'Libro Compra',
        ];

        return view('reportes.compras', compact('subModulos'));
    }

    public function produccion()
    {
        $subModulos = [
            'presupuesto' => 'Presupuesto',
            'orden' => 'Orden de Producción',
            'control' => 'Control de Producción',
            'calidad' => 'Control de Calidad',
            'mermas' => 'Mermas',
            'costo' => 'Costo Producción',
        ];

        return view('reportes.produccion', compact('subModulos'));
    }

    public function ventas()
    {
        $subModulos = [
            'pedidos' => 'Pedidos',
            'factura' => 'Factura Ventas',
            'cobros' => 'Cobros',
            'remision' => 'Nota de Remisión',
        ];

        return view('reportes.ventas', compact('subModulos'));
    }

    public function generar(Request $request, $modulo, $submodulo)
    {
        // Acá procesás el filtro y buscás datos
        return view('reportes.resultado', [
            'modulo' => $modulo,
            'submodulo' => $submodulo,
            'filtros' => $request->all()
        ]);
    }
    public function ajaxCompras(Request $request)
    {
        $tipo = $request->input('tipo'); // pedido, orden, etc.
        $buscar = $request->input('buscar');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        $data = [];

        switch ($tipo) {
            case 'pedido':
                $query = WishPurchase::with('branch');

                if ($buscar) {
                    $query->where('number', 'like', "%$buscar%");
                }
                if ($fecha_inicio) {
                    $query->whereDate('date', '>=', $fecha_inicio);
                }
                if ($fecha_fin) {
                    $query->whereDate('date', '<=', $fecha_fin);
                }

                $compras = $query->orderBy('id', 'desc')->limit(50)->get();

                $tbody = '';
                foreach ($compras as $p) {
                    $tbody .= '
                        <tr>
                            <td>'.$p->number.'</td>
                            <td>'.$p->branch->name.'</td>
                            <td>'.Carbon::parse($p->date)->format('d/m/Y').'</td>
                            <td><span class="label label-'.config('constants.wish-purchase-status-label.' . $p->status).'">'
                                .config('constants.wish-purchase-status.' . $p->status).
                            '</span></td>
                        </tr>';
                }

                if ($tbody == '') {
                    $tbody = '<tr><td colspan="4" class="text-center text-muted">Sin resultados</td></tr>';
                }

                $data['tbody'] = $tbody;
                break;

            case 'orden':
                $query = PurchaseOrder::with(['branch', 'provider'])
                    ->orderBy('id', 'desc');

                if ($buscar) {
                    $query->where(function($q) use ($buscar) {
                        $q->where('number', 'LIKE', "%$buscar%")
                        ->orWhereHas('provider', function($sub) use ($buscar) {
                            $sub->where('name', 'LIKE', "%$buscar%");
                        });
                    });
                }

                if ($fecha_inicio) {
                    $query->whereDate('date', '>=', $fecha_inicio);
                }

                if ($fecha_fin) {
                    $query->whereDate('date', '<=', $fecha_fin);
                }

                $ordenes = $query->limit(50)->get();

                $tbody = '';
                foreach ($ordenes as $ord) {
                    $tbody .= '
                        <tr>
                            <td>'.$ord->number.'</td>
                            <td>'.$ord->provider->name.'</td>
                            <td>'.$ord->branch->name.'</td>
                            <td>'.\Carbon\Carbon::parse($ord->date)->format('d/m/Y').'</td>
                            <td>
                                <span class="label label-'.config('constants.purchase_order_status_label.' . $ord->status).'">'
                                    .config('constants.purchase_order_status.' . $ord->status).'
                                </span>
                            </td>
                        </tr>';
                }

                if ($tbody == '') {
                    $tbody = '<tr><td colspan="6" class="text-center text-muted">Sin resultados</td></tr>';
                }

                $data['tbody'] = $tbody;
                break;

            case 'factura':
                $query = Purchase::with(['branch', 'provider'])
                    ->where('type', '!=', 5)
                    ->whereIn('status', [1, 2, 3])
                    ->orderBy('id', 'desc');

                // 🔍 Filtro por búsqueda general (razón social, RUC o número)
                if ($buscar) {
                    $query->where(function($q) use ($buscar) {
                        $q->where('razon_social', 'LIKE', "%$buscar%")
                        ->orWhere('ruc', 'LIKE', "%$buscar%")
                        ->orWhere('number', 'LIKE', "%$buscar%");
                    });
                }

                // 📅 Filtro por fechas
                if ($fecha_inicio) {
                    $query->whereDate('date', '>=', $fecha_inicio);
                }
                if ($fecha_fin) {
                    $query->whereDate('date', '<=', $fecha_fin);
                }
                if($request->type)
                {
                    $query->where('type',$request->type);
                }

                // 👤 Filtro por proveedor
                if ($request->provider_id) {
                    $query->where('provider_id', $request->provider_id);
                }

                $facturas = $query->limit(50)->get();

                // 🧩 Construcción del tbody
                $tbody = '';
                foreach ($facturas as $f) {
                    $tbody .= '
                        <tr>
                            <td>'.$f->branch->name.'</td>
                            <td>'.\Carbon\Carbon::parse($f->date)->format('d/m/Y').'</td>
                            <td>'.config('constants.invoice_condition.' . $f->condition).'</td>
                            <td><span class="label label-'.config('constants.type_purchases_label.' . $f->type).'">'
                                .config('constants.type_purchases.' . $f->type).'</span></td>
                            <td>'.$f->number.'</td>
                            <td>'.$f->ruc.'</td>
                            <td>'.$f->provider->name.'</td>
                            <td class="text-right">'.number_format($f->amount, 2, ',', '.').'</td>
                            <td><span class="label label-'.config('constants.purchase-status-label.' . $f->status).'">'
                                .config('constants.purchase-status.' . $f->status).'</span></td>
                        </tr>';
                }

                if ($tbody == '') {
                    $tbody = '<tr><td colspan="10" class="text-center text-muted">Sin resultados</td></tr>';
                }

                $data['tbody'] = $tbody;
                break;

                default:
                $data['tbody'] = '<tr><td colspan="4" class="text-center text-muted">Tipo de reporte no válido</td></tr>';
                break;
        }

        return response()->json($data);
    }
    public function exportar(Request $request)
    {
        $tipo = $request->input('tipo'); // pedido, orden, factura...
        $formato = $request->input('export'); // excel o pdf

        // Reutilizamos la misma lógica del ajax
        $dataResponse = $this->ajaxCompras($request)->getData(true);
        $html = '<table border="1" style="width:100%; border-collapse: collapse;">'.$dataResponse['tbody'].'</table>';

        $titulo = strtoupper("Reporte de $tipo - ".now()->format('d/m/Y H:i'));

        if ($formato === 'excel') {
            $filename = "reporte_$tipo.xlsx";
            return Excel::download(new class($html) implements \Maatwebsite\Excel\Concerns\FromHtml {
                protected $html;
                public function __construct($html){ $this->html = $html; }
                public function html(): string { return $this->html; }
            }, $filename);
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadHTML('<h3>'.$titulo.'</h3>'.$html)->setPaper('a4', 'landscape');
            return $pdf->download("reporte_$tipo.pdf");
        }

        return back()->with('error', 'Formato no válido');
    }
}
