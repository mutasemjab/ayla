<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivableTransaction extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function receivable()
    {
        return $this->belongsTo(Receivable::class,);
    }
    
}
