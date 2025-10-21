<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductionOrderRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\BudgetProduction;
use App\Models\BudgetProductionDetail;
use App\Models\Deposit;
use App\Models\Presentation;
use App\Models\ProductionCost;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\Provider;
use App\Models\PurchaseBudget;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\PurchasesExistence;
use App\Models\SettingProduct;
use App\Models\TeamWork;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductionOrderController extends Controller
{
    public function index()
    {
        $purchases_providers = Provider::Filter();

        $order = ProductionOrder::with('branch', 'client')
            ->leftJoin('production_controls', 'production_orders.id', '=', 'production_controls.production_order_id')
            ->leftJoin('production_quality_controls', 'production_controls.id', '=', 'production_quality_controls.production_control_id')
            ->leftJoin('production_rejected as control', function ($join) {
                $join->on('production_controls.id', '=', 'control.owner_id')
                    ->whereRaw("LOWER(control.owner_type) LIKE '%productioncontrol'");
            })
            ->leftJoin('production_rejected as calidad', function ($join) {
                $join->on('production_quality_controls.id', '=', 'calidad.owner_id')
                    ->whereRaw("LOWER(calidad.owner_type) LIKE '%productionqualitycontrol'");
            })
            ->select(
                'production_orders.*',
                DB::raw("MAX(CASE WHEN control.id IS NOT NULL THEN 1 ELSE 0 END) AS tiene_control_rechazo"),
                DB::raw("MAX(CASE WHEN calidad.id IS NOT NULL THEN 1 ELSE 0 END) AS tiene_calidad_rechazo")
            )
            ->groupBy('production_orders.id')
            ->orderBy('production_orders.id', 'desc');

        if (request()->o) {
            $order->where(function($q) {
                $q->where('ruc', 'LIKE', '%' . request()->o . '%')
                ->orWhere('number', 'LIKE', '%' . request()->o . '%');
            });
        }

        $order = $order->paginate(20);

        return view('pages.production-order.index', compact('order', 'purchases_providers'));
    }

    public function create()
    {
        $users                  = User::filter();
        $branches               = Branch::where('status', true)->pluck('name', 'id');
        $articulos               = Articulo::Filter();
        $product_presentations  = Presentation::Filter();
        $team_works             = TeamWork::filter();
        $provider_suggesteds    = NULL;
        return view('pages.production-order.create', compact('users' , 'branches', 'articulos', 'product_presentations','provider_suggesteds','team_works'));
    }

    public function store(CreateProductionOrderRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, & $production_order)
            {
                $production_order = ProductionOrder::create([
                    'date'                  => $request->date,
                    'status'                => 1,
                    'client_id'             => $request->client_id,
                    'team_work_id'          => $request->team_work_id,
                    'branch_id'             => $request->branch_id,
                    'user_id'               => auth()->user()->id,
                    'budget_production_id'  => $request->number_budget
                ]);

                $budget = BudgetProduction::find($request->number_budget);
                $budget->update([
                    'status' => 3
                ]);

                // Grabar los Productos
                foreach($request->detail_product_id as $key => $value)
                {
                    foreach ($request->{"selected_materials_$value"} as $key1 => $value1)
                    {
                        $production_order->production_order_details()->create([
                            'material_id'               => $value1,
                            'articulo_id'               => $value,
                            'quantity_material'         => $request->{"selected_materials_quantity_$value"}[$key1],
                            'quantity'                  => $request->detail_product_quantity[$key],
                            'production_order_id'       => $production_order->id,
                            'status'                    => 1
                        ]);
                        $deposit = Deposit::where('branch_id',$request->branch_id)->first();
                        $product_existences = PurchasesExistence::where('residue', '>', 0)
                                                                ->where('deposit_id', $deposit->id)
                                                                ->where('raw_material_id', $value1)
                                                                ->orderBy('id')
                                                                ->first();

                        $product_existences->update(['residue' => $product_existences->residue - $request->{"selected_materials_quantity_$value"}[$key1]]);

                    }

                    $cost_product = ProductionCost::where('order_production_id',$production_order->id)->first();
                    if(!$cost_product)
                    {
                        $cost_product = ProductionCost::create([
                            'date'                  => $request->date,
                            'status'                => 1,
                            'branch_id'             => $request->branch_id,
                            'user_id'               => auth()->user()->id,
                            'order_production_id'   => $production_order->id
                        ]);
                    }

                    $materials = SettingProduct::where('articulo_id',$value)->whereNotNull('raw_materials_id')->get();
                    $cantidadOrden = $request->detail_product_quantity[$key];
                    foreach ($materials as $key => $material)
                    {
                        $cost_product->production_cost_detail()->create([
                            'articulo_id'           => $value,
                            'material_id'           => $material->raw_material->id,
                            'quantity'              => $material->quantity * $cantidadOrden,
                            'production_cost_id'    => $cost_product->id,
                            'price_cost'            => $cantidadOrden * $material->raw_material->average_cost
                        ]);
                    }
                }
            });

            return response()->json([
                'success'            => true,
            ]);
        }
        abort(404);
    }

    public function show(ProductionOrder $production_order)
    {
        return view('pages.production-order.show', compact('production_order'));
    }

    public function charge_purchase_budgets(PurchaseOrder $wish_purchase)
    {
        return view('pages.wish-purchase.purchase_budgets',compact('wish_purchase'));
    }

    public function ajax_order_production()
    {
        if(request()->ajax())
        {
            $results = [];
            $order_productions = BudgetProductionDetail::with('budget_production', 'articulo')
                                                            ->select("budget_production_details.*")
                                                            ->join('budget_productions', 'budget_production_details.budget_production_id', '=', 'budget_productions.id')
                                                            ->where('budget_productions.status', 2)
                                                            ->where('budget_productions.id', request()->number_budget)
                                                            ->get();
            foreach ($order_productions as $key => $order_detail)
            {
                $results['items'][$key]['id']           = $order_detail->id;
                $results['items'][$key]['product_id']   = $order_detail->articulo_id;
                $results['items'][$key]['product_name'] = $order_detail->articulo->name;
                $results['items'][$key]['quantity']     = $order_detail->quantity;
                $results['items'][$key]['client_id']    = $order_detail->budget_production->client_id;
                $results['items'][$key]['client']       = $order_detail->budget_production->client->first_name.' '.$order_detail->budget_production->client->last_name;
                $results['items'][$key]['branch_id']    = $order_detail->budget_production->branch_id;
                $results['items'][$key]['branch']       = $order_detail->budget_production->branch->name;
                $results['items'][$key]['date']         = $order_detail->budget_production->date->format('d/m/Y');
                $results['branch_id']           = $order_detail->budget_production->branch_id;
            }
            return response()->json($results);
        }
        abort(404);
    }

    public function ajax_modal_material()
    {
        if(request()->ajax())
        {
            $results = [];
            $articulo = Articulo::where('id',request()->product_id)->first();
            $order = BudgetProductionDetail::where('budget_production_id',request()->number_budget)->where('articulo_id',request()->product_id)->first();
            foreach ($articulo->setting_product as $key => $setting)
            {
                if($setting->raw_materials_id)
                {
                    $results['items'][$key]['id']           = $setting->id;
                    $results['items'][$key]['articulo_id']   = $setting->articulo_id;
                    $results['items'][$key]['articulo_name'] = $setting->articulo->name;
                    $results['items'][$key]['raw_material_id']     = $setting->raw_materials_id;
                    $results['items'][$key]['raw_material']     = $setting->raw_material->description;
                    $results['items'][$key]['quantity']        = $setting->quantity * $order->quantity;
                }
            }
            return response()->json($results);

        }
        abort(404);
    }
    public function ajaxByClient($client_id)
    {
        $orders = ProductionOrder::where('client_id', $client_id)->whereIn('status', [3,4])->get();
        $results = [];
        foreach ($orders as $key => $order)
        {
            $results[$key]['id'] = $order->id;
            $results[$key]['date'] = $order->date;
            $results[$key]['branch'] = $order->branch->name;
        }
        return response()->json($results);
    }

    public function ajaxDetalle($id)
    {
        $orders = ProductionOrderDetail::where('production_order_id', $id)->groupBy('articulo_id')->get();

        $results = [];
        foreach ($orders as $key => $detail)
        {
            $orden = ProductionOrder::where('id', $detail->production_order_id)->first();
            $budget = BudgetProductionDetail::where('budget_production_id', $orden->budget_production_id)->where('articulo_id', $detail->articulo_id)->first();
            $results[$key]['id'] = $detail->id;
            $results[$key]['articulo_id'] = $detail->articulo->id;
            $results[$key]['articulo'] = $detail->articulo->name;
            $results[$key]['quantity'] = $detail->quantity;
            $results[$key]['precio'] = $budget->amount;
            $results[$key]['total_precio'] = $detail->quantity * $budget->amount;
        }
        return response()->json($results);
    }

    public function reWork(ProductionOrder $production_order)
    {
        DB::transaction(function () use ($production_order)
        {

            $new_order = ProductionOrder::create([
                'date'                  => now()->format('d/m/Y'),
                'status'                => 1,
                'client_id'             => $production_order->client_id,
                'team_work_id'          => $production_order->team_work_id,
                'branch_id'             => $production_order->branch_id, // corregido
                'user_id'               => auth()->user()->id,
                'budget_production_id'  => $production_order->budget_production_id,
                'old_order_id'          => $production_order->id
            ]);

            $rejectedControl = DB::table('production_rejected')
                ->join('production_controls', 'production_rejected.owner_id', '=', 'production_controls.id')
                ->where('production_controls.production_order_id', $production_order->id)
                ->whereRaw("LOWER(production_rejected.owner_type) LIKE '%productioncontrol'")
                ->select('production_rejected.articulo_id', 'production_rejected.quantity')
                ->get();

            $rejectedCalidad = DB::table('production_rejected')
                ->join('production_quality_controls', 'production_rejected.owner_id', '=', 'production_quality_controls.id')
                ->join('production_controls', 'production_quality_controls.production_control_id', '=', 'production_controls.id')
                ->where('production_controls.production_order_id', $production_order->id)
                ->whereRaw("LOWER(production_rejected.owner_type) LIKE '%productionqualitycontrol'")
                ->select('production_rejected.articulo_id', 'production_rejected.quantity')
                ->get();

            $rechazos = $rejectedControl->concat($rejectedCalidad);

            $agrupados = $rechazos->groupBy('articulo_id')->map(function ($items) {
                return [
                    'articulo_id' => $items->first()->articulo_id,
                    'quantity' => $items->sum('quantity')
                ];
            });

            foreach ($agrupados as $detalle)
            {
                $articulo = Articulo::where('id',$detalle['articulo_id'])->first();
                foreach ($articulo->setting_product as $key => $setting)
                {
                    if($setting->raw_materials_id)
                    {
                        $new_order->production_order_details()->create([
                            'articulo_id'         => $detalle['articulo_id'],
                            'material_id'         => $setting->raw_materials_id,
                            'quantity'            => $detalle['quantity'],
                            'quantity_material'   => $setting->quantity * $detalle['quantity'],
                            'material_id'         => $setting->raw_materials_id,
                            'status'              => 1
                        ]);

                        $deposit = Deposit::where('branch_id',$production_order->branch_id)->first();
                        $product_existences = PurchasesExistence::where('residue', '>', 0)
                                                                ->where('deposit_id', $deposit->id)
                                                                ->where('raw_material_id', $setting->raw_materials_id)
                                                                ->orderBy('id')
                                                                ->first();

                        $product_existences->update(['residue' => $product_existences->residue - $setting->quantity * $detalle['quantity']]);
                    }
                }
                $cost_product = ProductionCost::where('order_production_id',$new_order->id)->first();
                if(!$cost_product)
                {
                    $cost_product = ProductionCost::create([
                        'date'                  => now()->format('d/m/Y'),
                        'status'                => 1,
                        'branch_id'             => $new_order->branch_id,
                        'user_id'               => auth()->user()->id,
                        'order_production_id'   => $new_order->id
                    ]);
                }

                $materials = SettingProduct::where('articulo_id',$detalle['articulo_id'])->whereNotNull('raw_materials_id')->get();
                $cantidadOrden = $detalle['quantity'];
                foreach ($materials as $key => $material)
                {
                    $cost_product->production_cost_detail()->create([
                        'articulo_id'           => $detalle['articulo_id'],
                        'material_id'           => $material->raw_material->id,
                        'quantity'              => $material->quantity * $cantidadOrden,
                        'production_cost_id'    => $cost_product->id,
                        'price_cost'            => $cantidadOrden * $material->raw_material->average_cost
                    ]);
                }
            }
            $production_order->update([
                'status' => 4
            ]);
        });

        return redirect()->back()->with('success', 'Nueva orden generada correctamente a partir de los rechazos.');
    }

}
