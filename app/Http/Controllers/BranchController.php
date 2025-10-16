<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::paginate(20);

        return view('pages.branch.index',compact('branches'));
    }
    public function create()
    {
        return view('pages.branch.create');
    }
    public function store()
    {
        Branch::create([
            'name' => request()->name,
            'status' => 1
        ]);

        $this->flashMessage('check', 'La Sucursal fue registrado correctamente', 'success');

        return redirect()->route('branch');
    }
    public function edit(Branch $branches)
    {
        return view('pages.branch.edit',compact('branches'));
    }
    public function update(Branch $branches)
    {
            $branches->update([
                                'name'       => request()->name,
                            ]);
        $this->flashMessage('check', 'La Sucursal fue actualizado correctamente', 'success');
        return redirect('branch');
    }

    public function show(Branch $branches)
    {
        return view('pages.branch.show',compact('branches'));
    }
}
