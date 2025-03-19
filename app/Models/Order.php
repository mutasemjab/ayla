<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship to the User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function voucherProductDetails()
    {
        return $this->hasMany(VoucherProductDetail::class, 'order_id', 'id');
    }

    public function noteVouchers()
    {
        return $this->hasMany(NoteVoucher::class, 'order_id', 'id');
    }

    public function binNumber()
    {
        return $this->hasOne(VoucherProductDetail::class, 'order_id', 'id')
                    ->where('status', 1)
                    ->select(['order_id', 'bin_number']);
    }


}


