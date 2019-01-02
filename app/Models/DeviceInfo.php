<?php

namespace App\Models;

class DeviceInfo extends BaseModel
{
    protected $table = 'device_info';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
