<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductionOrderRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\BudgetProductionDetail;
use App\Models\Client;
use App\Models\Presentation;
use App\Models\ProductionControl;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
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

        if (request()->o)
        {
            $order = $order->where('ruc', 'LIKE', '%' . request()->o . '%')
                ->orWhere('number', 'LIKE', '%' . request()->o . '%');
        }

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

    public function store(CreateProductionOrderRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, &$wish_purchase)
            {
                $last_number = PurchaseOrder::orderBy('number', 'desc')->limit(1)->first();
                $last_number = $last_number ? $last_number->number : 0;
                $last_number = $last_number + 1;

                $wish_purchase = PurchaseOrder::create([
                    'number'                    => $last_number,
                    'date'                      => $request->date,
                    'ruc'                       => $request->ruc,
                    'branch_id'                 => $request->branch_id,
                    'condition'                 => $request->condition,
                    'provider_id'               => $request->purchases_provider_id,
                    'razon_social'              => $request->razon_social,
                    'phone'                     => $request->phone,
                    'address'                   => $request->address,
                    'status'                    => 1,
                    'observacion'               => $request->observation,
                    'user_id'                   => auth()->user()->id,
                    'amount'                    => $this->parse($request->total_product)
                ]);

                // Grabar los Productos
                foreach($request->detail_product_id as $key => $value)
                {

                    $wish_purchase->purchase_order_details()->create([
                        'material_id'              => $request->detail_product_id[$key],
                        'quantity'                 => $request->detail_product_quantity[$key],
                        'description'              => isset($request->detail_product_description[$key]) ? $request->detail_product_name[$key].'('.$request->detail_product_description[$key].')' : $request->detail_product_name[$key],
                        'amount'                   => $this->parse($request->detail_product_amount[$key]),
                        'residue'                  => $this->parse($request->detail_product_amount[$key]),
                    ]);
                }
            });

            return response()->json([
                'success'            => true,
                'purchases_order_id' => $wish_purchase->id
            ]);
        }
        abort(404);
    }

    public function show(PurchaseOrder $wish_purchase)
    {

        return view('pages.wish-purchase.show', compact('wish_purchase'));
    }

    public function charge_purchase_budgets(PurchaseOrder $wish_purchase)
    {
        return view('pages.wish-purchase.purchase_budgets',compact('wish_purchase'));
    }

    // private function uploadSignature($file)
    // {
    //     $signature_name = Str::random(40) . '.' . $file->getClientOriginalExtension();

    //     $destinationPath = 'storage/wish_purchases_budgets/' . $signature_name;

    //     if ($file->move(public_path('storage/wish_purchases_budgets'), $signature_name)) {
    //         Image::make($destinationPath)
    //             ->orientate()
    //             ->save($destinationPath);
    //     }

    //     return $signature_name;
    // }
    // public function confirm_purchase_budgets(PurchaseOrder $wish_purchase)
    // {

    //     $wish_purchases = $wish_purchase->purchase_budgets()->get();

    //     return view('pages.wish-purchase.confirm-purchase-budgets',compact('wish_purchase'));
    // }

    // public function confirm_purchase_budgets_store(PurchaseBudget $purchase_budget)
    // {
    //     $text = 'Presupuesto Aprobado';
    //     // APROBAR EL PRESUPUESTO
    //     if(request()->type == 1)
    //     {
    //         $purchase_budget->update(['confirmation_user_id'=> auth()->user()->id,'confirmation_date'=> now(),'status'=>2]);
    //         $purchase_budget->wish_purchase->update(['status' => 5]);
    //     }
    //     //BORRAR EL PRESUPUESTO
    //     elseif(request()->type == 2)
    //     {
    //         $purchase_budget->update(['confirmation_user_id'=> auth()->user()->id,'confirmation_date'=> now(),'status'=>3]);
    //         $text = 'Presupuesto Rechazado';

    //     }
    //     // RECHAZAR EL PRESUPUESTO
    //     elseif(request()->type == 3)
    //     {
    //         $text = 'Presupuesto Borrado';
    //         $purchase_budget->delete();
    //     }

    //     if(request()->url)
    //     {
    //         return redirect(request()->url);
    //     }
    //     else
    //     {
    //         return redirect('wish-purchase');
    //     }
    // }

    // public function wish_purchase_budgets_approved(PurchaseOrder $wish_purchase)
    // {
    //     $purchase_budgets = $wish_purchase->purchase_budgets()->where('status',2)->get();

    //     return view('pages.wish-purchase.wish-purchase-budgets-approved',compact('wish_purchase','purchase_budgets'));
    // }

    private function parse($value)
    {
        return str_replace(',', '.',str_replace('.', '', $value));
    }

    public function ajax_control_production()
    {
        if(request()->ajax())
        {
            $results = [];        
            $order_productions = ProductionOrderDetail::with('production_order', 'articulo')
                                                            ->select("production_order_details.*")
                                                            ->join('production_orders', 'production_order_details.production_order_id', '=', 'production_orders.id')
                                                            ->where('production_orders.status', true)
                                                            ->where('production_orders.id', request()->number_order)

                                                            ->groupBy('production_order_details.articulo_id')
                                                            ->get();
            foreach ($order_productions as $key => $order_detail)
            {
                $results['items'][$key]['id']           = $order_detail->id;
                $results['items'][$key]['product_id']   = $order_detail->articulo_id;
                $results['items'][$key]['product_name'] = $order_detail->articulo->name;
                $results['items'][$key]['quantity']     = $order_detail->quantity;
                $results['items'][$key]['client_id']    = $order_detail->production_order->client_id;
                $results['items'][$key]['client']       = $order_detail->production_order->client->first_name.' '.$order_detail->production_order->client->last_name;
                $results['items'][$key]['branch_id']    = $order_detail->production_order->branch_id;
                $results['items'][$key]['branch']       = $order_detail->production_order->branch->name;
                $results['items'][$key]['date']         = Carbon::createFromFormat('Y-m-d',$order_detail->production_order->date)->format('d/m/Y');
                $results['branch_id']                   = $order_detail->production_order->branch_id;
                $control = SettingProduct::join('production_stages','setting_products.stage_id','=','production_stages.id')->where('articulo_id',$order_detail->articulo_id)->whereNotNull('stage_id')->where('production_stages.number',request()->sesion)->first();
                if($control)
                {
                    $results['items'][$key]['stage_id']           = $control->stage_id;
                    $results['items'][$key]['stage_name']         = $control->name;
                }

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
            foreach ($articulo->setting_material as $key => $setting)
            {
                $results['items'][$key]['id']           = $setting->id;
                $results['items'][$key]['articulo_id']   = $setting->articulo_id;
                $results['items'][$key]['articulo_name'] = $setting->articulo->name;
                $results['items'][$key]['raw_material']     = $setting->raw_materials_id;
                $results['items'][$key]['raw_material_id']     = $setting->raw_material->description;
                $results['items'][$key]['quantity']        = $setting->quantity * $order->quantity;
            }         
            return response()->json($results);
        }
        abort(404);
    }
    
}
