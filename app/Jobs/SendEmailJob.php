<?php

namespace App\Jobs;

use App\Mail\Amb_Welcome;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $companyEmail;
    protected $details;

    public function __construct($companyEmail, $details)
    {
        $this->companyEmail = $companyEmail;
        $this->details = $details;
    }

    public function handle()
    {
        try {
            Mail::to($this->companyEmail)->send(new Amb_Welcome($this->details));
            \Log::error('welcome send email to '.$this->companyEmail);

        } catch (Exception $e) {
            \Log::error('Failed to send welcome email to '.$this->companyEmail.': '.$e->getMessage());
            // Optional: Handle additional failure logic (e.g., notify admin)
        }
    }
}

