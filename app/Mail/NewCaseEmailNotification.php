<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class NewCaseEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $raiseDeti;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($raiseDeti=[])
    {
         $this->raiseDeti = $raiseDeti;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $send= $this->subject('Notification for New Cases moved to you!'); 
        $send= $this->view('emails.newcase_notification'); 
        return $send;
    }
}
