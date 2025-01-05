<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRawMaterialsRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\RawMaterial;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RawMaterialsController extends Controller
{
    public function index()
    {
        $materiap = RawMaterial::where('status',1)->get();
        return view('pages.raw-materials.index',compact('materiap'));
    }

    public function create()
    {
        return view('pages.raw-materials.create');
    }

    public function store(CreateRawMaterialsRequest $request)
    {
        RawMaterial::create([
            'description' => request()->description,
            'status' => 1
        ]);

        $this->flashMessage('check', 'La materia prima fue registrado correctamente', 'success');

        return redirect()->route('raw-materials');
    }
    public function ajax_purchases_orders()
    {
        if(request()->ajax())
        {
            $results        = [];
            $product_orders = PurchaseOrderDetail::where('residue', '>', 0)
                                                  ->where('purchases_order_id', request()->id)
                                                  ->get();
            $results                = [];
            $results['total_count'] = count($product_orders);
            foreach ($product_orders as $key => $product_order)
            {
                $results['items'][$key]['id']                    = $product_order->material_id;
                $results['items'][$key]['name']                  = $product_order->raw_material->description;
                $results['items'][$key]['description']           = $product_order->description;
                $results['items'][$key]['price']                 = number_format($product_order->amount, 2,',','');
                $results['items'][$key]['quantity']              = $product_order->quantity;
                $results['items'][$key]['number_order']          = $product_order->purchase_order->number;
                $results['items'][$key]['id_order']              = $product_order->id;

            }

            // Buscar la fecha de Recepcion
            $purchases_movements = PurchaseOrder::select("purchase_movements.invoice_number", "purchase_movements.invoice_date", "purchase_movements.date_payment", "purchase_movements.branch_id", "purchase_movements.invoice_condition", "purchase_movements.invoice_stamped", "purchase_movements.stamp_validity")
                                                ->join('purchase_order_details', 'purchase_order_details.purchases_order_id', '=', 'purchase_orders.id')
                                                ->join('purchase_movements_details', 'purchase_movements_details.purchases_order_detail_id', '=', 'purchase_order_details.id')
                                                ->join('purchase_movements', 'purchase_movements_details.purchase_movement_id', '=', 'purchase_movements.id')
                                                ->where('purchase_orders.id', request()->id)
                                                ->whereNotNull('purchase_movements.date_payment')
                                                ->orderBy('purchase_movements.id', 'DESC')
                                                ->groupBy('purchase_movements.id')
                                                ->limit(1)
                                                ->get();
            if($purchases_movements)
            {
                foreach ($purchases_movements as $movement)
                {
                    $results['invoice_branch_id'] = $movement->branch_id;
                    $results['invoice_currency_id'] = $movement->currency_id;
                    $results['invoice_condition'] = $movement->invoice_condition;
                    $results['invoice_stamped'] = $movement->invoice_stamped ?  $movement->invoice_stamped : null;
                    $results['invoice_stamp_validity']   = $movement->stamp_validity ?  Carbon::createFromFormat('Y-m-d', $movement->stamp_validity)->format('d/m/Y') : null;
                    $results['invoice_number'] = $movement->invoice_number;
                    $results['invoice_date']   = Carbon::createFromFormat('Y-m-d', $movement->invoice_date)->format('d/m/Y');
                    $results['date_payment']   = Carbon::createFromFormat('Y-m-d', $movement->date_payment)->format('d/m/Y');
                }
            }else
            {
                $results['orders'][0]['invoice_number'] = '';
                $results['orders'][0]['invoice_date']   = '';
                $results['orders'][0]['date_payment']   = '';
            }

            return response()->json($results);
        }
        abort(404);
    }
    public function show(RawMaterial $materiap)
    {

        return view('pages.raw-materials.show', compact('materiap'));
    }

    public function ajax_purchases_products()
    {
        if(request()->ajax())
        {
            $products = RawMaterial::select('raw_materials.id',
                                                 'raw_materials.description',
                                                 'raw_materials.type_iva',
                                             )
                ->where('raw_materials.status', true)
                ->WhereRaw("raw_materials.description LIKE ?", ["%" . str_replace(' ', '%', request()->q) . "%"]);

            if(request()->purchase_order_number)
            {
                $products = $products->whereExists(function($query){
                    $query->from('purchase_order_details')
                        ->join('purchase_orders', 'purchase_order_details.purchases_order_id', '=', 'purchase_orders.id')
                        ->whereRaw('purchase_order_details.purchases_product_id = raw_materials.id AND purchase_orders.number = '.request()->purchase_order_number);
                });
            }
            $products = $products->orderBy('raw_materials.description')
                                ->limit(20)
                                ->get();
            $results = [];
            if ($products)
            {
                foreach ($products as $key => $product)
                {
                    $results['items'][$key]['id']                  = $product->id;
                    $results['items'][$key]['type_iva']            = $product->type_iva;
                    $results['items'][$key]['name']                = $product->description;
                }
            }
            return response()->json($results);
        }
        abort(404);
    }
}
