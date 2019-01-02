<?php
namespace App\Models;

use Carbon\Carbon;

class CancelledJob extends BaseModel
{
    protected $table = 'cancelled_jobs';
    protected $fillable = ['driver_id','job_id'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}