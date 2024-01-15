<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWishPurchaseRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\Presentation;
use App\Models\Provider;
use App\Models\User;
use App\Models\WishPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishPurchaseController extends Controller
{
    public function index()
    {
        $purchases_providers = Provider::Filter();
        $purchases           = WishPurchase::with('branch', 'provider')
            ->orderBy('id', 'desc');

        if (request()->s)
        {
            $purchases = $purchases->where('ruc', 'LIKE', '%' . request()->s . '%')
                ->orWhere('number', 'LIKE', '%' . request()->s . '%');
        }

        if (request()->invoice_copy)
        {
            $purchases = $purchases->where('invoice_copy', request()->invoice_copy);
        }

        $purchases = $purchases->whereIn('status', [1,2])->paginate(20);
        return view('pages.wish-purchase.index', compact('purchases', 'purchases_providers'));
    }

    public function create()
    {
        $users                  = User::filter();
        $branches               = Branch::where('status', true)->pluck('name', 'id');
        $purchases_products     = Articulo::Filter();
        $product_presentations  = Presentation::Filter();

        return view('pages.wish-purchase.create', compact('users' , 'branches', 'purchases_products', 'product_presentations'));
    }

    public function store(CreateWishPurchaseRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, &$purchases_order)
            {
                $last_number = WishPurchase::orderBy('number', 'desc')->limit(1)->first();
                $last_number = $last_number ? $last_number->number : 0;
                $last_number = $last_number + 1;

                $purchases_order = WishPurchase::create([
                    'number'                    => $last_number,
                    'date'                      => $request->date,
                    'branch_id'                 => $request->branch_id,
                    'observation'               => $request->observation,
                    'status'                    => 1,
                    'user_id'                   => auth()->user()->id
                ]);

                // Grabar los Productos
                foreach($request->detail_product_id as $key => $value)
                {

                    $purchases_order->purchases_order_details()->create([
                        'material_id'              => $request->detail_product_id[$key],
                        'quantity'                 => $request->detail_product_quantity[$key],
                        'wish_purchase_id'         => $purchases_order->id,
                        'deposit_id'               => 1,
                        'presentation'             => $request->detail_presentation_id[$key],
                        'description'              => isset($request->detail_product_description[$key]) ? $request->detail_product_name[$key].'('.$request->detail_product_description[$key].')' : $request->detail_product_name[$key],
                    ]);
                }
            });

            return response()->json([
                'success'            => true,
                'purchases_order_id' => $purchases_order->id
            ]);
        }
        abort(404);
    }

    public function show(WishPurchase $wish_purchase)
    {

        return view('pages.wish-purchase.show', compact('wish_purchase'));
    }

    public function charge_purchase_budgets(WishPurchase $wish_purchase)
    {
        return view('pages.wish-purchase.purchase_budgets',compact('wish_purchase'));
    }

    private function parse($value)
    {
        return str_replace(',', '.',str_replace('.', '', $value));
    }
}
