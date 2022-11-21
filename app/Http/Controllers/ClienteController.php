<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {  
        $clientes = Cliente::orderBy('name')->paginate(20);
        return view('pages.cliente.index' ,compact('clientes'));       
    } //

    public function create()
    {
        return view('pages.cliente.create'); 
    }

    public function store(CreateClienteRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $cliente = Cliente::create([  
                'name'           => $request->name,
                'apellido'       => $request->apellido,
                'ci'             => $request->ci,
                'phone'          => $request->phone,
                 ]);
        });
        return redirect('cliente'); 
    }
}


