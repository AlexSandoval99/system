<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CashBox;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Voucher;
use App\Models\VoucherCollect;
use App\Models\VoucherCollectPayment;
use App\Models\VoucherPayment;
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
        $payment_methods = PaymentMethod::where('status',1)->pluck('name', 'id');
        return view('pages.payments.create', compact('branches','cash_boxes','payment_methods'));
    }

    public function store(Request $request)
    {
        $payments = Voucher::create([
            'date' => $request->fecha,
            'branch_id' => $request->branch_id,
            'voucher_box_id' => $request->expedicion,
            'voucher_number' => $request->voucher_number,
            'voucher_condition' => 1,
            'expiration' => null,
            'client_id' => $request->cliente_id,
            'razon_social' => $request->razon_social,
            'ruc' => $request->ruc,
            'phone' => null,
            'address' => null,
            'voucher_type' =>3,
            'observation' => $request->observacion,
            'amount' => sum_array($request->monto_cuota),
            'total_excenta' => 0,
            'total_iva5' => 0,
            'total_iva10' => 0,
            'status' => 1,
            'user_id' => auth()->user()->id,
            'stamped_id' => $request->expedicion
        ]);

        foreach($request->cuota_nro as $key => $cuota)
        {
            VoucherCollectPayment::create([
                'voucher_id' => $payments->id,
                'voucher_collect_id' => $request->cuota_id[$key],
                'amount' => $request->monto_cuota[$key]
            ]);

            $cuota = VoucherCollect::find($request->cuota_id[$key]);
            $cuota->update([
                'residue' => $cuota->residue - $request->monto_cuota[$key]
            ]);
        }

        foreach($request->forma_pago as $key1 => $forma_pago)
        {
            VoucherPayment::create([
                'voucher_id' => $payments->id,
                'payment_method_id' => $forma_pago,
                'amount' => $request->monto_pago[$key1],
                'check_number' => $request->nro_cheque[$key1] ?? null,
                'check_expiration' => $request->vencimiento_cheque[$key1] ?? null,
                'status' => 1
            ]);
        }

        return response()->json([
            'message' => 'Cobro registrado correctamente.',
            'redirect' => route('payments')
        ]);
    }

    public function getCuotas()
    {
        $cuotas = VoucherCollect::where('voucher_id', request()->factura_id)
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
    public function getFacturas()
    {
        $facturas = Voucher::with('voucherCollects')
            ->where('client_id', request()->client_id)
            ->where('voucher_type', 1)
            ->where('status', 1)
            ->whereHas('voucherCollects', function($query) {
                $query->where('residue', '>', 0);
            })
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
