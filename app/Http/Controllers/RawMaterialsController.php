<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRawMaterialsRequest;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialsController extends Controller
{
    public function index()
    {
        $materiap = RawMaterial::where('status',1)->get();
        return view('pages.raw-materials.index',compact('materiap'));
    }

    public function create()
    {
        return view('pages.raw-materials.create');
    }

    public function store(CreateRawMaterialsRequest $request)
    {
        RawMaterial::create([
            'description' => request()->description,
            'status' => 1
        ]);

        $this->flashMessage('check', 'La materia prima fue registrado correctamente', 'success');

        return redirect()->route('raw-materials');
    }
}
