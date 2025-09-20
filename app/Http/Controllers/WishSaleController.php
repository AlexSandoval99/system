<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePurchaseImageRequest;
use App\Http\Requests\CreateWishProductionRequest;
use App\Models\Articulo;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Presentation;
use App\Models\Purchase;
use App\Models\PurchaseBudget;
use App\Models\User;
use App\Models\WishPurchase;
use App\Models\WishSale;
use App\Models\WishSaleDetail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class WishSaleController extends Controller
{
    public function index()
    {
        $client = Client::Filter();
        $sales           = WishSale::with('branch', 'client')
            ->orderBy('id');

        if (request()->p)
        {
            $sales = $sales->where('id', 'LIKE', '%' . request()->p . '%');
        }
        $sales = $sales->paginate(20);
        return view('pages.wish-sales.index', compact('sales', 'client'));
    }

    public function create()
    {
        $users                  = User::filter();
        $branches               = Branch::where('status', true)->pluck('name', 'id');
        $articulos              = Articulo::Filter();
        $product_presentations  = Presentation::Filter();

        return view('pages.wish-sales.create', compact('users' , 'branches', 'articulos', 'product_presentations'));
    }

    public function store(CreateWishProductionRequest $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, & $wish_sale)
            {
                $last_number = WishSale::orderBy('date')->limit(1)->first();
                $last_number = $last_number ? $last_number->number : 0;
                $last_number = $last_number + 1;

                $wish_sale = WishSale::create([
                    'date'                      => $request->date,
                    'branch_id'                 => $request->branch_id,
                    'observation'               => $request->observation,
                    'status'                    => 1,
                    'user_id'                   => auth()->user()->id,
                    'client_id'                 => $request->client_id
                ]);

                // Grabar los Productos
                foreach($request->detail_product_id as $key => $value)
                {

                    $wish_sale->wish_sale_details()->create([
                        'articulo_id'              => $request->detail_product_id[$key],
                        'quantity'                 => $request->detail_product_quantity[$key],
                        'wish_sale_id'       => $wish_sale->id,
                    ]);
                }
            });

            return response()->json([
                'success'            => true,
            ]);
        }
        abort(404);
    }

    public function show(WishSale $wish_sale)
    {
        return view('pages.wish-sales.show', compact('wish_sale'));
    }
    public function edit(WishSale $wish_sale)
    {
        $client = Client::Filter();
        $articulos          = Articulo::Filter();
        $branches           = Branch::where('status', true)->pluck('name', 'id');

        return view('pages.wish-sales.edit',compact('wish_sale','articulos','client','branches'));
    }

    public function update(Request $request, WishSale $wish_sale)
    {
        DB::transaction(function() use ($request, $wish_sale)
        {
            WishSaleDetail::where('wish_sale_id', $wish_sale->id)->delete();

            foreach ($request->detail_product_id as $key => $value)
            {
                WishSaleDetail::create([
                    'articulo_id'  => $request->detail_product_id[$key],
                    'quantity'     => $request->detail_product_quantity[$key],
                    'wish_sale_id' => $wish_sale->id,
                ]);
            }
        });

        return redirect()->route('wish-sales')->with('success', 'Pedido de Venta Actualizado');
    }


    public function charge_purchase_budgets(WishPurchase $wish_purchase)
    {
        return view('pages.wish-purchase.purchase_budgets',compact('wish_purchase'));
    }

    public function charge_purchase_budgets_store(WishPurchase $wish_purchase, CreatePurchaseImageRequest $request)
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

    private function uploadSignature($file)
    {
        $signature_name = Str::random(40) . '.' . $file->getClientOriginalExtension();

        $destinationPath = 'storage/wish_purchases_budgets/' . $signature_name;

        if ($file->move(public_path('storage/wish_purchases_budgets'), $signature_name)) {
            Image::make($destinationPath)
                ->orientate()
                ->save($destinationPath);
        }

        return $signature_name;
    }
    public function confirm_purchase_budgets(WishPurchase $wish_purchase)
    {

        $wish_purchases = $wish_purchase->purchase_budgets()->get();

        return view('pages.wish-purchase.confirm-purchase-budgets',compact('wish_purchase'));
    }

    public function confirm_purchase_budgets_store(PurchaseBudget $purchase_budget)
    {
        $text = 'Presupuesto Aprobado';
        // APROBAR EL PRESUPUESTO
        if(request()->type == 1)
        {
            $purchase_budget->update(['confirmation_user_id'=> auth()->user()->id,'confirmation_date'=> now(),'status'=>2]);
            $purchase_budget->wish_purchase->update(['status' => 5]);
        }
        //BORRAR EL PRESUPUESTO
        elseif(request()->type == 2)
        {
            $purchase_budget->update(['confirmation_user_id'=> auth()->user()->id,'confirmation_date'=> now(),'status'=>3]);
            $text = 'Presupuesto Rechazado';

        }
        // RECHAZAR EL PRESUPUESTO
        elseif(request()->type == 3)
        {
            $text = 'Presupuesto Borrado';
            $purchase_budget->delete();
        }

        if(request()->url)
        {
            return redirect(request()->url);
        }
        else
        {
            return redirect('wish-purchase');
        }
    }

    public function wish_purchase_budgets_approved(WishPurchase $wish_purchase)
    {
        $purchase_budgets = $wish_purchase->purchase_budgets()->where('status',2)->get();

        return view('pages.wish-purchase.wish-purchase-budgets-approved',compact('wish_purchase','purchase_budgets'));
    }

    // public function pdf(WishPurchase $wish_purchase)
    // {
    //      return PDF::loadView('pages.wish-purchase.pdf', compact('wish_purchase'))
    //         ->setPaper('A4', 'portrait')
    //         ->stream();

    // }

    public function searchProviderStamped()
    {
        $invoice_number = explode('-', request()->purchase_number);
        $invoice_number = $invoice_number[0] . '-' . $invoice_number[1];

        $purchase = Purchase::select("purchases.*", DB::raw("DATE_FORMAT(stamped_validity, '%d/%m/%Y') stamp_validity"))
            ->where('provider_id', request()->provider_id)
            ->where('number', 'like', ['%'.$invoice_number.'%'])
            ->whereIn('status', [1,3,4])
            ->orderBy('id', 'DESC')
            ->first();

        return  response()->json($purchase);
    }

    public function pdf(WishPurchase $restocking)
    {
        return PDF::loadView('pages.wish-purchase.pdf', compact('restocking'))
                    ->setPaper([0, 0, 250, 100], 'portrait')
                    // ->setPaper([0,0,300,300], 'portrait')
                    ->stream();
    }
    private function parse($value)
    {
        return str_replace(',', '.',str_replace('.', '', $value));
    }

}
