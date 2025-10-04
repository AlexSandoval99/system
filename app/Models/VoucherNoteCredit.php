<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class VoucherNoteCredit extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_id', 'invoice_id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Voucher::class, 'invoice_id');
    }

}
