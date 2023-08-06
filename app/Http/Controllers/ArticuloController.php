<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticuloRequest;
use App\Models\Articulo;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index()
    {  
        $articulos = Articulo::orderBy('name')->paginate(20);
        return view('pages.articulo.index' ,compact('articulos'));       
    } //

    public function create()
    {
        return view('pages.articulo.create'); 
    }

    public function store(CreateArticuloRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $articulo = Articulo::create([  
                                        'name'           => $request->name,
                                        'barcode'        => $request->barcode ]);
        });
        return redirect('articulo'); 
    }

    public function pdf(Articulo $articulo)
    {   
        return PDF::loadView('pages.articulo.pdf', compact('articulo'))                 
                    ->setPaper([0, 0, 250, 100], 'portrait')
                    // ->setPaper([0,0,300,300], 'portrait')
                    ->stream();
    }
}
