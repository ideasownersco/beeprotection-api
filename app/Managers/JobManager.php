<?php

namespace App\Managers;


use App\Models\Job;
use Carbon\Carbon;

class JobManager extends Job
{

    public function getWorkingJob()
    {
        $now = Carbon::now()->toDateTimeString();
        $minDate = Carbon::yesterday()->toDateTimeString();
        $job = $this->model
            ->ofStatus('working')
            ->where('accepted',1)
            ->whereDateBetween('started_working_at',$minDate,$now)
            ->whereNull('stopped_working_at')
            ->latest()
            ->limit(1)
        ;

        return $job;
    }


}