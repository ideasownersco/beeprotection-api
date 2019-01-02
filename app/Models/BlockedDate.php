<?php

namespace App\Models;

use Carbon\Carbon;

class BlockedDate extends BaseModel
{
    protected $table = 'blocked_dates';
    protected $guarded = ['id'];
    protected $hidden = ['user_id'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}
