<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherProduct extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function voucherProductDetails()
    {
        return $this->hasMany(VoucherProductDetail::class, 'voucher_product_id', 'id');
    }

    public function noteVoucher()
    {
        return $this->belongsTo(NoteVoucher::class, 'note_voucher_id');
    }
    
     public function voucherProductDetailsForOrder($orderId)
    {
        return $this->hasMany(VoucherProductDetail::class)
                    ->where('order_id', $orderId);
    }
  

}
