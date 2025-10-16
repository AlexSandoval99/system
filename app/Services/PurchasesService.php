<?php
namespace App\Services;

use App\Models\Deposit;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseNoteCredit;
use App\Models\PurchaseOrder;
use App\Models\PurchasesCollect;
use App\Models\PurchasesExistence;
use App\Models\RawMaterial;
use Carbon\Carbon;

class PurchasesService
{
	public function store($request, $purchase, $user_id)
	{
        if ($purchase)
        {
            $purchase->update([
                'condition'                 => $request->condition,
                'branch_id'                 => $request->branch_id,
                'number'                    => $request->number,
                'date'                      => $request->date,
                'change'                    => $request->change,
                'stamped'                   => $request->stamped,
                'stamped_validity'          => $request->stamped_validity ? ($request->electronic_document == 1 ? NULL : $request->stamped_validity   ) : NULL,
                'observation'               => $request->observation,
                'amount'                    => cleartStringNumber($request->total_product),
                'total_excenta'             => cleartStringNumber($request->total_excenta_final),
                'total_iva5'                => cleartStringNumber($request->total_iva5_final),
                'total_iva10'               => cleartStringNumber($request->total_iva10_final),
                'amount_iva5'               => cleartStringNumber($request->amount_iva5),
                'amount_iva10'              => cleartStringNumber($request->amount_iva10),
                'user_id'                   => $user_id,
                'status'                    => 1,
            ]);
            $purchase->purchases_details()->delete();
        }
        else
        {
            $purchase = Purchase::create([
                'date'                  => $request->date,
                'branch_id'             => $request->branch_id,
                'stamped'               => $request->stamped,
                'type'                  => $request->type,
                'condition'             => $request->condition,
                'number'                => $request->number,
                'provider_id'           => $request->purchases_provider_id,
                'razon_social'          => $request->razon_social,
                'ruc'                   => $request->ruc,
                'phone'                 => $request->phone,
                'address'               => $request->address,
                'observation'           => $request->observation,
                'stamped_validity'      => $request->stamped_validity ? ($request->electronic_document == 1 ? NULL : $request->stamped_validity   ) : NULL,
                'amount'                => cleartStringNumber($request->total_product),
                'total_excenta'         => cleartStringNumber($request->total_excenta_final),
                'total_iva5'            => cleartStringNumber($request->total_iva5_final),
                'total_iva10'           => cleartStringNumber($request->total_iva10_final),
                'amount_iva5'           => cleartStringNumber($request->amount_iva5),
                'amount_iva10'          => cleartStringNumber($request->amount_iva10),
                'change'                => $request->change,
                'status'                => true,
                'user_id'               => $user_id,
                'order_id'              => $request->order_id ? $request->order_id : NULL
            ]);
        }
        if($request->order_id)
        {
            $purchase_order = PurchaseOrder::find($request->order_id);
            $purchase_order->update(['status' => 2]); // Comprado
        }

        // Nota de Credito
        if ($request->type == 2)
        {
            $invoice = Purchase::find($request->invoice_id);
            $amount = cleartStringNumber($request->total_product);

            PurchaseNoteCredit::create([
                'purchase_id'         => $purchase->id,
                'purchase_invoice_id' => $invoice->id
            ]);

            if ($amount >= $invoice->amount)
            {
                $invoice->update(['status' => 3]); // Anulado
            }
            else
            {
                $invoice->update(['status' => 3]); // NC Parcial
            }

            foreach ($request->detail_product_id as $key => $product_id)
            {
                $quantity = $request->detail_product_quantity[$key];
                $amount_product = cleartStringNumber($request->detail_product_amount[$key]);

                $purchase_detail = PurchaseDetail::create([
                    'purchase_id'               => $purchase->id,
                    'material_id'               => $request->detail_product_id[$key],
                    'purchases_order_detail_id' => $request->detail_product_orders_id[$key] ? $request->detail_product_orders_id[$key] : NULL,
                    'description'               => $request->detail_product_name[$key],
                    'quantity'                  => $request->detail_product_quantity[$key],
                    'amount'                    => $amount_product,
                    'excenta'                   => cleartStringNumber($request->detail_total_excenta[$key]),
                    'iva5'                      => cleartStringNumber($request->detail_total_iva5[$key]),
                    'iva10'                     => cleartStringNumber($request->detail_total_iva10[$key])
                ]);

                $deposit = Deposit::where('branch_id', $request->branch_id)->first();
                $existencia = PurchasesExistence::where('deposit_id', $deposit->id)
                                ->where('raw_material_id', $product_id)
                                ->where('residue', '>', 0)
                                ->orderBy('id', 'DESC')
                                ->first();
                if ($existencia)
                {
                    $existencia->update(['residue' => $existencia->residue - $quantity]);
                }

            }
        }
        else
        {
            foreach($request->detail_product_id as $key => $value)
            {
                $amount_product = cleartStringNumber($request->detail_product_amount[$key]);
                $purchase_detail = PurchaseDetail::create([
                    'purchase_id'               => $purchase->id,
                    'material_id'               => $request->detail_product_id[$key],
                    'purchases_order_detail_id' => $request->detail_product_orders_id[$key] ? $request->detail_product_orders_id[$key] : NULL,
                    'description'               => $request->detail_product_name[$key],
                    'quantity'                  => $request->detail_product_quantity[$key],
                    'amount'                    => $amount_product,
                    'excenta'                   => cleartStringNumber($request->detail_total_excenta[$key]),
                    'iva5'                      => cleartStringNumber($request->detail_total_iva5[$key]),
                    'iva10'                     => cleartStringNumber($request->detail_total_iva10[$key])
                ]);

                $deposit = Deposit::where('branch_id', $request->branch_id)->first();
                $purchases_existence = PurchasesExistence::create([
                                        'deposit_id'           => $deposit->id,
                                        'type'                 => 1,
                                        'raw_material_id'      => $request->detail_product_id[$key],
                                        'quantity'             => $request->detail_product_quantity[$key],
                                        'residue'              => $request->detail_product_quantity[$key],
                                        'price_cost'           => $amount_product,
                                        'price_cost_iva'       => $amount_product * 1.1
                                    ]);
                //ACTUALIZACION DE COSTO PROMEDIO
                $existences = PurchasesExistence::where('raw_material_id',  $request->detail_product_id[$key])
                            ->where('residue', '>', 0)
                            ->get()
                            ->sum('residue');

                $existence_costs = PurchasesExistence::selectRaw('sum(residue) as total_quantity, price_cost,raw_material_id')
                                ->where('raw_material_id', $request->detail_product_id[$key])
                                ->where('residue', '>', 0)
                                ->groupBy('price_cost','raw_material_id')
                                ->get();
                $cost_array = [];
                $n_cost = null;
                foreach ($existence_costs as $key1 => $cost)
                {
                    if(isset($cost_array[($cost->raw_material_id)]))
                    {
                        $cost_array[$cost->raw_material_id] += $cost->total_quantity * $cost->price_cost;
                    }
                    else
                    {
                        $cost_array[$cost->raw_material_id] = $cost->total_quantity * $cost->price_cost;
                    }
                }

                if($existences)
                {
                    $new_cost = ($cost_array[$request->detail_product_id[$key]] / $existences);
                    $raw_material = RawMaterial::where('id',$request->detail_product_id[$key])->update(['average_cost' =>$new_cost]);
                }
            }
            if(sum_array($request->amount_treasury) > 0)
            {
                $monto = $request->amount_treasury[0] / $request->quota;
                for ($i=0; $i < $request->quota ; $i++)
                {
                    $expiration = Carbon::createFromFormat('d/m/Y', $request->expiration[0])->addMonths($i);
                    $purchases_collect = PurchasesCollect::create([
                                            'purchase_id' => $purchase->id,
                                            'number'     => $i + 1,
                                            'expiration' => $expiration->format('d/m/Y'),
                                            'amount'     => cleartStringNumber($this->parse($monto)),
                                            'residue'    => cleartStringNumber($this->parse($monto))
                                        ]);
                }
            }
        }


        return $purchase;
	}

    private function parse($value)
    {
        return str_replace(',', '.',str_replace('.', '', $value));
    }
}
