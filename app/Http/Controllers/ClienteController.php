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
        $clientes = Client::orderBy('first_name')->paginate(20);
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
                'first_name'   => $request->name,
                'last_name'    => $request->apellido,
                'address'      => $request->address,
                'ruc'          => $request->ruc,
                'phone'          => $request->phone,
                 ]);
        });
        return redirect('cliente'); 
    }
}


