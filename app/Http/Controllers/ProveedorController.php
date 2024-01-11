<?php

namespace App\Http\Controllers;

use App\Exports\NombreExport;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProveedorRequest;
use App\Models\Provider;
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

    public function edit(Provider $provider_id)
    {
        return view('pages.proveedor.edit',compact('provider_id'));
    }

    public function store(CreateProveedorRequest $request)
    {
        DB::transaction(function() use ($request)
        {
            $proveedor = Provider::create([
                                        'name'       => $request->name,
                                        'ruc'        => $request->ruc,
                                        'address'     => $request->address,
                                        'phone'      => $request->phone]);
        });
        return redirect('provider');
    }

    public function update(Provider $provider_id)
    {
            $provider_id->update([
                                'name'       => request()->name,
                                'ruc'        => request()->ruc,
                                'address'     => request()->address,
                                'phone'      => request()->phone]);

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
        $providers = Provider::orderBy('name');

        if(request()->s)
        {
            $providers = $providers->where('name', 'like', '%'.request()->s.'%')->orwhere('ruc', 'like', '%'.request()->s.'%');
        }
        return $providers;
    }

    public function ajax_providers()
    {
        $purchases_providers = Provider::orderBy('name')
                                                ->where(function ($query)
                                                {
                                                    $query->Where('name', 'LIKE', '%' . request()->q . '%')
                                                          ->orWhere('ruc', 'LIKE', '%' . request()->q . '%');
                                                })
                                                ->where('status', true)
                                                ->get();
        $results = [];
        foreach ($purchases_providers as $key => $purchases_provider)
        {
            $results['items'][$key]['id']                  = $purchases_provider->id;
            $results['items'][$key]['text']                = $purchases_provider->name . '| Ruc : ' . $purchases_provider->ruc;
            $results['items'][$key]['name']                = $purchases_provider->name;
            $results['items'][$key]['ruc']                 = $purchases_provider->ruc;
            $results['items'][$key]['dv']                  = $purchases_provider->dv;
            $results['items'][$key]['address']             = $purchases_provider->address;
            $results['items'][$key]['phone']               = $purchases_provider->phone1.' '.$purchases_provider->phone2;
            $results['items'][$key]['type_iva']            = $purchases_provider->type_iva ? $purchases_provider->type_iva : 3;
            $results['items'][$key]['bank_account']        = $purchases_provider->bank ? ($purchases_provider->bank->name.' - '.$purchases_provider->bank_account) : '';
            // $results['items'][$key]['bank_id']             = $purchases_provider->bank_id;
            // $results['items'][$key]['bank_account_number'] = $purchases_provider->bank_account;
            // $results['items'][$key]['days_of_grace']       = $purchases_provider->days_of_grace;

            // Buscar el Ultimo Timbrado del Proveedor
            // $purchases_ringing = Purchase::whereIn('type', [1,4])
            //                                ->Active()
            //                                ->orderBy('id', 'desc')
            //                                ->where('purchases_provider_id', $purchases_provider->id)
            //                                ->limit(1)
            //                                ->first();
            // if($purchases_ringing)
            // {
            //     $results['items'][$key]['stamped']          = $purchases_ringing->stamped;
            //     $results['items'][$key]['stamped_validity'] = $purchases_ringing->stamped_validity->format('d/m/Y');
            // }else
            // {
            //     $results['items'][$key]['stamped']          = '';
            //     $results['items'][$key]['stamped_validity'] = '';
            // }
        }
        return response()->json($results);
    }

}
