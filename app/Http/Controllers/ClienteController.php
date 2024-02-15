<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClienteRequest;
use App\Models\Ciudad;
use App\Models\Client;
use App\Models\Departamento;
use App\Models\Nationality;
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
        $departamentos = Departamento::pluck('departamento','id');
        return view('pages.cliente.create', compact('departamentos'));
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
                'document_number' => $request->document_number,
                'ciudades'       => $request->ciudades,
                'departamentos'=> $request->departamentos,
                'neighborhood'=> $request->neighborhood,
                'razon_social'=> $request->razon_social,
                'civil_status'=> $request->civil_status,
                'gender'=> $request->gender,
                'observation'=> $request->observation,
                 ]);
                 
        });
        return redirect('cliente'); 
    }

    public function show($clientes)
    {   
        $clientes = Client::find($clientes);
        $departamento = Departamento::all();
        $ciudades = Ciudad::whereDepartamento_id($clientes->departamento['id'])->get();
        
        return view('pages.clientes.create', compact(['cliente','departamentos','ciudadades']));
    }
    public function ajax_nationalities()
    {
        if(request()->ajax())
        {
            $nation = Nationality::where('nationalities_id',request()->nationalities_id)->get();
            $result = [];
            foreach ($nation as $key => $nations) 
            {
                $result[$key]['id'] = $nations->id;    
                $result[$key]['name'] = $nations->name;
            }
            return $result;
        }
    }
}



