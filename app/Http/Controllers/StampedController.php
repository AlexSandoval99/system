<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stamped;
use App\Models\VoucherBox;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StampedController extends Controller
{
    public function index()
    {
        $stampeds = Stamped::where('status',1);
        $stampeds = $stampeds->paginate(20);
         return view('pages.stamped.index', compact('stampeds'));
    }

    public function create()
    {
        $stampeds  = Stamped::get('number','id');
        return view('pages.stamped.create', compact('stampeds'));
    }

    public function store(Request $request)
    {
        DB::transaction(function() use ($request, & $control)
        {
            $start = Carbon::createFromFormat('Y-m-d', $request->from);
            $end = Carbon::createFromFormat('Y-m-d', $request->end);

            Stamped::create([
                'number' => $request->number,
                'from_date' => $start,
                'until_date' => $end,
                'observation' => $request->observation,
                'user_id' => auth()->user()->id,
                'status' => 1,
            ]);

        });

        return redirect()->route('stampeds.index')->with('success', 'Timbrado registrada exitosamente.');

    }

    public function show(Stamped $stamped)
    {
        return view('pages.stamped.show', compact('stamped'));
    }

    public function edit($id)
    {
        $stamped = Stamped::findOrFail($id);
        return view('pages.stamped.edit', compact('stamped'));
    }

    public function update(Request $request, $id)
    {
        $stamped = Stamped::findOrFail($id);

        DB::transaction(function() use ($request, $stamped)
        {
            $start = Carbon::createFromFormat('Y-m-d', $request->from);
            $end = Carbon::createFromFormat('Y-m-d', $request->end);

            $stamped->update([
                'number' => $request->number,
                'from_date' => $start,
                'until_date' => $end,
                'observation' => $request->observation,
                'user_id' => auth()->user()->id,
                'status' => 1,
            ]);

        });

        return redirect()->route('stampeds.index')->with('success', 'Timbrado actualizada exitosamente.');
    }
}
