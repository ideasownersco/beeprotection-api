<?php

namespace App\Models;

class Transaction extends BaseModel
{
    protected $table = 'transactions';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }



}
