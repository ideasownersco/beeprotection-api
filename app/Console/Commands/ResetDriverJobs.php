<?php

namespace App\Console\Commands;

use App\Models\Driver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDriverJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:resetjobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Driver Jobs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('drivers')->update(['job_count' => 0]);
        $this->info('Driver Job Count Resetted');
    }
}
