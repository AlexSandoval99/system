<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProveedorRequest;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $articulos = Proveedor::orderBy('name')->paginate(20);
        return view('pages.proveedor.index' ,compact('articulos'));
    } //

    public function create()
    {
        return view('pages.proveedor.create');
    }

    public function store(CreateProveedorRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $proveedor = Proveedor::create([
                                        'name'           => $request->name,
                                        'barcode'        => $request->barcode ]);
        });
        return redirect('articulo');
    }
}
