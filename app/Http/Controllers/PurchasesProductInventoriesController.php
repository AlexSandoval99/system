<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmInventoryRequest;
use App\Http\Requests\CreatePurchasesProductInventoriesRequest;
use App\Http\Requests\UpdatePurchasesProductInventoriesRequest;
use App\Models\Deposit;
use App\Models\Inventory;
use App\Models\PriceUpdateLog;
use App\Models\Purchase;
use App\Models\PurchaseMovement;
use App\Models\PurchasesExistence;
use App\Models\PurchasesMovement;
use App\Models\PurchasesOrderDetail;
use App\Models\PurchasesProduct;
use App\Models\PurchasesProductBrand;
use App\Models\RawMaterial;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchasesProductInventoriesController extends Controller
{
    public function index()
    {
        $deposits = Deposit::where('status',true)->get();
        $purchases_product_inventories = Inventory::with('deposit')
            ->orderBy('id', 'desc')->whereIn('status', [1,2]);

        if(request()->filter)
        {
            if(request()->deposit_id)
            {
                $purchases_product_inventories = $purchases_product_inventories->where('deposit_id', request()->deposit_id);
            }

            if(request()->from_date)
            {
                $from_date = Carbon::createFromFormat('d/m/Y', request()->from_date)->format('Y-m-d');
                $purchases_product_inventories = $purchases_product_inventories->where('date', '>=', $from_date);
            }

            if(request()->until_date)
            {
                $until_date = Carbon::createFromFormat('d/m/Y', request()->until_date)->format('Y-m-d');
                $purchases_product_inventories = $purchases_product_inventories->where('date', '<=', $until_date);
            }
        }

        $purchases_product_inventories = $purchases_product_inventories->paginate(20);
        return view('pages.purchases-product-inventories.index', compact('purchases_product_inventories', 'deposits'));
    }

    public function create()
    {
        $deposits = Deposit::where('status',true)->pluck('name','id');
        $deposit_destiny      = Deposit::where('id', '<>', auth()->user()->deposit_id)->where('status',true)->get();
        $purchases_products   = '';
        $existences           = [];

        if(request()->filter and request()->deposit_id)
        {
            $purchases_products = RawMaterial::where('status', true)
                                                    ->orderBy('description');
            $purchases_products = $purchases_products->get();

            $purchases_existences = PurchasesExistence::with('deposit')
                                                    ->select("purchases_existences.*", DB::raw("SUM(purchases_existences.residue) as existence"))
                                                    ->whereHas('raw_material', function ($query) {
                                                                $query->where('status', true);})
                                                    ->where('deposit_id', request()->deposit_id)
                                                    ->where('residue', '>', 0)
                                                    ->groupBy('raw_material_id')
                                                    ->get();
            foreach ($purchases_existences as $purchases_existence)
            {
                $existences[$purchases_existence->raw_material_id] = $purchases_existence->existence;
            }
        }

        return view('pages.purchases-product-inventories.create', compact( 'deposit_destiny', 'existences', 'purchases_products', 'deposits'));
    }

    public function store(CreatePurchasesProductInventoriesRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            // Inventario
            $purchases_product_inventory = Inventory::create([ 'date'        => date('Y-m-d'),
                                                                               'social_reason_id'  => $request->social_reason_id,
                                                                               'purchases_category_id'  => $request->purchases_category_id,
                                                                               'deposit_id'  => $request->deposit_id,
                                                                               'observation' => $request->observation,
                                                                               'status'      => 2,
                                                                               'user_id'     => auth()->user()->id ]);

            foreach($request->product_id AS $product_id => $quantity)
            {
                if($quantity != '')
                {
                    $purchases_product_inventory->purchases_product_inventory_details()->create([
                                                                                              'material_id' => $product_id,
                                                                                              'quantity'   => $quantity,
                                                                                              'existence'  => $request->old_existences[$product_id],
                                                                                              'old_cost'   => $request->old_cost_product[$product_id]
                                                                                          ]);
                }
            }


            toastr()->success('Agregado exitosamente');
        });

        return redirect('inventories');
    }

    public function show(Inventory $purchases_product_inventory)
    {
        $purchases_product_inventory->load(['purchases_product_inventory_details.material']);
        return view('pages.purchases-product-inventories.show', compact('purchases_product_inventory'));
    }

    public function confirm_inventory(ConfirmInventoryRequest $request, Inventory $purchases_product_inventory)
    {
        DB::transaction(function() use ($purchases_product_inventory)
        {
            foreach($purchases_product_inventory->purchases_product_inventory_details as $key => $detail)
            {
                // Entrada de Producto
                if($detail->quantity > $detail->existence)
                {
                    $quantity_final = $detail->quantity - $detail->existence;
                    $dividendo =  11;
                    $price_cost_iva = $detail->old_cost - ($detail->old_cost / $dividendo);

                    $purchases_existence = PurchasesExistence::create([ 'deposit_id'           => $purchases_product_inventory->deposit_id,
                                                                        'raw_material_id'      => $detail->material_id,
                                                                        'quantity'             => $quantity_final,
                                                                        'residue'              => $quantity_final,
                                                                        'price_cost'           => $price_cost_iva,
                                                                        'price_cost_iva'       => $detail->old_cost,
                                                                        'type'                 => 1
                                                                    ]);
                }

                // Salida de Producto
                if($detail->quantity < $detail->existence)
                {
                    $quantity_final = $detail->existence - $detail->quantity;
                    $quantity_process   = $quantity_final;
                    $product_existences = PurchasesExistence::where('residue', '>', 0)
                                                            ->where('deposit_id', $purchases_product_inventory->deposit_id)
                                                            ->where('raw_material_id', $detail->material_id)
                                                            ->orderBy('id')
                                                            ->get();

                        foreach($product_existences as $product_existence)
                        {
                            if($quantity_process > 0)
                            {
                                $quantity_residue = $quantity_process > $product_existence->residue ? $product_existence->residue : $quantity_process;

                                $product_existence->update(['residue' => $product_existence->residue - $quantity_residue]);

                                $quantity_process = $quantity_process - $quantity_residue;
                            }
                        }
                }
            }
        });

        $purchases_product_inventory->update(['status' => 1]);

        toastr()->success('Inventario confirmado exitosamente');

        return redirect('inventories');
    }
}
