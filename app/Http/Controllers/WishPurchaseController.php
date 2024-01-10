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

        if (request()->purchases_provider_id)
        {
            $purchases = $purchases->where('purchases_provider_id', request()->purchases_provider_id);
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

                // $orders_name = NULL;
                // $file        = request()->file('signature_image');
                // if($file)
                // {
                //     $orders_name = $this->uploadSignature($file);
                // }

                $purchases_order = WishPurchase::create([
                    'number'                    => $last_number,
                    'date'                      => $request->date,
                    // 'requesting_departments_id' => $request->requesting_departments_id,
                    // 'requested_by'              => $request->requested_by,
                    'branch_id'                 => $request->branch_id,
                    // 'social_reason_id'          => $request->social_reason_id,
                    // 'condition'                 => $request->condition,
                    'purchases_provider_id'     => $request->purchases_provider_id,
                    // 'razon_social'              => $request->razon_social,
                    'ruc'                       => $request->ruc,
                    'phone'                     => $request->phone,
                    'address'                   => $request->address,
                    'observation'               => $request->observation,
                    'amount'                    => $this->parse($request->total_product),
                    // 'currency_id'               => $request->currency_id,
                    // 'authorized_user_id'        => $request->authorized_user_id,
                    // 'verified_user_id'          => $request->verified_user_id,
                    // 'controlled_user_id'        => $request->controlled_user_id,
                    // 'change'                    => $request->change,
                    // 'image'                     => $orders_name,
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
                        'deposit_id'               => 1
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

    private function parse($value)
    {
        return str_replace(',', '.',str_replace('.', '', $value));
    }
}
