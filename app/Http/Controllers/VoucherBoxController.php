<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Stamped;
use App\Models\VoucherBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherBoxController extends Controller
{
    public function index()
    {
        $boxes = VoucherBox::orderBy('voucher_boxes.id', 'desc');
        $boxes = $boxes->paginate(20);
         return view('pages.voucher_box.index', compact('boxes'));
    }

    public function create()
    {
        $stampeds  = Stamped::pluck('number','id');
        $branches = Branch::pluck('name','id');
        return view('pages.voucher_box.create', compact('stampeds','branches'));
    }

    public function store(Request $request)
    {
        DB::transaction(function() use ($request, & $control)
        {
            $control = VoucherBox::create([
                'name'                  => $request->name,
                'voucher_number'        => $request->voucher_number,
                'branch_id'             => $request->branch_id,
                'from_invoice_number'   => $request->from_invoice_number,
                'until_invoice_number'  => $request->until_invoice_number,
                'user_id'               => 1,
                'stamped_id'            => $request->stamped_id,
            ]);

        });

        return response()->json([
            'success'            => true,
        ]);
    }

    public function show(VoucherBox $voucher_box)
    {
        return view('pages.voucher_box.show', compact('voucher_box'));
    }
}
