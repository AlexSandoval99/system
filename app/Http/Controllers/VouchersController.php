<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVoucherRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\CashBoxUser;
use App\Models\Stamped;
use App\Models\Voucher;
use App\Models\VoucherBox;
use App\Models\VoucherCollect;
use App\Models\VoucherDetail;
use App\Models\VoucherNoteCredit;
use Illuminate\Http\Request;

class VouchersController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('branch')->orderBy('id','DESC');

        $vouchers = $vouchers->paginate(20);
        return view('pages.vouchers.index', compact('vouchers'));
    }

    public function create(Request $request)
    {
        $branches = Branch::where('status',1)->pluck('name', 'id');
        $articulos = Articulo::where('status',1)->pluck('name', 'id');
        return view('pages.vouchers.create', compact('branches','articulos'));
    }

    public function store(CreateVoucherRequest $request)
    {
        if(request()->tipoDocumento == 1)
        {
            $factura = Voucher::create([
                'date' => $request->date,
                'branch_id' => $request->branch_id,
                'voucher_box_id' => $request->expedicion,
                'voucher_number' => $request->voucher_number,
                'voucher_condition' => $request->condicion,
                'expiration' => $request->vig_timbrado,
                'client_id' => $request->client_id,
                'razon_social' => $request->razon_social,
                'ruc' => $request->ruc,
                'phone' => null,
                'address' => null,
                'voucher_type' =>1,
                'observation' => $request->observacion,
                'amount' => 0,
                'total_excenta' => 0,
                'total_iva5' => 0,
                'total_iva10' => 0,
                'status' => 1,
                'user_id' => auth()->user()->id,
                'stamped_id' => $request->id_timb
            ]);
        }
        else if(request()->tipoDocumento == 2)
        {
            $factura = Voucher::create([
                'date' => $request->date,
                'branch_id' => $request->branch_id,
                'voucher_box_id' => $request->expedicion,
                'voucher_number' => $request->voucher_number,
                'voucher_condition' => $request->condicion,
                'expiration' => $request->vig_timbrado,
                'client_id' => $request->client_id,
                'razon_social' => $request->razon_social,
                'ruc' => $request->ruc,
                'phone' => null,
                'address' => null,
                'voucher_type' => 2,
                'observation' => $request->observacion,
                'amount' => 0,
                'total_excenta' => 0,
                'total_iva5' => 0,
                'total_iva10' => 0,
                'status' => 1,
                'user_id' => auth()->user()->id,
                'stamped_id' => $request->id_timb
            ]);

            $nota = VoucherNoteCredit::create([
                'voucher_id' => $factura->id,
                'invoice_id' => $request->invoice_id,
            ]);
        }

        foreach ($request->articulo as $key => $value)
        {
            VoucherDetail::create([
                'voucher_id' => $factura->id,
                'articulo_id' => $value,
                'description' => $request->observacion ?? 'vacio',
                'quantity' => $request->quantity[$key],
                'amount' => $request->precio[$key],
                'iva5'  => 0,
                'iva10' => round($request->precio[$key] * 10 / 110, 0)
            ]);
            $factura->update([
                'amount' => $factura->amount += ($request->precio[$key] * $request->quantity[$key])
            ]);
        }

        $iva10 = $factura->amount / 11;
        $factura->update([
            'total_iva10' => $iva10
        ]);

        if($request->condicion == 2)
        {
            for ($i=0; $i < $request->intervalo ; $i++)
            {
                VoucherCollect::create([
                    'voucher_id' => $factura->id,
                    'number' => $i + 1,
                    'expiration' => now()->addMonths($i + 1),
                    'amount' => round($factura->amount / $request->intervalo, 0),
                    'residue' => round($factura->amount / $request->intervalo, 0)
                ]);
            }
        }
        else
        {
            VoucherCollect::create([
                    'voucher_id' => $factura->id,
                    'number' => 1,
                    'expiration' => now(),
                    'amount' => round($factura->amount),
                    'residue' => round($factura->amount)
                ]);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('voucher'),
            'message' => 'Comprobante registrado correctamente'
        ]);
    }

    public function ajaxExpedicion()
    {
        if(request()->ajax())
        {
            $cash_users = CashBoxUser::with(['cashBox.voucher_box'])->where('cash_box_users.user_id', auth()->user()->id)
                ->whereHas('cashBox.voucher_box', function($q) {
                    $q->where('branch_id', request()->branch_id);
                })
                ->get();
            $results = [];
            foreach ($cash_users as $key => $expedition)
            {

                $results[$key]['establecimiento']   = str_pad($expedition->cashBox->voucher_box->branch_id, 3, "0", STR_PAD_LEFT);
                $results[$key]['expedicion']        = str_pad($expedition->cashBox->voucher_box->voucher_number, 3, "0", STR_PAD_LEFT);
                $results[$key]['id']                = $expedition->cashBox->voucher_box->id;
            }
            return response()->json($results);
        }
    }

    public function ajaxTimbrado()
    {
        if(request()->ajax())
        {
            $timbrado = VoucherBox::find(request()->expedicion);
            $stampeds = Stamped::where('id',$timbrado->stamped_id)->first();
            $number = Voucher::where('voucher_box_id',$timbrado->id)->where('voucher_type',request()->voucher_type)->orderBy('id','desc')->first();

            $results['numero'] = $number ? str_pad(($number->voucher_number + 1), 7, "0", STR_PAD_LEFT) : str_pad(1, 7, "0", STR_PAD_LEFT);
            $results['timbrado'] = $stampeds->number;
            $results['vig_timbrado'] = $stampeds->until_date->format('d/m/Y');
            $results['id_timbrado'] = $stampeds->id;
            return response()->json($results);

        }
    }

    public function facturasCliente()
    {
        $facturas = Voucher::where('client_id', request()->client_id)
        ->where('voucher_fullnumber', request()->q)
        ->where('status', 1)
        ->get();
        foreach ($facturas as $key => $factura)
        {
            $results['items'][$key]['id']        = $factura->id;
            $results['items'][$key]['text']      = $factura->voucher_fullnumber;
            $results['items'][$key]['total']     = $factura->amount;
            $results['items'][$key]['date']      = $factura->date->format('d/m/Y');
            $results['items'][$key]['condition'] = config('constants.invoice_condition.' . $factura->voucher_condition);

            foreach ($factura->voucher_details as $key2 => $detail_products)
            {
                $results['items'][$key]['products'][$key2]['id']              = $detail_products->articulo_id;
                $results['items'][$key]['products'][$key2]['name']            = $detail_products->articulo->name;
                $results['items'][$key]['products'][$key2]['quantity']        = number_format($detail_products->quantity, 0, ',', '.');
                $results['items'][$key]['products'][$key2]['amount']          = number_format($detail_products->amount, 0, ',', '.');
                $results['items'][$key]['products'][$key2]['subtotal']        = intVal($detail_products->amount * $detail_products->quantity);
                $results['items'][$key]['products'][$key2]['excenta']         = number_format($detail_products->excenta, 0, ',', '.');
                $results['items'][$key]['products'][$key2]['iva5']            = number_format($detail_products->iva5, 0, ',', '.');
                $results['items'][$key]['products'][$key2]['iva10']           = number_format($detail_products->iva10, 0, ',', '.');
            }
        }
        return response()->json($results);
    }
}
