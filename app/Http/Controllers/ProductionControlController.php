<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductionControlRequest;
use App\Http\Requests\CreateProductionOrderRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\BudgetProductionDetail;
use App\Models\Client;
use App\Models\Presentation;
use App\Models\ProductionControl;
use App\Models\ProductionCost;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\ProductionRejected;
use App\Models\Provider;
use App\Models\PurchaseBudget;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\SettingProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductionControlController extends Controller
{
    public function index()
    {
        $clients = Client::Filter();
        $order           = ProductionControl::with('branch')
            ->orderBy('id', 'desc');

         $order = $order->paginate(20);
         return view('pages.production-control.index', compact('order', 'clients'));
    }

    public function create()
    {
        $users                  = User::filter();
        $branches               = Branch::where('status', true)->pluck('name', 'id');
        $articulos               = Articulo::Filter();
        $product_presentations  = Presentation::Filter();
        $provider_suggesteds    = NULL;
        return view('pages.production-control.create', compact('users' , 'branches', 'articulos', 'product_presentations','provider_suggesteds'));
    }

    public function store(CreateProductionControlRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, & $control)
            {
                $control = ProductionControl::create([
                    'production_order_id'       => $request->number_order,
                    'date'                      => $request->date,
                    'status'                    => 1,
                    'client_id'                 => $request->client_id,
                    'branch_id'                 => $request->branch_id,
                    'user_id'                   => auth()->user()->id,
                ]);
                $orden = ProductionOrder::find($request->number_order)->update([
                    'status'    => 2
                ]);

                $cost_product = ProductionCost::where('order_production_id',$request->number_order)->first();

                if ($cost_product)
                {
                    $basicCosts = [
                        ['id' => 8,'descripcion' => 'Luz eléctrica', 'monto' => (50000 / 30)],
                        ['id' => 9,'descripcion' => 'Agua corriente', 'monto' => (30000 / 30)],
                        ['id' => 10,'descripcion' => 'Inmueble (alquiler)', 'monto' => (100000 / 30)],
                    ];

                    foreach ($basicCosts as $key => $cost) {
                        $cost_product->production_cost_detail()->create([
                            'articulo_id'         => $cost['id'],
                            'material_id'         => null,
                            'quantity'            => 1,
                            'production_cost_id'  => $cost_product->id,
                            'hour_worker'         => 0,
                            'price_cost'          => $cost['monto'],
                            'observation'         => $cost['descripcion'],
                        ]);
                    }
                }
                // Grabar los Productos
                foreach($request->detail_stage_id as $key => $value)
                {
                    $articulo = explode("_", strval($value));
                    $control->production_control_details()->create([
                        'articulo_id'           => $articulo[0],
                        'quantity'              => $request->{"total$value"} ?? 0,
                        'residue'               => $request->{"cantidad_controlada$value"} ?? 0,
                        'observation'           => $request->{"observacion$value"} ?? '',
                        'production_control_id' => $control->id,
                        'start_date'            => $request->{"fecha_inicio$value"} ?? null,
                        'end_date'              => $request->{"fecha_fin$value"} ?? null,
                        'stage_id'              => $request->{"stage_id$value"}
                    ]);

                    $cost_product = ProductionCost::where('order_production_id',$request->number_order)->first();
                    if($cost_product)
                    {
                        $startHour = Carbon::createFromFormat('Y-m-d\TH:i', $request->{"fecha_inicio$value"});
                        $endHour = Carbon::createFromFormat('Y-m-d\TH:i', $request->{"fecha_fin$value"});

                        $diffInMinutes = $startHour->diffInMinutes($endHour);
                        $diffInHours = $diffInMinutes / 60;

                        $cost_product->production_cost_detail()->create([
                            'articulo_id'           => $articulo[0],
                            'material_id'           => null,
                            'quantity'              => $request->{"total$value"},
                            'production_cost_id'    => $cost_product->id,
                            'hour_worker'          => $diffInHours,
                            'price_cost'            => $diffInHours * 15000
                        ]);
                    }

                    // $array_products = $control->production_control_details()->orderBy('id','desc')->groupBy('articulo_id')->get();
                    // foreach ($array_products as $key => $product)
                    // {
                    //     if($request->input("total{$product->articulo_id}_{$product->quality_id}") > $request->input("cantidad_controlada{$product->articulo_id}_{$product->quality_id}"))
                    //     {
                    //         $losse = Losse::where('control_quality_id',$control->id)->first();
                    //         if(!$losse)
                    //         {
                    //             $losse = Losse::create([
                    //                 'status'            => 1,
                    //                 'date'               => $request->date,
                    //                 'user_id'            => auth()->user()->id,
                    //                 'branch_id'          => $request->branch_id,
                    //                 'control_quality_id' => $control->id
                    //             ]);
                    //         }

                    //         $materials = SettingProduct::where('articulo_id',$product->articulo_id)->whereNotNull('raw_materials_id')->get();
                    //         foreach ($materials as $key => $material)
                    //         {
                    //             $losse->losse_detail()->create([
                    //                 'articulo_id'   => $product->articulo_id,
                    //                 'reason'        => $request->input("observacion{$product->articulo_id}_{$product->quality_id}"),
                    //                 'material_id'   => $material->raw_material->id,
                    //                 'quantity'      => $request->input("total{$product->articulo_id}_{$product->quality_id}")  - $request->input("cantidad_controlada{$product->articulo_id}_{$product->quality_id}"),
                    //                 'losse_id'      => $losse->id
                    //             ]);
                    //         }
                    //     }
                    // }

                    if($request->{"total$value"} > $request->{"cantidad_controlada$value"})
                    {
                        $existe = ProductionRejected::where('articulo_id',$articulo[0])->where('owner_id',$control->id)->first();
                        if($existe)
                        {
                            $existe->update([
                                'quantity' => $request->{"total$value"} - $request->{"cantidad_controlada$value"}
                            ]);
                        }
                        else
                        {
                            ProductionRejected::create([
                                'articulo_id'   => $articulo[0],
                                'quantity'      => $request->{"total$value"} - $request->{"cantidad_controlada$value"},
                                'owner_id'      => $control->id,
                                'owner_type'    => "app\Models\ProductionControl",
                            ]);
                        }
                    }

                }
            });

            return response()->json([
                'success'            => true,
            ]);
        }
        abort(404);
    }

    public function show(ProductionControl $control)
    {

        return view('pages.production-control.show', compact('control'));
    }


    public function ajax_control_production()
    {
        if(request()->ajax())
        {
            $results = [];
            foreach (request()->sesion as $key => $session) {
                $order_productions = ProductionOrderDetail::with('production_order', 'articulo')
                                                                ->select("production_order_details.*")
                                                                ->join('production_orders', 'production_order_details.production_order_id', '=', 'production_orders.id')
                                                                ->where('production_orders.status', true)
                                                                ->where('production_orders.id', request()->number_order)

                                                                ->groupBy('production_order_details.articulo_id')
                                                                ->get();
                foreach ($order_productions as $key => $order_detail)
                {
                    $results['items'][$session][$key]['id']           = $order_detail->id;
                    $results['items'][$session][$key]['product_id']   = $order_detail->articulo_id;
                    $results['items'][$session][$key]['product_name'] = $order_detail->articulo->name;
                    $results['items'][$session][$key]['quantity']     = $order_detail->quantity;
                    $results['items'][$session][$key]['client_id']    = $order_detail->production_order->client_id;
                    $results['items'][$session][$key]['client']       = $order_detail->production_order->client->first_name.' '.$order_detail->production_order->client->last_name;
                    $results['items'][$session][$key]['branch_id']    = $order_detail->production_order->branch_id;
                    $results['items'][$session][$key]['branch']       = $order_detail->production_order->branch->name;
                    $results['items'][$session][$key]['date']         = Carbon::createFromFormat('Y-m-d',$order_detail->production_order->date)->format('d/m/Y');
                    $results['branch_id']                   = $order_detail->production_order->branch_id;
                    $control = SettingProduct::join('production_stages','setting_products.stage_id','=','production_stages.id')->where('articulo_id',$order_detail->articulo_id)->whereNotNull('stage_id')->where('production_stages.number',$session)->first();
                    if($control)
                    {
                        $results['items'][$session][$key]['stage_id']           = $control->stage_id;
                        $results['items'][$session][$key]['stage_name']         = $control->name;
                    }

                }
            }
            return response()->json($results);
        }
        abort(404);
    }

}
