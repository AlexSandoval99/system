<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CashBox;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\VoucherCollect;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Voucher::where('voucher_type',3);

        $payments = $payments->paginate(20);

        return view('pages.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $branches = Branch::where('status',1)->pluck('name', 'id');
        $cash_boxes = CashBox::where('status',1)->pluck('name', 'id');
        $payments = Voucher::where('voucher_type',3)->get();
        return view('pages.payments.create', compact('branches','cash_boxes'));
    }

    public function getCuotas($facturaId)
    {
        $cuotas = VoucherCollect::where('voucher_id', $facturaId)
            ->get()
            ->map(function($c){
                return [
                    'id' => $c->id,
                    'factura' => $c->voucher->voucher_fullnumber,
                    'cuota' => $c->number,
                    'vencimiento' => $c->expiration,
                    'monto' => $c->amount,
                    'residue' => $c->residue,
                ];
            });

        return response()->json($cuotas);
    }
    public function getFacturas($clienteId)
    {
        $facturas = Voucher::with('voucherCollects')
            ->where('client_id', $clienteId)
            ->where('voucher_type', 1) // Solo facturas
            ->where('status', 1) // Solo activas
            ->whereHas('voucherCollects', function($query) {
                $query->where('residue', '>', 0);
            }) // Solo con cuotas pendientes
            ->get()
            ->map(function($f){
                return [
                    'id' => $f->id,
                    'fecha' => $f->date->format('d/m/Y'),
                    'numero' => $f->voucher_fullnumber,
                ];
            });

        return response()->json($facturas);
    }
}
