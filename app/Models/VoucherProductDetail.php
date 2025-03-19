<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherProductDetail extends Model
{
    use HasFactory;

    protected $fillable = ['note_voucher_id', 'voucher_product_id', 'bin_number', 'serial_number', 'expiry_date','order_id'];

    // Define the inverse relationship with VoucherProduct
    public function voucherProduct()
    {
        return $this->belongsTo(VoucherProduct::class, 'voucher_product_id', 'id');
    }
   
    public function noteVoucher()
    {
        return $this->belongsTo(NoteVoucher::class, 'note_voucher_id', 'id');
    }
  
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
