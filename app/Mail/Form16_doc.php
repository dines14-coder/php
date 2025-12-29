<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;


class Form16_doc extends Mailable
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
        //
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $send = $this->subject($this->details['subject']);
        $send = $this->view('emails.form16_doc'); 
        $send = $this->from('noreply@example.com', 'Alumni');
        
        if($this->details['form16_file_url'] !== ""){
            // Handle multiple Form 16 files (similar to QueryUpdate_doc)
            $form16_array = explode(",", $this->details['form16_file_url']);
            foreach($form16_array as $form16_file){
                $send = $this->attach($form16_file);
            }
        }
        
        return $send;      
    }
}