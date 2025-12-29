<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDataReport;
use App\Models\Employee;

class SendAccountDataReport extends Command
{
    protected $signature = 'report:account-data';
    protected $description = 'Send account data report via email';

    public function handle()
    {
        $employees = Employee::orderBy('last_synced_at', 'desc')->get();
        
        $accountData = [
            'total_employees' => $employees->count(),
            'active_employees' => $employees->where('status', 'Active')->count(),
            'inactive_employees' => $employees->where('status', 'Inactive')->count(),
            'employees' => $employees
        ];

        Mail::send(new AccountDataReport($accountData));

        $this->info('Account data report sent successfully');
    }
}