<?php

namespace App\Http\Controllers;

use App\Exports\NombreExport;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProveedorRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ProveedorController extends Controller
{
    public function index()
    {
        $providers = $this->getProvider();
        $providers = $providers->paginate(20);
        return view('pages.proveedor.index' ,compact('providers'));
    } 

    public function create()
    {
        return view('pages.proveedor.create');
    }

    public function store(CreateProveedorRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $proveedor = Proveedor::create([
                                        'name'       => $request->name,
                                        'ruc'        => $request->ruc,
                                        'address'     => $request->address,
                                        'phone'      => $request->phone]);
        });
        return redirect('provider');
    }

    public function export_xls()
    {
        $providers = $this->getProvider()->get();
        $excelArray = [];
        $excelArray[] = [
            'Nombre',
            'RUC',
            'Direccion.',
            'Telefono',
        ];
    
        foreach ($providers as $provider) {
            $excelArray[] = [
                $provider->name,
                $provider->ruc,
                $provider->address,
                $provider->phone,
            ];
        }
    
        return Excel::download(new NombreExport(collect($excelArray)), 'Proveedores.xlsx');

    }

    private function getProvider()
    {
        $providers = Proveedor::orderBy('name');

        if(request()->s)
        {
            $providers = $providers->where('name', 'like', '%'.request()->s.'%')->orwhere('ruc', 'like', '%'.request()->s.'%');
        }
        return $providers;
    }

}
