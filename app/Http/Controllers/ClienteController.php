<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClienteRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {  
        $clientes = Client::orderBy('name')->paginate(20);
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
            $cliente = Client::create([  
                'name'           => $request->name,
                'apellido'       => $request->apellido,
                'direccion'             => $request->direccion,
                'ruc'          => $request->ruc,
                 ]);
        });
        return redirect('cliente'); 
    }
}


