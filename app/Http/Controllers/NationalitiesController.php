<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNationalitiesRequest;
use App\Models\Nationality;
use Illuminate\Http\Request;

class NationalitiesController extends Controller
{
    public function index()
    {
        $nations = Nationality::where('status',1)->get();
        return view('pages.nationalities.index',compact('nations'));
    }

    public function create()
    {
        return view('pages.nationalities.create');
    }

    public function store(CreateNationalitiesRequest $request)
    {
        Nationality::create([
            'name' => request()->name,
            'status' => 1
        ]);

        $this->flashMessage('check', 'La Nacionalidad fue registrado correctamente', 'success');

        return redirect()->route('nationalities');
    }
}
