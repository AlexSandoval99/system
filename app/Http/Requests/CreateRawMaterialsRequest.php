<?php

namespace App\Http\Requests;

use App\Models\MateriaPrima;
use App\Models\RawMaterial;
use App\Models\RawMaterials;
use Illuminate\Foundation\Http\FormRequest;

class CreateRawMaterialsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator)
        {
            if(request()->description)
            {
                $material = RawMaterial::where('description',request()->description)->where('status',1)->first();
                if($material && request()->status == true)
                {
                    $validator->errors()->add('description', 'El la materia prima ya fue registrado');
                }
            }
        });
    }
}
