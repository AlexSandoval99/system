<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stamped;
use Illuminate\Http\Request;

class StampedController extends Controller
{
    public function index()
    {
        $stampeds = Stamped::get();
        $stampeds = $stampeds->paginate(20);
         return view('pages.stamped.index', compact('stampeds'));
    }

    public function create()
    {
        $stampeds  = Stamped::get('number','id');
        $branches = Branch::pluck('name','id');
        return view('pages.voucher_box.create', compact('stampeds','branches'));
    }

    public function store(Request $request)
    {
        if(request()->ajax())
        {
            DB::transaction(function() use ($request, & $control)
            {
                $control = VoucherBox::create([
                    'date'                      => $request->date,
                    'status'                    => 1,
                    'client_id'                 => $request->client_id,
                    'branch_id'                 => $request->branch_id,
                    'user_id'                   => auth()->user()->id,
                ]);

            });

            return response()->json([
                'success'            => true,
            ]);
        }
        abort(404);
    }

    public function show(VoucherBox $voucher_box)
    {
        return view('pages.voucher_box.show', compact('voucher_box'));
    }
}
