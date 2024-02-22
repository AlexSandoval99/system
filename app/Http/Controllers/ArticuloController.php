<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticuloRequest;
use App\Models\Articulo;
use App\Models\Brand;
use App\Models\Purchase;
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
        return view('pages.articulo.create',compact('brand'));
    }

    public function store(CreateArticuloRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $articulo = Articulo::create([
                                        'name'           => $request->name,
                                        'barcode'        => $request->barcode,
                                        'status'         => 1 ]);
        });
        return redirect('articulo');
    }

    public function pdf(Articulo $articulo)
    {
        return PDF::loadView('pages.articulo.pdf', compact('articulo'))
                    ->setPaper([0, 0, 250, 100], 'portrait')
                    // ->setPaper([0,0,300,300], 'portrait')
                    ->stream();
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
}
