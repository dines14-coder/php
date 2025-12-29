<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class checklistNotifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details1;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details1=[])
    {
        $this->details1 = $details1;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->subject('Notification for New Checklist Received!')
                ->view('emails.checklistNotifyMail');
    }
}
