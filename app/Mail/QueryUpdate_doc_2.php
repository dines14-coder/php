<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;


class QueryUpdate_doc_2 extends Mailable
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
        $send= $this->subject('HEPL Query'); 
        $send= $this->view('emails.query_update_doc_2'); 
        if($this->details['pay_slip_file_url']!==''){
            $payslip_array=explode(",",$this->details['pay_slip_file_url']);
            foreach($payslip_array as $pay_slip){
                $send= $this->attach($pay_slip);
            }
        }
        if($this->details['ff_statement_file_url']!==""){
            $send= $this->attach($this->details['ff_statement_file_url']);    
        }
        if($this->details['form16_file_url']!==""){
            $form16_array=explode(",",$this->details['form16_file_url']);
            foreach($form16_array as $form16){
                $send= $this->attach($form16);
            }
        }
        if($this->details['rel_letter_file_url']!==""){
            $send= $this->attach($this->details['rel_letter_file_url']);       
        }
        if($this->details['ser_letter_file_url']!==""){
            $send= $this->attach($this->details['ser_letter_file_url']);      
        }

        if($this->details['bonus_file_url']!==""){
            $send= $this->attach($this->details['bonus_file_url']);      
        }
        if($this->details['performance_incentive_file_url']!==""){
            $send= $this->attach($this->details['performance_incentive_file_url']);      
        }
        if($this->details['sales_travel_claim_file_url']!==""){
            $send= $this->attach($this->details['sales_travel_claim_file_url']);      
        }
        if($this->details['parental_medical_reimbursement_file_url']!==""){
            $send= $this->attach($this->details['parental_medical_reimbursement_file_url']);      
        }
        // t2
        if($this->details['pf_file_url']!==""){
            $send= $this->attach($this->details['pf_file_url']);      
        }
        if($this->details['gratuity_file_url']!==""){
            $send= $this->attach($this->details['gratuity_file_url']);      
        }
        // t2 end
        if($this->details['others_doc_file_url']!==""){
            $send= $this->attach($this->details['others_doc_file_url']);
        }
        return $send;      
    }
}
