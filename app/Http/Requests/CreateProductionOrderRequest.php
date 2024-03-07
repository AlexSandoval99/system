<?php

namespace App\Http\Requests;

use App\Models\Proveedor;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductionOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator)
        {
            if(!request()->{"stage_id0"} || !request()->{"cantidad_controlada0"})
            {
                $validator->errors()->add('ruc', 'La primera Etapa no puede mandar vacio');
            }
            if(!request()->{"stage_id1"} || !request()->{"cantidad_controlada1"})
            {
                $validator->errors()->add('ruc', 'La segunda Etapa no puede mandar vacio');
            }
            if(!request()->{"stage_id2"} || !request()->{"cantidad_controlada2"})
            {
                $validator->errors()->add('ruc', 'La tercera Etapa no puede mandar vacio');
            }
        });
    }
}
