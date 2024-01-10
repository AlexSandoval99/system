<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticuloRequest;
use App\Models\Articulo;
use App\Models\Brand;
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
            $purchases = WishPurchase::orderBy('wish_purchases.date', 'desc')
                                    ->selectRaw("wish_purchases.date, wish_purchase_details.quantity")
                                    ->join('wish_purchase_details', 'wish_purchase_details.wish_purchase_id', '=', 'wish_purchases.id')
                                    ->where('wish_purchases.status', true)
                                    ->where('wish_purchase_details.material_id', request()->purchases_product_id)
                                    ->limit(3);

            if(request()->purchases_provider_id)
            {
                $purchases = $purchases->where('wish_purchases.provider_id', request()->purchases_provider_id);
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
