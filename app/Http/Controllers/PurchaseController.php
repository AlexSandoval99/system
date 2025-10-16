<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchasesRequest;
use App\Http\Requests\DeletePurchasesRequest;
use App\Http\Requests\UpdatePurchasesRequest;
use App\Jobs\AccountingMovementsJob;
use App\Models\AccountingEntry;
use App\Models\AccountingPlan;
use App\Models\Branch;
use App\Models\CalendarPayment;
use App\Models\CashBox;
use App\Models\CashBoxDetail;
use App\Models\CostCenter;
use App\Models\Currency;
use App\Models\Provider;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseNoteCredit;
use App\Models\PurchasesAccountingPlan;
use App\Models\PurchasesCollectPayment;
use App\Models\PurchasesCostCenter;
use App\Models\PurchasesDetail;
use App\Models\PurchasesNoteCredit;
use App\Models\PurchasesOrderDetail;
use App\Models\PurchasesProvider;
use App\Models\PurchasesCollect;
use App\Models\SocialReason;
use App\Services\PurchasesService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PurchaseController extends Controller
{
    public function index()
    {
        $providers = Provider::Filter();
        $purchases           = Purchase::with('provider')
            ->where('type', '!=', 5)
            ->orderBy('id', 'desc');
        if (request()->s)
        {
            $purchases = $purchases->where('razon_social', 'LIKE', '%' . request()->s . '%')
                ->orWhere('ruc', 'LIKE', '%' . request()->s . '%')
                ->orWhere('number', 'LIKE', '%' . request()->s . '%');
        }

        if (request()->invoice_copy)
        {
            $purchases = $purchases->where('invoice_copy', request()->invoice_copy);
        }

        if (request()->provider_id)
        {
            $purchases = $purchases->where('provider_id', request()->provider_id);
        }
        $purchases = $purchases->whereIn('status', [1,2,3])->paginate(20);

        return view('pages.purchase.index', compact('purchases', 'providers'));
    }

    public function create()
    {
        $branches          = Branch::GetAllCached()->sortBy('name')->pluck('name', 'id');
        $invoice_copy = config('constants.invoice_copy');
        unset($invoice_copy[0]);

        return view('pages.purchase.create', compact('branches', 'invoice_copy'));
    }

    public function store(CreatePurchasesRequest $request)
    {
        if (request()->ajax())
        {
            DB::transaction(function () use ($request, &$purchase)
            {
                $purchase = (New PurchasesService)->store($request, null, auth()->user()->id);

                toastr()->success('Agregado exitosamente');
            });

            return response()->json([
                'success'     => true,
                'purchase_id' => $purchase->id
            ]);
        }
        abort(404);
    }

    public function show(Purchase $purchase)
    {
        return view('pages.purchase.show', compact('purchase'));
    }

    public function pdf(Purchase $purchase)
    {
        return Pdf::loadView('pages.purchase.pdf', compact('purchase'))
            ->setPaper('A4', 'portrait')
            ->stream();
    }

    public function delete(Purchase $purchase)
    {
        return view('pages.purchase.delete', compact('purchase'));
    }

    public function invoice_download(Purchase $purchase)
    {
        $path = storage_path('app/invoices/' . $purchase->file);

        if (request()->show)
        {
            $file = File::get($path);
            $mime_type = File::mimeType($path);

            $response = response()->make($file, 200);

            $response->header('Content-Type', $mime_type);

            return $response;
        }

        return response()->download($path);
    }

    public function ajax_purchases_note_credits()
    {
        if (request()->ajax())
        {
            $purchases = Purchase::where('type', 1)
                ->where('number', request()->q)
                ->where('provider_id', request()->purchases_provider_id)
                ->whereIn('status', [1, 3])
                ->get();

            $results = ['items' => []];

            foreach ($purchases as $key => $purchase)
            {
                // Total acreditado por notas anteriores
                $total_nc = PurchaseNoteCredit::where('purchase_invoice_id', $purchase->id)
                    ->join('purchases', 'purchases.id', '=', 'purchase_note_credits.purchase_id')
                    ->sum('purchases.amount');

                $residue = max(0, $purchase->amount - $total_nc);

                // Cantidades devueltas por producto
                $products_nc = PurchaseDetail::whereIn('purchase_id',
                        PurchaseNoteCredit::where('purchase_invoice_id', $purchase->id)->pluck('purchase_id')
                    )
                    ->selectRaw('material_id, SUM(quantity) as quantity')
                    ->groupBy('material_id')
                    ->get()
                    ->keyBy('material_id');

                $results['items'][$key] = [
                    'id'        => $purchase->id,
                    'text'      => $purchase->number,
                    'total'     => $purchase->amount,
                    'residue'   => $residue,
                    'date'      => $purchase->date->format('d/m/Y'),
                    'condition' => config('constants.invoice_condition.' . $purchase->condition),
                    'products'  => []
                ];

                foreach ($purchase->purchase_details as $key2 => $detail_products)
                {
                    $devuelto = $products_nc[$detail_products->material_id]->quantity ?? 0;
                    $pendiente = max(0, $detail_products->quantity - $devuelto);

                    $results['items'][$key]['products'][$key2] = [
                        'id'       => $detail_products->material_id,
                        'name'     => $detail_products->material->description,
                        'quantity' => number_format($pendiente, 0, ',', '.'),
                        'amount'   => number_format($detail_products->amount, 0, ',', '.'),
                        'subtotal' => number_format($detail_products->amount * $pendiente, 0, ',', '.'),
                        'excenta'  => number_format($detail_products->excenta, 0, ',', '.'),
                        'iva5'     => number_format($detail_products->iva5, 0, ',', '.'),
                        'iva10'    => number_format($detail_products->iva10, 0, ',', '.'),
                    ];
                }
            }

            return response()->json($results);
        }

        abort(404);
    }


    public function searchProviderStamped()
    {
        $invoice_number = explode('-', request()->purchase_number);
        $invoice_number = $invoice_number[0] . '-' . $invoice_number[1];

        $purchase = Purchase::select("purchases.*", DB::raw("DATE_FORMAT(stamped_validity, '%d/%m/%Y') stamp_validity"))
            ->where('purchases_provider_id', request()->provider_id)
            ->where('number', 'like', ['%'.$invoice_number.'%'])
            ->whereIn('status', [1,3,4])
            ->orderBy('id', 'DESC')
            ->first();

        return  response()->json($purchase);
    }

    public function ajax_purchases_invoice()
    {
        if(request()->ajax())
        {
            $results = [];

            if(request()->purchase_id)
            {
                $purchase_note_credit = PurchasesNoteCredit::where('purchase_id',request()->purchase_id)->first();

                if($purchase_note_credit)
                {
                    $key = 0;
                    $results['head'][$key]['invoice_number']  = $purchase_note_credit->purchase_invoice->number;
                    $results['head'][$key]['date']            = $purchase_note_credit->purchase_invoice->date->format('d/m/Y');
                    $results['head'][$key]['amount']          = number_format($purchase_note_credit->purchase_invoice->amount,0,',','.');
                    $results['head'][$key]['condition']       = config('constants.invoice_condition.'.$purchase_note_credit->purchase_invoice->condition);
                    foreach($purchase_note_credit->purchase_invoice->purchases_details as $details)
                    {
                        $key++;
                        $results['details'][$key]['id']             = $details->id;
                        $results['details'][$key]['cod']             = $details->purchases_product_id;
                        $results['details'][$key]['product_name']    = $details->purchases_product->name;
                        $results['details'][$key]['description']     = $details->description;
                        $results['details'][$key]['accounting_plan'] = $details->accounting_plan ? $details->accounting_plan->fullname : '';
                        $results['details'][$key]['quantity']        = number_format($details->quantity, 0, ',', '.');
                        $results['details'][$key]['amount']          = number_format($details->amount, 2, ',', '.');
                        $results['details'][$key]['details']         = number_format($details->amount*$details->quantity, 2, ',', '.');
                        $results['details'][$key]['excenta']         = number_format($details->excenta, 2, ',', '.');
                        $results['details'][$key]['iva5']            = number_format($details->iva5, 2, ',', '.');
                        $results['details'][$key]['iva10']           = number_format($details->iva10, 0, ',', '.');
                    }
                }
            }
            return response()->json($results);
        }
        else
        {
            abort(404);
        }
    }
}
