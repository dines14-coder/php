<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\amb_document_tbl;

class AlumniFunctionalityController extends Controller
{
    public function query_doc_update(){
        $get = DB::table('query_tbls')->get();
        $ticket_id = array();
        foreach($get as $val){
            $ticket_id[] = $val->ticket_id;
            $doc[] = $val->document;
            $updated_by[] = $val->updated_by;
            $status[] = $val->status;
        }
        for($i=0 ; $i<count($ticket_id);$i++)
        {
            $doct = $doc[$i];
            $document = explode("," , $doct);

            $get_result =DB::table('query_tbls')
            ->join('query_document_tbls', 'query_tbls.ticket_id','=','query_document_tbls.ticket_id')
            ->where('query_tbls.ticket_id',$ticket_id[$i])
            // ->where('query_document_tbls.document',$document[$j])
            ->select('query_tbls.*')
            ->get();

            for($j=0 ;$j<count($document)-1;$j++){

                if (count($get_result) > 0) {
                    $result = DB::table('query_document_tbls')
                    ->where('ticket_id',$get_result[0]->ticket_id)
                    ->where('document',$document[$j])
                    ->update(['updated_by'=>$get_result[0]->updated_by , 'status' => $get_result[0]->status]);

                }else{
                    $result = DB::table('query_document_tbls')->insert(
                        array( 'ticket_id' => $ticket_id[$i] ,
                                'document' => $document[$j] ,
                                'file_name' => "",
                                'remark' => "",
                                'updated_by' => $updated_by[$i],
                                'status' => $status[$i],
                        )
                    );
                }
            }
        }
        return $result;
    }

    public function ck_alumni_with_lwd()
    {
        $get_result =DB::table('ck_alumni_with_lwd')
            ->join('emp_profile_tbls', 'ck_alumni_with_lwd.emp_id','=','emp_profile_tbls.emp_id')
            ->select('ck_alumni_with_lwd.*')
            ->get();

        foreach($get_result as $row){
            $emp_id[]=$row->emp_id;
            $last_working_date[]=$row->last_working_date;
        }
        for($i=0;$i<count($get_result);$i++){
        $data1= DB::table('emp_profile_tbls')->where('emp_id',$emp_id[$i])->update(['last_working_date'=> date("Y-m-d",strtotime($last_working_date[$i]))]); 
        }
        return response()->json(['response'=>'Success']);
    }


    //Bulk upload for Form 16

    public function upload_form16_doc(){

        // Employee ID Start
        $inputFileName = public_path("documents/form16.xlsx");
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $spreadsheet = $spreadsheet->getActiveSheet();
        $data_array =  $spreadsheet->toArray();
        $emp_id = array();
        foreach($data_array as $row){
            if(is_numeric($row[1])){
                $emp_id[] = $row[1];
            }
        }

        print_r($emp_id);      
        exit;
              

        // Employee ID End

        for($k=0;$k<count($emp_id);$k++){

            // check form 16 already uploaded or not
            $check_form16_exist = DB::table('amb_document_tbls')
            ->where('emp_id',$emp_id[$k])
            ->where('document',"Form 16")
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->get();

            // print_r($check_form16_exist);

            if(!isset($check_form16_exist[0])){
                $files=array('personal__'.$emp_id[$k].'__Part-A21-22.pdf','personal__'.$emp_id[$k].'__Part-B21-22.pdf');
                $doc_row=array();
                $m=0;
                $part = array('PART_A','PART_B');
                foreach ($files as $file1) {
                    $from_path = public_path('documents/'.$part[$m].'/'.$file1);
                    if(File::exists($from_path)){
                        // $path_info = pathinfo($from_path);
                        // $ext = $path_info['extension'];
                        $part_name = ($m == 0) ? 'Part A' : 'Part B';
                        $ah_name = 'form16_part_'.strtolower(str_replace(' ', '_', $part_name)).'_'.time().'_'.$emp_id[$k].'.pdf';
                        $to_path = public_path('documents/'.$emp_id[$k].'/form16'.'/'.$ah_name);
                        $path = public_path('documents/'.$emp_id[$k].'/form16');
                        if (File::exists($path)) {
                            $copied = File::copy($from_path, $to_path);
                        }else{
                            File::makeDirectory($path, 0777, true);
                            $copied = File::copy($from_path, $to_path);
                        }
                        $doc_row[]=[
                            'doc_type'=>'Form 16 '.$part_name,
                            'doc_name'=>$ah_name,
                        ];
                    }
                    // else{
                    //     $data['connection'] = $emp_id[$k];
                    //     $data['failed_at'] = date('Y-m-d H:i:s');
                    //     $employee_id = DB::table('failed_jobs')->where('connection',$emp_id[$k])->get();
                    //     if(!isset($employee_id[0])){
                    //         DB::table('failed_jobs')->insert($data);
                    //     }
                    // }
                    $m++;

                }
                $count=0;
                $string_file_type=array();
                $form16_file_url=array();
                while($count<count($doc_row)){
                    $check_isset_employee = DB::table('emp_profile_tbls')->where('emp_id',$emp_id[$k])->get();
                    if(isset($check_isset_employee[0])){
                        $querytbl = new amb_document_tbl();
                        $querytbl->emp_id = $emp_id[$k];
                        $querytbl->document = $doc_row[$count]['doc_type'];
                        $querytbl->file_name = $doc_row[$count]['doc_name'];
                        $querytbl->status = "Active";
                        $querytbl->save();

                        //update status and updated by in emp_profile_tbls table

                        $data1['ff_doc_updated_by'] = session()->get('emp_id');
                        $data1['doc_status'] = "Pending";
                        DB::table('emp_profile_tbls')->where('emp_id',$emp_id[$k])->update($data1);

                        //make mail
                        $string_file_type[] = $doc_row[$count]['doc_type'];
                        $file_name = $doc_row[$count]['doc_name'];
                        if($doc_row[$count]['doc_type'] == "Form 16"){
                            $path="form16";
                            $form16_file_name=$file_name;
                            $form16_file_url[]="documents/".$emp_id[$k]."/".$path."/".$file_name."";
                        }
                    }else{
                        $data['connection'] = $emp_id[$k];
                        $data['failed_at'] = date('Y-m-d H:i:s');
                        $employee_id = DB::table('failed_jobs')->where('connection',$emp_id[$k])->get();
                        if(!isset($employee_id[0])){
                            DB::table('failed_jobs')->insert($data);
                        }
                    }
                    $count++;
                }

                $final_form16_file_url=implode(",",$form16_file_url);
                $unique_file_type=array_unique($string_file_type);
                $all_submit_doc=implode(",",$unique_file_type); 


                $getempdetail = DB::table('emp_profile_tbls')->where('emp_id',$emp_id[$k])->get();
                // send query mail
                if(isset($getempdetail[0])){
                    $company_email = $getempdetail[0]->email;

                    $body_content1 = "Dear ".$getempdetail[0]->emp_name.',';
                    $body_content2 = "Form 16 for the financial year 21-22 is available in the Alumni Portal.";
                    $body_content3 = 'Pls download your form 16 from the Portal.';
                    $body_content4 = "https://citpl_alumni.cavinkare.in/index.php/login"; 
                    $body_content5 = "Cheers";
                    $body_content6 = "Team HR";
        
                    $details = [
                        'subject' => 'CITPL',
                        'title' => 'Your Documents - CITPL',
                        'body_content1' => $body_content1,
                        'body_content2' => $body_content2,
                        'body_content3' => $body_content3,
                        'body_content4' => $body_content4, 
                        'body_content5' => $body_content5, 
                        'body_content6' => $body_content6, 
                        'form16_file_url' => $final_form16_file_url, 
                        'all_submit_doc' => $all_submit_doc,
                    ];
        
                    // in proper laravel method mail send plz enable below link
                    // \Mail::to($company_email)->send(new \App\Mail\mail_send($details));
                }
            }

        }
        return ['response' => 'success'];
        
    }

    public function bulk_epm_upload()
    {
        $inputFileName = public_path("bulk_alumni.xlsx");
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $spreadsheet = $spreadsheet->getActiveSheet();
        $data_array =  $spreadsheet->toArray();
        $emp_id = array();
        $name = array();
        $pan = array();
        $lwd = array();
        $dob = array();
        $phone = array();
        $email = array();
        $tor = array();
        foreach($data_array as $row){
            if($row[1] !="" &&  $row[1] !="Employee Id"){
                $emp_id[] = $row[1];
            }
            if($row[2] !="" &&  $row[2] !="Full Name"){
                $name[] = $row[2];
            }
            if($row[3] !="" &&  $row[3] !="PAN Number"){
                $pan[] = $row[3];
            }
            if($row[4] !="" &&  $row[4] !="LWD"){
                $lwd[] = $row[4];
            }
            if($row[7] !="" &&  $row[7] !="DOB"){
                $dob[] = $row[7];
            }
            if($row[8] !="" &&  $row[8] !="MOBILE NUMBER"){
                $phone[] = $row[8];
            }
            if($row[9] !="" &&  $row[9] !="PERSONAL EMAIL ID"){
                $email[] = $row[9];
            }
            if($row[10] !="" &&  $row[10] !="TYPE OF LEAVING"){
                $tor[] = $row[10];
            }
        }

        for($i=0;$i<count($emp_id);$i++){
            $check =  DB::table('emp_profile_tbls')->where('emp_id',$emp_id[$i])->orwhere("email",$email[$i])->orwhere("pan_no",$pan[$i])->get();
            if(isset($check[0])){
                $data['connection'] = $emp_id[$i];
                $data['failed_at'] = date('Y-m-d H:i:s');
                DB::table('failed_jobs')->insert($data);
            }else{
                $data['emp_id'] = $emp_id[$i];
                $data['emp_name'] = $name[$i];
                $data['pan_no'] = $pan[$i];

                $d1 = $dob[$i];  
                $newDate1 = date("Y-m-d", strtotime($d1));  
                $data['dob'] = $newDate1;

                $data['mobileno'] = $phone[$i];
                $data['email'] = $email[$i];
                $data['type_of_leaving'] = $tor[$i];

                $d2 = $lwd[$i];  
                $newDate2 = date("Y-m-d", strtotime($d2));  
                $data['last_working_date'] = $newDate2;

                $data['f_f_document']="Yes";
                $data['f_f_c_s_g']="1";
                $data['cl_c_p']="Fresh";
                $data['fn_c_p']="Fresh";
                $data['pr_c_p']="Fresh";
                $data['hr_ld_c_p']="Fresh";
                $data['it_c_p']="Fresh";
                $data['it_inf_c_p']="Fresh";
                $data['doc_status']="Fresh";
                $data['ff_doc_updated_by']="";
                $data['s_doc_updated_by']="";
                $data['status']="Active";
                $data['password']=bcrypt("123456");
                $data['real_pass']="123456";
                DB::table('emp_profile_tbls')->insert($data);


                // send mail
        // To Master Mail 
        $company_email = $email[$i];
        // $company_email ="lakshminarayanan@hemas.in"; 

        $body_content1 = "Hello! ".$name[$i];
        $body_content2 = "Welcome onboard the Alumni Portal -  one stop shop for all your queries relating to your tenure at CITPL.";
        $body_content3 = 'Pls change the password as soon as you log in';
        $body_content4 = $email[$i]; 
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

        // in proper laravel method mail send plz enable below link
        // \Mail::to($company_email)->send(new \App\Mail\Amb_Welcome($details));
            }
        }

        return "success";



        
    }


    
}
