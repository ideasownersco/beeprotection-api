<?php

namespace App\Models;

use App\Events\StartTracking;

class JobCount extends BaseModel
{
    protected $table = 'job_counts';
    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}