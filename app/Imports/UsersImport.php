<?php

namespace App\Imports; 

use App\Models\emp_profile_tbl;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Validator;
use Illuminate\Validation\Rule;
use Mail;
use Maatwebsite\Excel\Concerns\WithValidation;
  
  
  
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

  
}


class UsersImport implements ToCollection, WithHeadingRow, WithValidation
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection  $rows)
    {
        $datas = $rows[0];

            foreach ($rows as $row) {
                if($row['f_f_document']=="Yes" || $row['f_f_document']=="yes"){
                    $f_f_document="Yes";
                    $f_f_c_s_g="1";
                    $cl_c_p="Fresh";
                    $fn_c_p="Fresh";
                    $pr_c_p="Fresh";
                    $hr_ld_c_p="Fresh";
                    $it_c_p="Fresh";
                    $it_inf_c_p="Fresh";
                }
                else{
                    $f_f_document="No";
                    $f_f_c_s_g ="";
                    $cl_c_p="";
                    $fn_c_p="";
                    $pr_c_p="";
                    $hr_ld_c_p="";
                    $it_c_p="";
                    $it_inf_c_p="";
                }

                emp_profile_tbl::create([
                    'emp_id'     => trim(str_replace(" ","", $row['emp_id'])),
                    'emp_name'    => $row['emp_name'], 
                    'pan_no'    => $row['pan_no'], 
                    'dob'    => date('Y-m-d', strtotime($row['dob'])), 
                    'mobileno'    => $row['mobileno'], 
                    'email'    => $row['email'], 
                    'type_of_leaving'    =>$row['type_of_leaving'], 
                    'last_working_date'    =>date('Y-m-d', strtotime($row['last_working_date'])),
                    'address'    => "", 
                    'state'    => "", 
                    'city'    => "",  
                    'otp'    => "", 
                    'password'    => "123456", 
                    'real_pass'    => "", 
                    'status'    => "Active", 
                    'doc_status'    => "Fresh", 
                    'ff_doc_updated_by'=>"",
                    's_doc_updated_by'=>"", 
                    'remark'    => "", 

                    'f_f_document'    => $f_f_document, 
                    'f_f_c_s_g'    =>$f_f_c_s_g, 
                    'cl_c_p'    => $cl_c_p, 
                    'fn_c_p'    => $fn_c_p, 
                    'pr_c_p'    => $pr_c_p, 
                    'hr_ld_c_p'    => $hr_ld_c_p, 
                    'it_c_p'    => $it_c_p, 
                    'it_inf_c_p'    => $it_inf_c_p, 
                ]);

                // send mail
        // To Master Mail 

       
            $company_email = $row['email'];
            // $company_email ="lakshminarayanan@hemas.in"; 

            $body_content1 = "Hello! ".$row['emp_name'];
            $body_content2 = "Welcome onboard to the Alumni Portal -  one stop shop for all your queries relating to your tenure at CITPL.";
            $body_content3 = 'Pls change the password as soon as you log in';
            $body_content4 = trim(str_replace(" ","", $row['emp_id'])); 
            $body_content5 = "123456"; 
            $body_content6 = "https://citpl_alumni.cavinkare.in/index.php/login"; 
            $body_content7 = "We wish you success in all your future endeavors"; 
            $body_content8 = "Good luck";
            $body_content9 = "Team HR"; 
    
            $details = [
                'subject' => 'CITPL',
                'body_content1' => $body_content1,
                'body_content2' => $body_content2,
                'body_content3' => $body_content3,
                'body_content4' => $body_content4, 
                'body_content5' => $body_content5, 
                'body_content6' => $body_content6, 
                'body_content7' => $body_content7, 
                'body_content8' => $body_content8, 
                'body_content9' => $body_content9, 
            ];

            // \Mail::to($company_email)->queue(new \App\Mail\Amb_Welcome($details));
                SendEmailJob::dispatch($company_email, $details);

            }
        
        
    }


    public function rules(): array
    {
        $dt = new \Carbon\Carbon();
        $before = $dt->subYears(18)->format('d-m-Y');
        return [
            '*.emp_id' => 'required|alpha_num',
            '*.email' => 'required|email:dns,rfc',
            '*.emp_name' => 'required|regex:/(^[A-Za-z\s]+$)+/u|max:33',
            '*.dob' => 'required||before:'.$before,
            '*.pan_no' => 'required|unique:emp_profile_tbls,pan_no|alpha_num|min:10|max:10|distinct',
            '*.type_of_leaving' => 'required|in:Relieved,Terminated,Abscond,relieved,terminated,abscond,Transferred,transferred',
            '*.last_working_date' => 'required',
            '*.mobileno' => 'required|unique:emp_profile_tbls,mobileno|numeric|digits:10|distinct',
            '*.f_f_document' => 'required|in:yes,Yes,YES,NO,No,no',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'f_f_document.in' => 'The f_f_document field contains only Yes/No.',
            'type_of_leaving.in' => 'The type_of_leaving field contains only Relieved/Terminated/Abscond/Transferred.',
        ];
    }
}
