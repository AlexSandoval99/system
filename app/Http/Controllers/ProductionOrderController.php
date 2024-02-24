<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductionOrderRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\Presentation;
use App\Models\Provider;
use App\Models\PurchaseBudget;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductionOrderController extends Controller
{
    public function index()
    {
        $purchases_providers = Provider::Filter();
        $order           = PurchaseOrder::with('branch', 'provider')
            ->orderBy('id', 'desc');

        if (request()->o)
        {
            $order = $order->where('ruc', 'LIKE', '%' . request()->o . '%')
                ->orWhere('number', 'LIKE', '%' . request()->o . '%');
        }

        if (request()->invoice_copy)
        {
            $order = $order->where('invoice_copy', request()->invoice_copy);
        }

         $order = $order->paginate(20);
         return view('pages.purchase-order.index', compact('order', 'purchases_providers'));
    }

    public function create()
    {
        $users                  = User::filter();
        $branches               = Branch::where('status', true)->pluck('name', 'id');
        $raw_materials          = RawMaterial::Filter();
        $product_presentations  = Presentation::Filter();
        $provider_suggesteds    = NULL;
        return view('pages.production-order.create', compact('users' , 'branches', 'raw_materials', 'product_presentations','provider_suggesteds'));
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

    public function charge_purchase_budgets_store(PurchaseOrder $wish_purchase, CreatePurchaseImageRequest $request)
    {

        if (request()->ajax()) {
            if ($request->hasFile('files')) {
                $wish_purchase->purchase_budgets()->delete();

                foreach ($request->file('files') as $key => $input_file) {
                    $file = $input_file;

                    $dir = 'storage/wish_purchases_budgets';
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    if ($file) {
                        $filename = $this->uploadSignature($file);
                    }

                    $wish_purchase->purchase_budgets()->create([
                        'name' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            return response()->json(['success' => true]);
        }
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
}
