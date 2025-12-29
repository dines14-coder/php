<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class mail_send extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details=[])
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $send= $this->subject('CITPL Query'); 
        $send= $this->view('emails.form16_bulk_mail_send'); 
        // if($this->details['form16_file_url']!==""){
        //     $form16_array=explode(",",$this->details['form16_file_url']);
        //     foreach($form16_array as $form16){
        //         $send = $this->attach($form16);
        //     }
        // }
        return $send;
    }
}
