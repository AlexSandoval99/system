<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCiudadRequest;
use App\Models\Ciudad;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index()
    {  
        $ciudas = Ciudad::orderBy('name')->paginate(20);
        return view('pages.ciudad.index' ,compact('ciudad'));       
    } //

    public function create()
    {
        return view('pages.ciudad.create'); 
    }

    public function store(CreateCiudadRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $ciudas = Ciudad::create([  
                                        'name'  => $request->name ]);
        });
        return redirect('ciudad'); 
    }

    public function pdf(Ciudad $ciudas)
    {   
        return PDF::loadView('pages.ciudad.pdf', compact(''))                 
                    ->setPaper([0, 0, 250, 100], 'portrait')
                    // ->setPaper([0,0,300,300], 'portrait')
                    ->stream();
    }
}
