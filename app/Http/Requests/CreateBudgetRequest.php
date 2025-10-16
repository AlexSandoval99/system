<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBudgetRequest extends FormRequest
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
        $validator->after(function ($validator) {
            $data = $this->all();

            if (empty($data['provider_id']))
                {
                $validator->errors()->add('provider_id', 'El campo proveedor es obligatorio.');
            }

            if (isset($data['prices']) && is_array($data['prices']))
            {
                foreach ($data['prices'] as $key => $price)
                {
                    if ($price === null || $price === '' || !is_numeric($price))
                    {
                        $validator->errors()->add("prices.$key", "El precio del producto con ID $key es obligatorio.");
                    }
                }
            }
        });
    }
}
