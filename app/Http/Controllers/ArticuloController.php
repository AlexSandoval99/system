<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticuloRequest;
use App\Models\Articulo;
use App\Models\Brand;
use App\Models\ProductionStage;
use App\Models\Purchase;
use App\Models\RawMaterial;
use App\Models\SettingProduct;
use App\Models\WishPurchase;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index()
    {
        $articulos = Articulo::orderBy('name')->paginate(20);
        return view('pages.articulo.index' ,compact('articulos'));
    } //

    public function create()
    {
        $brand = Brand::where('status',1)->pluck('name','id');
        $materials = RawMaterial::filter();
        $stages = ProductionStage::filter();
        return view('pages.articulo.create',compact('brand','materials','stages'));
    }

    public function store(CreateArticuloRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $articulo = Articulo::create([
                                        'name'           => $request->name,
                                        'barcode'        => $request->barcode,
                                        'price'        => $request->price,
                                        'status'         => 1 ]);
            foreach ($request->materiales as $key => $value) 
            {
                SettingProduct::create([
                    'articulo_id'       => $articulo->id,
                    'raw_materials_id'   => $value,
                    'quantity'          => $request->cantidades[$key],
                ]);
            }
            foreach ($request->stages as $key1 => $value1) 
            {
                SettingProduct::create([
                    'articulo_id'       => $articulo->id,
                    'stage_id'       => $value1,
                ]);
            }
        });
        return redirect('articulo');
    }

    public function show(Articulo $articulo)
    {
        $articulo->load(['setting_product']);

        return view('pages.articulo.show', compact('articulo'));
    }

    public function pdf(Articulo $articulo)
    {
        return PDF::loadView('pages.articulo.pdf', compact('articulo'))
                    ->setPaper([0, 0, 250, 100], 'portrait')
                    // ->setPaper([0,0,300,300], 'portrait')
                    ->stream();
    }


    public function edit(Articulo $articulo_id)
    {
        return view('pages.articulo.edit',compact('articulo_id'));
    }

    public function update(Articulo $articulo_id)
    {
            $articulo_id->update([
                                'name'       => request()->name,
                                'ruc'        => request()->ruc,
                                'address'     => request()->address,
                                'phone'      => request()->phone]);

        return redirect('articulo');
    }


    

    public function ajax_purchases_last()
    {
        if(request()->ajax())
        {
            // Buscar La ultima Compra del Producto
            $results   = [];
            $purchases = Purchase::orderBy('purchases.date', 'desc')
                                    ->selectRaw("purchases.date, purchase_details.quantity")
                                    ->join('purchase_details', 'purchase_details.purchase_id', '=', 'purchases.id')
                                    ->where('purchases.status', true)
                                    ->where('purchase_details.material_id', request()->purchases_product_id)
                                    ->limit(3);

            if(request()->purchases_provider_id)
            {
                $purchases = $purchases->where('purchases.provider_id', request()->purchases_provider_id);
            }
            $purchases = $purchases->get();

            $results                = [];
            $results['total_count'] = count($purchases);
            foreach ($purchases as $key => $purchase)
            {
                $results['items'][$key]['id']       = $purchase->id;
                $results['items'][$key]['date']     = $purchase->date->format('d/m/Y');
                $results['items'][$key]['quantity'] = $purchase->quantity;
            }

            return response()->json($results);
        }
        abort(404);
    }

    public function ajax_articulo()
    {
        if(request()->ajax())
        {
            $results   = [];

            if(request()->articulo_id)
            {
                $articulo = Articulo::where('id',request()->articulo_id)->first();
                $results                = [];
                $results['items']['id']       = $articulo->id;
                $results['items']['name']     = $articulo->name;
                $results['items']['price']    = intVal($articulo->price);
            }

            return response()->json($results);
        }
        abort(404);
    }
}
