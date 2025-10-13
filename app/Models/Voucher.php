<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $fillable = [
        'date',
        'enterprise_id',
        'branch_id',
        'voucher_box_id',
        'voucher_condition',
        'voucher_number',
        'expiration',
        'client_id',
        'razon_social',
        'ruc',
        'phone',
        'address',
        'voucher_type',
        'observation',
        'amount',
        'total_excenta',
        'total_iva5',
        'total_iva10',
        'amount_iva5',
        'amount_iva10',
        'status',
        'user_id',
        'reason_canceled',
        'user_canceled',
        'voucher_fullnumber',
        'stamped_id',
    ];

    protected $casts = [
        'date' => 'date',
        'expiration' => 'date',
        'amount' => 'decimal:2',
        'total_excenta' => 'decimal:2',
        'total_iva5' => 'decimal:2',
        'total_iva10' => 'decimal:2',
        'amount_iva5' => 'decimal:2',
        'amount_iva10' => 'decimal:2',
        'status' => 'boolean',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->voucher_type == 1) {
                $voucher_box = VoucherBox::find($model->voucher_box_id);
                $branch = Branch::find($voucher_box->branch_id);

                $branch_voucher_number = $branch->id;

                $model->voucher_fullnumber =
                    str_pad($branch_voucher_number, 3, '0', STR_PAD_LEFT) . '-' .
                    str_pad($voucher_box->voucher_number, 3, '0', STR_PAD_LEFT) . '-' .
                    str_pad($model->voucher_number, 7, '0', STR_PAD_LEFT);
            }

            if ($model->voucher_type == 2) {
                $voucher_box = VoucherBox::find($model->voucher_box_id);
                $note_credit = Voucher::find($model->invoice_id);
                $branch = Branch::find($voucher_box->branch_id);

                $branch_voucher_number = $branch->id;

                $model->voucher_fullnumber =
                    str_pad($branch_voucher_number, 3, '0', STR_PAD_LEFT) . '-' .
                    str_pad($voucher_box->voucher_number, 3, '0', STR_PAD_LEFT) . '-' .
                    str_pad($model->voucher_number, 7, '0', STR_PAD_LEFT);

                $model->invoice_fullnumber = $note_credit->voucher_fullnumber ?? null;
            }

            if ($model->voucher_type == 3) {
                $voucher_box = VoucherBox::find($model->voucher_box_id);

                if ($voucher_box) {
                    $model->voucher_fullnumber =
                        str_pad($voucher_box->branch->id, 3, '0', STR_PAD_LEFT) . '-' .
                        str_pad($voucher_box->voucher_number, 3, '0', STR_PAD_LEFT) . '-' .
                        str_pad($model->voucher_number, 7, '0', STR_PAD_LEFT);
                } else {
                    $model->voucher_fullnumber =
                        str_pad($model->voucher_number, 7, '0', STR_PAD_LEFT);
                }
            }
        });
    }


    // Relaciones

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function voucherBox()
    {
        return $this->belongsTo(VoucherBox::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userCanceled()
    {
        return $this->belongsTo(User::class, 'user_canceled');
    }

    public function stamped()
    {
        return $this->belongsTo(Stamped::class);
    }

    public function voucher_details()
    {
        return $this->hasMany(VoucherDetail::class);
    }

    public function voucherCollects()
    {
        return $this->hasMany(VoucherCollect::class);
    }
}
