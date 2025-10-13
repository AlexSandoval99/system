<?php

namespace App\Http\Requests;

use App\Models\CashBoxDetail;
use App\Models\CashBoxUser;
use Illuminate\Foundation\Http\FormRequest;

class CreateVoucherRequest extends FormRequest
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

    public function messages()
    {
        return [

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = auth()->id();
            // Buscar caja del usuario
            $cashBoxUser = CashBoxUser::where('user_id', $userId)
                            ->where('status', true)
                            ->first();

            if (!$cashBoxUser) {
                $validator->errors()->add('cash_box', 'No tiene una caja asignada o está inactiva.');
                return;
            }

            // Buscar apertura de caja del día
            $cajaAbierta = CashBoxDetail::where('cash_box_id', $cashBoxUser->cash_box_id)
                            ->where('cash_box_concept_id', 1) // 1 = apertura
                            ->whereDate('created_at', now()->format('Y-m-d'))
                            ->where('status', true)
                            ->first();

            if (!$cajaAbierta) {
                $validator->errors()->add('cash_box', 'Debe abrir una caja antes de registrar movimientos.');
            }
        });
    }

}
