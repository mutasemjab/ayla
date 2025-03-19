<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteVoucher extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }


    public function noteVoucherType()
    {
        return $this->belongsTo(NoteVoucherType::class, 'note_voucher_type_id');
    }

    public function voucherProducts()
    {
        return $this->hasMany(VoucherProduct::class, 'note_voucher_id', 'id');
    }
}
