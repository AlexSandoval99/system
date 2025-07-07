<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCashBoxConceptsRequest;
use App\Models\CashBox;
use App\Models\CashBoxDetail;
use App\Models\CashBoxUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBoxBalancesController extends Controller
{
    public function index()
    {
        $cash_box_balances = CashBoxDetail::with('user', 'cash_box')
                                            ->where('cash_box_details.cash_box_concept_id', 1)
                                            ->select("cash_box_details.*")
                                            ->join('cash_boxes',     'cash_box_details.cash_box_id', '=', 'cash_boxes.id')
                                            ->join('cash_box_users', 'cash_boxes.id' , '=', 'cash_box_users.cash_box_id')
                                            ->where('cash_box_users.user_id', auth()->user()->id)
                                            ->where('cash_box_users.status', true)
                                            ->orderBy('id', 'DESC');
        $cash_box_balances = $cash_box_balances->paginate(30);

        return view('pages.cash_box_balances.index', compact('cash_box_balances'));
    }

    public function create()
    {
        $cash_boxes = CashBoxUser::Filter()
                                    ->pluck('cash_boxes.name AS name', 'cash_box_users.cash_box_id AS id');
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

                    // if($cash_box->small_box == 0)
                    // {

                    $cash_box_details = CashBoxDetail::where([ 'status'      => true,
                        'cash_box_id' => $request->cash_box_id,
                        'currency_id' => $request->currency_id,
                        'fund'        => 1 ])
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
                    // }
                    // elseif($cash_box->small_box == 1)
                    // {
                    //     $cash_box_residue = $request->amount;
                    // }

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

    public function show(CashBoxConcept $cash_box_concept)
    {
        return redirect('cash_box_balances');
    }

    public function edit(CashBoxConcept $cash_box_concept)
    {
        return view('pages.cash_box_balances.edit', compact('cash_box_concept'));
    }

    public function update(UpdateCashBoxConceptsRequest $request, CashBoxConcept $cash_box_concept)
    {
        $cash_box_concept->update([
            'name'   => $request->name,
            'type'   => $request->type,
            'status' => $request->status
        ]);

        toastr()->success('Editado exitosamente');

        return redirect('cash_box_balances');
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
}
