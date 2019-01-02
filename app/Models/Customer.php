<?php

namespace App\Models;

class Customer extends BaseModel
{
    protected $table = 'customers';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
