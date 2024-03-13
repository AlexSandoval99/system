<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductionOrderRequest;
use App\Http\Requests\CreateProductionQualityRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\BudgetProductionDetail;
use App\Models\Client;
use App\Models\Losse;
use App\Models\LosseDetail;
use App\Models\Presentation;
use App\Models\ProductionControl;
use App\Models\ProductionControlDetail;
use App\Models\ProductionControlQuality;
use App\Models\ProductionCost;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\ProductionQualityControl;
use App\Models\Provider;
use App\Models\PurchaseBudget;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\PurchasesExistence;
use App\Models\SettingProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductionControlQualityController extends Controller
{
    public function index()
    {
        $clients = Client::Filter();
        $order           = ProductionQualityControl::with('branch')
            ->orderBy('id', 'desc');

         $order = $order->paginate(20);
         return view('pages.production-control-quality.index', compact('order', 'clients'));
    }

    public function create()
    {
        $users                  = User::filter();
        $branches               = Branch::where('status', true)->pluck('name', 'id');
        $articulos                = Articulo::Filter();
        $product_presentations  = Presentation::Filter();
        $provider_suggesteds    = NULL;
        return view('pages.production-control-quality.create', compact('users' , 'branches', 'articulos', 'product_presentations','provider_suggesteds'));
    }

    public function store(CreateProductionQualityRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, & $control)
            {
                $control = ProductionQualityControl::create([
                    'date'                      => $request->date,
                    'status'                    => 1,
                    'client_id'                 => $request->client_id,
                    'branch_id'                 => $request->branch_id,
                    'user_id'                   => auth()->user()->id,
                ]);

                // Grabar los Productos
                foreach($request->detail_product_id as $key => $value)      
                {
                    $control->production_quality_control_details()->create([
                        
                        'articulo_id'           => $value,
                        'quantity'              => $request->{"total$key"} ?? 0,
                        'residue'               => $request->{"cantidad_controlada$key"} ?? 0,
                        'observation'           => $request->{"observacion$key"} ?? '',
                        'quality'                 => $request->{"etapa$key"} ? 1 : 0,
                        'production_quality_id' => $control->id,
                        'quality_id'              => $request->{"quality_id$key"}
                    ]);
                }
                $array_products = $control->production_quality_control_details()->groupBy('articulo_id')->get();
                foreach ($array_products as $key => $product)
                {
                    if($request->{"total$key"} > $request->{"cantidad_controlada$key"})
                    {
                        $losse = Losse::where('control_quality_id',$control->id)->first();
                        if(!$losse)
                        {
                            $losse = Losse::create([
                                'status'            => 1,
                                'date'               => $request->date,
                                'user_id'            => auth()->user()->id,
                                'branch_id'          => $request->branch_id,
                                'control_quality_id' => $control->id
                            ]);
                        }
    
                        $materials = RawMaterial::where('articulo_id',$product->articulo_id)->where('status',1)->get();
                        foreach ($materials as $key => $material) 
                        {
                            $losse->losse_detail()->create([
                                'articulo_id'   => $product->articulo_id,
                                'reason'        => $request->{"observacion$key"},
                                'material_id'   => $material->id, 
                                'quantity'      => $request->{"total$key"} - $request->{"cantidad_controlada$key"},
                                'losse_id'      => $losse->id
                            ]);
                        }
                    }
                    $cost_product = ProductionCost::where('control_quality_id',$control->id)->first();
                    if(!$cost_product)
                    {
                        $cost_product = ProductionCost::create([
                            'date'               => $request->date,
                            'status'            => 1,
                            'branch_id'          => $request->branch_id,
                            'user_id'            => auth()->user()->id,
                            'control_quality_id' => $control->id
                        ]);
                    }

                    $materials = RawMaterial::where('articulo_id',$product->articulo_id)->where('status',1)->get();
                    foreach ($materials as $key => $material) 
                    {
                        $cost_product->production_cost_detail()->create([
                            'articulo_id'           => $product->articulo_id,
                            'material_id'           => $material->id, 
                            'quantity'              => $request->{"total$key"},
                            'production_cost_id'    => $cost_product->id,
                            'price_cost'            => $request->{"total$key"} * $material->average_cost
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

    public function show(ProductionQualityControl $control)
    {

        return view('pages.production-control-quality.show', compact('control'));
    }


    public function ajax_control_calidad()
    {
        if(request()->ajax())
        {
            $results = [];        
            $order_productions = ProductionControlDetail::with('production_control', 'articulo')
                                                            ->select("production_control_details.*")
                                                            ->join('production_controls', 'production_control_details.production_control_id', '=', 'production_controls.id')
                                                            ->where('production_controls.status', true)
                                                            ->where('production_controls.id', request()->number_control)

                                                            ->groupBy('production_control_details.articulo_id')
                                                            ->get();
            foreach ($order_productions as $key => $order_detail)
            {
                $results['items'][$key]['id']           = $order_detail->id;
                $results['items'][$key]['product_id']   = $order_detail->articulo_id;
                $results['items'][$key]['product_name'] = $order_detail->articulo->name;
                $results['items'][$key]['quantity']     = $order_detail->quantity;
                $results['items'][$key]['client_id']    = $order_detail->production_control->client_id;
                $results['items'][$key]['client']       = $order_detail->production_control->client->first_name.' '.$order_detail->production_control->client->last_name;
                $results['items'][$key]['branch_id']    = $order_detail->production_control->branch_id;
                $results['items'][$key]['branch']       = $order_detail->production_control->branch->name;
                $results['items'][$key]['date']         = Carbon::createFromFormat('Y-m-d',$order_detail->production_control->date)->format('d/m/Y');
                $results['branch_id']                   = $order_detail->production_control->branch_id;
                $control = SettingProduct::join('production_qualities','setting_products.production_qualities_id','=','production_qualities.id')->where('articulo_id',$order_detail->articulo_id)->whereNotNull('production_qualities_id')->where('production_qualities.number',request()->sesion)->first();
                if($control)
                {
                    $results['items'][$key]['production_qualities_id']           = $control->production_qualities_id;
                    $results['items'][$key]['qualities_name']         = $control->name;
                }

            }         
            return response()->json($results);
        }
        abort(404);
    }
    
}
