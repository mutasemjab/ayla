<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id');
    }

    public function receivableTransactions()
    {
        return $this->hasMany(ReceivableTransaction::class,);
    }
}
