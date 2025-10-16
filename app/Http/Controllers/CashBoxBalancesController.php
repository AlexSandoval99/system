<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CashBox;
use App\Models\CashBoxDetail;
use App\Models\CashBoxUser;
use App\Models\CashCount;
use App\Models\CollectionDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBoxBalancesController extends Controller
{
    public function index()
    {
        $cash_box_balances = CashBoxDetail::with('user', 'cash_box', 'cash_count')
                                            ->where('cash_box_details.cash_box_concept_id', 1)
                                            ->select('cash_box_details.*', 'cash_count.id as arqueo_id')
                                            ->join('cash_boxes',     'cash_box_details.cash_box_id', '=', 'cash_boxes.id')
                                            ->join('cash_box_users', 'cash_boxes.id' , '=', 'cash_box_users.cash_box_id')
                                            ->leftJoin('cash_count', 'cash_box_details.id', '=', 'cash_count.cash_box_detail_id')
                                            ->where('cash_box_users.user_id', auth()->user()->id)
                                            ->where('cash_box_users.status', true)
                                            ->groupBy('cash_box_details.id')
                                            ->orderBy('id', 'DESC');

        $cash_box_balances = $cash_box_balances->paginate(30);

        $banks = Bank::where('status',1)->pluck('name', 'id');

        return view('pages.cash_box_balances.index', compact('cash_box_balances','banks'));
    }

    public function create()
    {
        $cash_boxes = CashBoxUser::Filter()->pluck('cash_boxes.name AS name', 'cash_box_users.cash_box_id AS id');
        return view('pages.cash_box_balances.create', compact('cash_boxes'));
    }

    public function store(Request $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request)
            {
                $date = CashBoxDetail::where(['status'      => true,
                                              'cash_box_id' => $request->cash_box_id])
                                              ->orderBy('created_at', 'DESC')
                                              ->first();
                $cash_box_residue = 0;
                if($date)
                {
                    $cash_box = CashBox::find(request()->cash_box_id);

                    $cash_box_details = CashBoxDetail::where([ 'status'      => true,
                        'cash_box_id' => $request->cash_box_id])
                        ->whereDate('created_at', $date->created_at->format('Y-m-d'))
                        ->get();

                        foreach($cash_box_details AS $cash_box_detail)
                        {
                            if($cash_box_detail->amount)
                            {
                                //SI ES INGRESO
                                if($cash_box_detail->type == 1)
                                {
                                    $cash_box_residue += $cash_box_detail->amount;
                                }
                                else//SI ES EGRESO
                                {
                                    $cash_box_residue -= $cash_box_detail->amount;
                                }
                            }
                        }
                }

                CashBoxDetail::create([
                    'cash_box_id'          => $request->cash_box_id,
                    'amount'               => intVal($cash_box_residue),
                    'observation'          => $request->observation,
                    'cash_box_concept_id'  => 1,
                    'type'                 => 1,
                    'user_id'              => auth()->user()->id,
                    'status'               => true
                ]);

                toastr()->success('Agregado exitosamente');
            });

            return response()->json([ 'success' => true ]);
        }
        abort(404);
    }

    public function close($id)
    {
        $caja = CashBoxDetail::findOrFail($id);

        // Validar que esté abierta
        if ($caja->status != 1)
        {
            return redirect()->back()->with('error', 'La caja ya está cerrada.');
        }

        $caja->update([
            'status' => 2,
            'closed_at' => now(),
            'closed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Caja cerrada correctamente.');
    }

    public function ajax_last_cash_balance()
    {
        if(request()->ajax())
        {
            $results          = [];
            $count            = 0;
            $cash_box_residue = 0;
            $cash_box_entry   = 0;
            $cash_box_egress  = 0;
            $date = CashBoxDetail::where(['status'      => true,
                                          'cash_box_id' => request()->cash_box_id])
                                          ->orderBy('created_at', 'DESC')
                                          ->first();
            if($date)
            {
                $cash_box_details = CashBoxDetail::where([ 'status'      => true,
                                                           'cash_box_id' => request()->cash_box_id])
                                                ->whereDate('created_at', $date->created_at->format('Y-m-d'))
                                                ->get();

                $small_cash_box_detail = CashBoxDetail::with('cash_box_concept', 'voucher', 'payment', 'user')
                ->where('cash_box_id',request()->cash_box_id)
                ->where('status',1)
                ->get();

                foreach($cash_box_details AS $cash_box_detail)
                {
                    if($cash_box_detail->amount)
                    {
                        if($cash_box_detail->type == 1)
                        {
                            $cash_box_residue = $cash_box_residue + $cash_box_detail->amount;
                            $cash_box_entry   = $cash_box_entry   + $cash_box_detail->amount;
                        }else
                        {
                            $cash_box_residue = $cash_box_residue - $cash_box_detail->amount;
                            $cash_box_egress  = $cash_box_egress  + $cash_box_detail->amount;
                        }
                    }

                }

                $results['date'] = $date->created_at->format('d/m/Y');
                $count++;
            }

            $results['count']   = $count;
            $results['residue'] = $cash_box_residue;
            $results['entry']   = $cash_box_entry;
            $results['egress']  = $cash_box_egress;

            return response()->json($results);
        }
        abort(404);
    }

    public function storeArqueo(Request $request, $id)
    {
        if ($request->ajax()) {
            DB::transaction(function () use ($request, $id) {
                foreach($request->cant_denominacion as $key => $cant)
                {
                    CashCount::create([
                        'cash_box_detail_id' => $request->cash_box_id,
                        'denominacion'        => $key + 1,
                        'quantity'            => $cant,
                    ]);
                }
                CashCount::create([
                    'cash_box_detail_id' => $id,
                    'denominacion'        => 8,
                    'quantity'            => $request->tarjetaMonto,
                ]);

                CashCount::create([
                    'cash_box_detail_id' => $id,
                    'denominacion'        => 9,
                    'quantity'            => $request->chequesMonto,
                ]);

            });

            return response()->json(['success' => true, 'message' => 'Arqueo guardado correctamente.']);
        }
        abort(404);
    }

    public function storeDeposito(Request $request, $id)
    {
        if ($request->ajax())
        {
            DB::transaction(function () use ($request, $id)
            {
                $cash_box_detail = CashBoxDetail::find($id);
                $bank = Bank::find($request->bank_id);
                CollectionDeposit::create([
                    'collection_date' => now(),
                    'amount_cash'     => $request->montoEfectivo,
                    'amount_check'   =>  $request->montoCheque,
                    'bank_id'        => $bank->id,
                    'account_number' => $bank->account_number,
                    'user_id'        => auth()->user()->id,
                    'cash_box_id'   => $cash_box_detail->cash_box_id,
                    'cash_box_detail_id' => $cash_box_detail->id,
                    'observations'   => $request->observacion ?? null,
                    'status'        => 1,
                ]);

                CashBoxDetail::create([
                    'cash_box_id'          => $cash_box_detail->cash_box_id,
                    'amount'               => $request->montoEfectivo + $request->montoCheque,
                    'observation'          => 'Depósito a banco: ' . $bank->name,
                    'cash_box_concept_id'  => 4, // Concepto de depósito
                    'type'                 => 2, // Egreso
                    'user_id'              => auth()->user()->id,
                    'status'               => true
                ]);
            });

            return response()->json(['success' => true, 'message' => 'Depósito guardado correctamente.']);
        }
        abort(404);
    }

    public function movements()
    {
        $cash_boxes = CashBoxUser::where('status', true)
            ->with('cashBox')
            ->where('user_id', auth()->id())
            ->get()
            ->pluck('cashBox.name', 'cashBox.id');

        return view('pages.cash_box_balances.movements', compact('cash_boxes'));
    }

    public function filterMovements(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');
        $cashBoxId = $request->input('cash_box_id');

        $query = CashBoxDetail::with('user', 'cash_box', 'cash_box_concept')
            ->whereBetween(DB::raw('DATE(cash_box_details.created_at)'), [$from, $to])
            ->orderBy('created_at', 'asc');

        if ($cashBoxId) {
            $query->where('cash_box_id', $cashBoxId);
        }

        $movements = $query->get();

        // Calcular totales
        $totalIngresos = $movements->where('type', 1)->sum('amount');
        $totalEgresos  = $movements->where('type', 2)->sum('amount');
        $saldoFinal    = $totalIngresos - $totalEgresos;

        return response()->json([
            'movements' => $movements,
            'totals' => [
                'ingresos' => $totalIngresos,
                'egresos' => $totalEgresos,
                'saldo' => $saldoFinal
            ]
        ]);
    }
}
