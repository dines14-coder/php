<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchEmployeeData extends Command
{
    protected $signature = 'employee:fetch-data';
    protected $description = 'Fetch employee data from external API and store in database';

    public function handle()
    {
        $this->info('Employee data fetch functionality has been removed.');
        return 0;
    }
}