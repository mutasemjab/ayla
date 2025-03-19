<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPackage extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'card_package_products')
                    ->withPivot('selling_price')
                    ->withTimestamps();
    }
}
