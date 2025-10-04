<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Branch;
use App\Models\CashBoxUser;
use App\Models\Stamped;
use App\Models\Voucher;
use App\Models\VoucherBox;
use Illuminate\Http\Request;

class VouchersController extends Controller
{
    public function index()
    {
        return view('pages.vouchers.index');
    }

    public function create(Request $request)
    {
        $branches = Branch::where('status',1)->pluck('name', 'id');
        $articulos = Articulo::where('status',1)->pluck('name', 'id');
        return view('pages.vouchers.create', compact('branches','articulos'));
    }

    public function store(Request $request)
    {
        Voucher::create([
            'date' => $request->date,
            'branch_id' => $request->branch_id,
            'voucher_box_id' => $request->voucher_box_id,
            'voucher_condition' => $request->voucher_condition,
            'voucher_number' => $request->voucher_number,
            'expiration' => $request->expiration,
            'client_id' => $request->client_id,
            'razon_social' => $request->razon_social,
            'ruc' => $request->ruc,
            'phone' => $request->phone,
            'address'   => $request->address,
            'voucher_type' => $request->voucher_type,
            'observation'   => $request->observation,
            'amount' => $request->amount,
            'total_excenta' => $request->total_excenta,
            'total_iva5'    => $request->total_iva5,
            'total_iva10'  => $request->total_iva10,
            'amount_iva5'  => $request->amount_iva5,
            'amount_iva10' => $request->amount_iva10,
            'status' => 1,
            'user_id' => auth()->user()->id,
            'voucher_fullnumber' => $request->voucher_number,
            'stamped_id' => $request->stamped_id,
        ]);
        return redirect()->route('voucher')->with('success', 'Comprobante registrado correctamente');
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
            $number = Voucher::where('voucher_box_id',$timbrado->id)->orderBy('id','desc')->first();

            $results['numero'] = $number ? str_pad(($number->voucher_number + 1), 7, "0", STR_PAD_LEFT) : str_pad(1, 7, "0", STR_PAD_LEFT);
            $results['timbrado'] = $stampeds->number;
            $results['vig_timbrado'] = $stampeds->until_date->format('d/m/Y');
            return response()->json($results);

        }
    }
}
