<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBudgetProductionRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\BudgetProduction;
use App\Models\BudgetProductionDetail;
use App\Models\WishSale;
use App\Models\WishSaleDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BudgetProductionController extends Controller
{
    public function index()
    {
        $budget_productions = BudgetProduction::with('branch')
                                                ->orderBy('id', 'desc');
        $branches = Branch::where('status',1)->pluck('name','id');
        if(request()->s)
        {
            $budget_productions = $budget_productions->where('id', request()->s);
        }

        if (request()->wish_production_number)
        {
            $budget_productions = $budget_productions->whereHas('budget_production_details', function($query){
                $query->where('wish_sale_id', request()->wish_production_number);
            });
        }

        if(request()->branch_id)
        {
            $budget_productions = $budget_productions->where('branch_id', request()->branch_id);
        }
        $budget_productions = $budget_productions->paginate(20);
        return view('pages.budget-production.index', compact('budget_productions','branches'));
    }

    public function create()
    {
        $branches       = Branch::getAllCached()->pluck('name', 'id');
        $articulos       = Articulo::Filter();

        return view('pages.budget-production.create', compact('branches','articulos'));
    }

    public function store(CreateBudgetProductionRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request)
            {
                $budget_production = BudgetProduction::create([  'client_id'          => $request->client_id,
                                                                 'total_amount'       => $request->total_amount,
                                                                 'status'             => true,
                                                                 'date'               => $request->date,
                                                                 'branch_id'          => $request->branch_id,
                                                                 'user_id'            => auth()->user()->id,
                                                                 'wish_sale_id'       => $request->wish_sale_id,

                                                                ]);
                foreach($request->detail_product_id as $key => $product_id)
                {
                    $budget_proction_details        = $budget_production->budget_production_details()->create([
                            'quantity'              => $request->quantity_product[$key],
                            'amount'                => str_replace('.', '', $request->detail_product_amount[$key]),
                            'budget_production_id'  => $budget_production->id,
    						'articulo_id'           => $product_id
                    ]);
                }
                if($request->wish_sale_id)
                {
                    $wish_sale = WishSale::findOrFail($request->wish_sale_id);
                    $wish_sale->update([
                        'status' => 2
                    ]);
                }

                toastr()->success('Agregado exitosamente');
            });

            return response()->json([
                'success' => true
            ]);
        }
        abort(404);
    }

    public function edit(BudgetProduction $budget_production)
    {
        $articulos       = Articulo::Filter();

        return view('pages.budget-production.edit',compact('budget_production','articulos'));
    }

    public function update(CreateBudgetProductionRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $budget_production = BudgetProduction::findOrFail($request->id_presupuesto);
            $budget_production->budget_production_details()->delete();

            foreach ($request->detail_product_id as $key => $product_id)
            {
                $budget_production->budget_production_details()->create([
                    'articulo_id' => $product_id,
                    'quantity'    => str_replace('.', '', $request->quantity_product[$key]),
                    'amount'      => str_replace('.', '', $request->detail_product_amount[$key]),
                ]);
            }
        });

        toastr()->success('Presupuesto de producción actualizado exitosamente');
        return redirect()->route('budget-production');

    }

    public function show(BudgetProduction $budget_production)
    {

        return view('pages.budget-production.show', compact('budget_production'));
    }

    public function ajax_budget_production()
    {
        if(request()->ajax())
        {
            $results = [];
            $wish_productions = WishSaleDetail::with('wish_sales', 'articulo')
                                                            ->select("wish_sale_details.*")
                                                            ->join('wish_sales', 'wish_sale_details.wish_sale_id', '=', 'wish_sales.id')
                                                            ->where('wish_sales.status', true)
                                                            ->where('wish_sales.id', request()->number_ped)
                                                            ->get();
            foreach ($wish_productions as $key => $order_detail)
            {
                $results['items'][$key]['id']                           = $order_detail->id;
                $results['items'][$key]['product_id']                   = $order_detail->articulo_id;
                $results['items'][$key]['product_name']                 = $order_detail->articulo->name;
                $results['items'][$key]['quantity']                     = $order_detail->quantity;
                $results['items'][$key]['amount']                       = $order_detail->articulo->price;
                $results['items'][$key]['subtotal']                     = $order_detail->articulo->price * $order_detail->quantity;
                $results['items'][$key]['client_id']                    = $order_detail->wish_sales->client_id;
                $results['items'][$key]['client']                       = $order_detail->wish_sales->client->first_name.' '.$order_detail->wish_sales->client->last_name;
                $results['items'][$key]['branch_id']                    = $order_detail->wish_sales->branch_id;
                $results['items'][$key]['branch']                       = $order_detail->wish_sales->branch->name;
                $results['items'][$key]['date']                         = Carbon::createFromFormat('Y-m-d',$order_detail->wish_sales->date)->format('d/m/Y');
                $results['items'][$key]['wish_sale_id']                 = $order_detail->wish_sales->id;
                $results['branch_id']                   = $order_detail->wish_sales->branch_id;
            }
            return response()->json($results);
        }
        abort(404);
    }

    private function parse($value)
    {
        return intVal(str_replace(',', '.',str_replace('.', '', $value)));
    }

    private function array_sum($array)
    {
        $total = 0;
        foreach ($array as $key => $value)
        {
            $total += intVal(str_replace(',', '.',str_replace('.', '', $value)));
        }
        return $total;
    }

    public function confirm_budget_production(BudgetProduction $budget_production)
    {
        $budget_production->update([
            'status' => 2
        ]);

        toastr()->success('Presupuesto de producción confirmado exitosamente');
        return redirect()->back();
    }

    public function delete(BudgetProduction $budget_production)
    {
        $budget_production->update([
            'status' => 0
        ]);
        toastr()->success('Presupuesto de producción anulado exitosamente');
        return redirect()->back();
    }

}
