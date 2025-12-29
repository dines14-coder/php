<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\IEmpRepository;
use App\Repositories\IDocRepository;
use DataTables;
use Image;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;

class DocumentController extends Controller
{
    //
    public function __construct( IEmpRepository $emp_task,IDocRepository  $doc_task ) {
        $this->middleware( 'adminLog' );
        $this->emp_task = $emp_task;
        $this->doc_task = $doc_task;
    }
    public function document_manage_landing()  
    {
        return view('Admin.document_manage_landing');
    }

    public function get_admin_alumni_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : ('');

            if($start_date || $end_date ){
                if(session()->get('user_type')=="F_F_HR"){
                    $filter_data = [ 
                        'start_date' => $start_date,
                        'end_date'=> $end_date,
                        'doc_status_col'=> 'doc_status',
                        'doc_status'=> $type,
                    ];
                }
                $getquerydetails = $this->doc_task->get_ambassador($filter_data);
            }
            else{
                if(session()->get('user_type')=="F_F_HR"){
                    $filter_data = [  
                        'doc_status_col'=> 'doc_status',
                        'doc_status'=> $type, 
                    ];
                }
                else if(session()->get('user_type')=="HR-LEAD"){
                    $filter_data = [  
                        'doc_status_col'=> 'HR-LEAD', 
                        'doc_status'=> $type, 
                    ];
                }
                $getquerydetails = $this->doc_task->get_ambassador_default($filter_data);
            }


            // print_r($getquerydetails);
            // exit();

            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use($type){

                        if($type=="Fresh"){
                            $document_string="Pay Slips,F&F Statement,Form 16,Relieving Letter,Service Letter,Bonus,Performance Incentive,Sales Travel claim,Parental medical reimbursement,F&F Document,PF,Gratuity";
                            $btn_style="";
                            $icon="";
                            if($row->f_f_document=="Yes"){
                                if($row->cl_c_p=="Completed" && $row->fn_c_p=="Completed" && $row->pr_c_p=="Completed" && $row->hr_ld_c_p=="Completed" && $row->it_c_p=="Completed" && $row->it_inf_c_p=="Completed"){
                                    $btn_style="";
                                    $icon='<i class="fas fa-upload"></i>';
                                    $class='btn-success';
                                }
                                else{
                                    $btn_style="cursor: not-allowed;    pointer-events: none;background-color: #e05956;";
                                    $icon='<i class="fa fa-times-circle"></i>';
                                    $class='btn-danger';
                                }
                                
                            }
                            else{
                                $btn_style="";
                                $class="btn-success";
                                $icon='<i class="fas fa-upload"></i>';
                            }

                            $action ="";
                            $action.= '<a href="#" style="'.$btn_style.'" class="btn btn-sm btn-icon '.$class.' mr-1" data-toggle="tooltip" data-placement="bottom" data-toggle="tooltip" data-placement="bottom" title="Document Upload" onclick="upload_document('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$document_string."'".','."'".$type."'".');">'.$icon.'</a>';
                            if($row->f_f_document == "Yes"){
                                $action.= '<a  href="#" data-controls-modal="f_and_f_document_popup" data-backdrop="static" data-keyboard="false" class="btn btn-sm btn-icon btn-primary " data-toggle="tooltip" data-placement="bottom" title="F&F Check Points PDF"  onclick="f_and_f_document_popup('."'".$row->emp_id."'".');"><i class="fas fa-flag"></i></a>';
                            }
                        }
                        if($type=="Pending"){

                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );

                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){
                                $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                            $count_i++;
                            }
                            $unique_doc_array_1=array_unique($pre_doc_uploaded);

                            $unique_doc_array=array_values($unique_doc_array_1);

                            if(session()->get('user_type')=="F_F_HR"){
                                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","F&F Document","Parental medical reimbursement","PF","Gratuity"); 
                            }
                            else if(session()->get('user_type')=="HR-LEAD"){
                                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","F&F Document","PF","Gratuity","Parental medical reimbursement"); 
                            }
                            

                            $check_diff_array=array_diff($marks,$unique_doc_array);
                            
                            $check_diff=array(); 
                            foreach ($check_diff_array as $a) {
                                $check_diff[]= $a;
                            } 

                            $document_string=implode(",",$check_diff);

                            $action="";
                            $action.= '<a href="#" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" data-placement="bottom" data-toggle="tooltip" data-placement="bottom" data-toggle="tooltip" data-placement="bottom" title="Document Upload" onclick="upload_document('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$document_string."'".','."'".$type."'".');"><i class="fas fa-upload"></i></a>';
                            if($row->f_f_document == "Yes"){
                                $action.= '<a  href="#" data-controls-modal="f_and_f_document_popup" data-backdrop="static" data-keyboard="false" class="btn btn-icon btn-sm btn-primary float-right" data-toggle="tooltip" data-placement="bottom" title="F&F Check Points PDF"  onclick="f_and_f_document_popup('."'".$row->emp_id."'".');"><i class="fas fa-flag"></i></a>';
                            }
                        }
                        if($type=="Completed"){
                            $action ="";
                            $action.= '<a href="#" class="btn btn-sm btn-icon btn-success mr-1" title="Document Details" onclick="doc_detail('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$row->remark_2."'".');"><i class="fas fa-eye"></i></a>';
                            if($row->f_f_document == "Yes"){
                                $action.= '<a  href="#" data-controls-modal="f_and_f_document_popup" data-backdrop="static" data-keyboard="false" class="btn btn-sm btn-icon btn-primary  " data-toggle="tooltip" data-placement="bottom" title="F&F Check Points PDF"  onclick="f_and_f_document_popup('."'".$row->emp_id."'".');"><i class="fas fa-flag"></i></a>';
                            }
                        }
                        return $action;
                    })
                    ->addColumn('remark', function($row) use($type){

                        $remark = $row->remark_2;
                        
                        return $remark;
                    })
                   
                    ->addColumn('document_div', function($row) use($type){
                        $document_div=''; 
                        
                        if($type=="Fresh"){

                            if(session()->get('user_type')=="F_F_HR"){
                                $document_div.='<div class="badge badge-primary doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">F&F Document</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Gratuity</div><br>';
                            }
                            else if(session()->get('user_type')=="HR-LEAD"){
                                $document_div.='<div class="badge badge-primary doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">F&F Document</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Gratuity</div><br>';
                            }

                        }
                        if($type=="Pending"){

                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );

                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){

                                if(session()->get('user_type')=="F_F_HR"){
                                    $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","F&F Document","Parental medical reimbursement","PF","Gratuity");
                                    if(in_array($get_doc_rows[$count_i]['document'],$marks)==true)
                                    {
                                        $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                                    }
                                }
                                else if(session()->get('user_type')=="HR-LEAD"){
                                    $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","F&F Document","Parental medical reimbursement","PF","Gratuity");
                                    if(in_array($get_doc_rows[$count_i]['document'],$marks)==true)
                                    {
                                        $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                                    }
                                }
                                
                            $count_i++;
                            }
                            $unique_doc_array_1=array_unique($pre_doc_uploaded);

                            $unique_doc_array=array_values($unique_doc_array_1);

                            if(session()->get('user_type')=="F_F_HR"){
                                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","F&F Document","Parental medical reimbursement","PF","Gratuity"); 
                            }
                            else if(session()->get('user_type')=="HR-LEAD"){
                                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","F&F Document","Parental medical reimbursement","PF","Gratuity"); 
                            }
                            

                            $check_diff_array=array_diff($marks,$unique_doc_array);
                            $check_diff=array();
                            foreach ($check_diff_array as $a) {
                                $check_diff[]= $a;
                            } 
                            
                            $count_j=0;
                            while($count_j<count($unique_doc_array)){
                                $document_div.='<div class="badge badge-success doc_name">'.$unique_doc_array[$count_j].'</div><br>';
                            $count_j++;
                            }

                            $count_k=0;
                            while($count_k<count($check_diff)){
                                $document_div.='<div class="badge badge-danger doc_name">'.$check_diff[$count_k].'</div><br>';
                            $count_k++;
                            }
                        }
                        if($type=="Completed"){ 

                            if(session()->get('user_type')=="F_F_HR"){
                                $document_div.='<div class="badge badge-success doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">F&F Document</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Gratuity</div><br>';
                            }
                            
                            else if(session()->get('user_type')=="HR-LEAD"){
                                $document_div.='<div class="badge badge-success doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">F&F Document</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Gratuity</div><br>';
                            }


                        }

                        return $document_div;
                    })
                    ->addColumn('status', function($row){

                        if(session()->get('user_type')=="F_F_HR"  || session()->get('user_type')=="HR-LEAD"){
                            if($row->doc_status=="Fresh"){
                                $sts_clr='warning';
                            }
                            else if($row->doc_status=="Pending"){
                                $sts_clr='primary';
                            }
                            else if($row->doc_status=="Completed"){
                                $sts_clr='success';
                            }
                            $status=$row->doc_status;
                        }
                        

                        
                        $status_btn = '<div class="badge badge-'.$sts_clr.'">'.$status.'</div>';
                        return $status_btn;
                    })
                    ->addColumn('type_of_leaving', function($row){
                        if(!$row->type_of_leaving== ""|| !$row->type_of_leaving== null){
                            $type_of_leaving = '';
                            if($row->type_of_leaving == "Abscond" || $row->type_of_leaving == "Terminated"){
                                $type_of_leaving.='<div class="badge badge-danger doc_name">'.$row->type_of_leaving.'</div><br>';
                            }elseif($row->type_of_leaving == "Transferred"){
                                $type_of_leaving.='<div class="badge badge-primary doc_name">'.$row->type_of_leaving.'</div><br>';
                            }
                            else{
                                $type_of_leaving.='<div class="badge badge-success doc_name">'.$row->type_of_leaving.'</div><br>';
                            }
                            return $type_of_leaving;
                        }
                        else{
                            return "-----";
                        }
                    })
                    ->addColumn('created_at', function($row){
                        $created_at=date('d-m-Y', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->rawColumns(['action','remark','type_of_leaving','document_div','status','created_at'])
                    ->make(true);
        }
        return view(' Admin.document_manage_landing ');
    }


    public function alumni_doc_upload_admin_submit(Request $request)
    { 
        set_time_limit(5000);
        $emp_id = $request->input('emp_id');
        $document_pop=$request->input('pop_document');
        $remark=$request->input('remark');
        $doc_arr=explode(",",$document_pop);

        $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","F&F Document","PF","Gratuity","Others"); 
        $i=0; 

        $doc_row=array();

        while($i<count($doc_arr)){
            if (in_array($doc_arr[$i], $marks)==true)
            {

                if($doc_arr[$i]=="Pay Slips"){ 

                    $files = $request->file('pay_slip');

                    if(is_array($files)){
                        $ps_count=count($files);
                        if($request->hasfile('pay_slip')){
                            $p_s_c=0;
                            foreach ($files as $file) {

                                $ah_name = 'payslip'.time().'_'.$p_s_c.'.'.$file->extension();
                                $file->move(public_path().'/documents/'.$emp_id.'/pay_slip', $ah_name); 
                                
                                $doc_row[]=[
                                    'doc_type'=>'Pay Slips',
                                    'doc_name'=>$ah_name,
                                ];
                                $p_s_c++;
                            }
                        }

                    }
                    
                }
                if($doc_arr[$i]=="F&F Statement"){
                    if($request->hasfile('ff_statement')){
                        $ah_name = 'ff_statement'.time().'.'.$request->file('ff_statement')->extension();
                        $request->file('ff_statement')->move(public_path().'/documents/'.$emp_id.'/ff_statement', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'F&F Statement',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                if($doc_arr[$i]=="Form 16"){ 
                    $files = $request->file('form16');
                    if(is_array($files)){
                        $ps_count=count($files);
                        if($request->hasfile('form16')){
                            $p_s_c=0;
                            foreach ($files as $file) {
                                $ah_name = 'form16'.time().'_'.$p_s_c.'.'.$file->extension();
                                $file->move(public_path().'/documents/'.$emp_id.'/form16', $ah_name); 
                                $doc_row[]=[
                                    'doc_type'=>'Form 16',
                                    'doc_name'=>$ah_name,
                                ];
                                $p_s_c++;
                            }
                        }
                    }
                }
                if($doc_arr[$i]=="Relieving Letter"){
                    if($request->hasfile('rel_letter')){
                        $ah_name = 'rel_letter'.time().'.'.$request->file('rel_letter')->extension();
                        $request->file('rel_letter')->move(public_path().'/documents/'.$emp_id.'/rel_letter', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Relieving Letter',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                if($doc_arr[$i]=="Service Letter"){
                    if($request->hasfile('ser_letter')){
                        $ah_name = 'ser_letter'.time().'.'.$request->file('ser_letter')->extension();
                        $request->file('ser_letter')->move(public_path().'/documents/'.$emp_id.'/ser_letter', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Service Letter',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }

                if($doc_arr[$i]=="Bonus"){
                    if($request->hasfile('bonus')){
                        $ah_name = 'bonus'.time().'.'.$request->file('bonus')->extension();
                        $request->file('bonus')->move(public_path().'/documents/'.$emp_id.'/bonus', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Bonus',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                if($doc_arr[$i]=="Performance Incentive"){
                    if($request->hasfile('performance_incentive')){
                        $ah_name = 'performance_incentive'.time().'.'.$request->file('performance_incentive')->extension();
                        $request->file('performance_incentive')->move(public_path().'/documents/'.$emp_id.'/performance_incentive', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Performance Incentive',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                if($doc_arr[$i]=="Sales Travel claim"){
                    if($request->hasfile('sales_travel_claim')){
                        $ah_name = 'sales_travel_claim'.time().'.'.$request->file('sales_travel_claim')->extension();
                        $request->file('sales_travel_claim')->move(public_path().'/documents/'.$emp_id.'/sales_travel_claim', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Sales Travel claim',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                if($doc_arr[$i]=="Parental medical reimbursement"){
                    if($request->hasfile('parental_medical_reimbursement')){
                        $ah_name = 'parental_medical_reimbursement'.time().'.'.$request->file('parental_medical_reimbursement')->extension();
                        $request->file('parental_medical_reimbursement')->move(public_path().'/documents/'.$emp_id.'/parental_medical_reimbursement', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Parental medical reimbursement',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }

                // bunus file from hr
                
                if($doc_arr[$i]=="F&F Document"){
                    if($request->hasfile('f_and_f_document')){
                        $ah_name = 'f_and_f_document'.time().'.'.$request->file('f_and_f_document')->extension();
                        $request->file('f_and_f_document')->move(public_path().'/documents/'.$emp_id.'/f_and_f_document', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'F&F Document',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                // type 2
                if($doc_arr[$i]=="PF"){
                    if($request->hasfile('pf')){
                        $ah_name = 'pf'.time().'.'.$request->file('pf')->extension();
                        $request->file('pf')->move(public_path().'/documents/'.$emp_id.'/pf', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'PF',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                if($doc_arr[$i]=="Gratuity"){
                    if($request->hasfile('gratuity')){
                        $ah_name = 'gratuity'.time().'.'.$request->file('gratuity')->extension();
                        $request->file('gratuity')->move(public_path().'/documents/'.$emp_id.'/gratuity', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Gratuity',
                            'doc_name'=>$ah_name,
                        ];
                    }
                }
                // end type 2

            }
            $i++;
        }

        $count=0;
        $mail_doc_div='';
        $pay_slip_file_url="";
            $ff_statement_file_url="";
            $form16_file_url="";
            $rel_letter_file_url="";
            $ser_letter_file_url="";

            $bonus_file_url="";
            $performance_incentive_file_url="";
            $sales_travel_claim_file_url="";
            $parental_medical_reimbursement_file_url="";
            // bonus form from hr
            $f_and_f_file_url="";
            // t2
            $pf_file_url="";
            $gratuity_file_url="";
            // t2 end

            $string_file_type=array();
            $pay_slip_file_url=array();
            $form16_file_url=array();


        while($count<count($doc_row)){
            $credentials=[
                'emp_id'=>$emp_id, 
                'document'=>$doc_row[$count]['doc_type'],
                'file_name'=>$doc_row[$count]['doc_name'],
                'status'=>"Active",
            ];
            // save query document row
            $saved_query_ticket_id = $this->doc_task->DocumentEntry( $credentials );

            // make mail doc

            $string_file_type[]=$credentials['document'];
            

            $file_name=$credentials['file_name'];
            if($credentials['document']=="Pay Slips"){
                $path="pay_slip";
                $pay_slip_file_name=$file_name;
                $pay_slip_file_url[]="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="F&F Statement"){
                $path="ff_statement";
                $ff_statement_file_name=$file_name;
                $ff_statement_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Form 16"){
                $path="form16";
                $form16_file_name=$file_name;
                $form16_file_url[]="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Relieving Letter"){
                $path="rel_letter";
                $rel_letter_file_name=$file_name;
                $rel_letter_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Service Letter"){
                $path="ser_letter";
                $ser_letter_file_name=$file_name;
                $ser_letter_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }

            if($credentials['document']=="Bonus"){
                $path="bonus";
                $bonus_file_name=$file_name;
                $bonus_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Performance Incentive"){
                $path="performance_incentive";
                $performance_incentive_file_name=$file_name;
                $performance_incentive_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Sales Travel claim"){
                $path="sales_travel_claim";
                $sales_travel_claim_file_name=$file_name;
                $sales_travel_claim_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Parental medical reimbursement"){
                $path="parental_medical_reimbursement";
                $parental_medical_reimbursement_file_name=$file_name;
                $parental_medical_reimbursement_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            // bonus form from hr
            if($credentials['document']=="F&F Document"){
                $path="f_and_f_document";
                $f_and_f_file_name=$file_name;
                $f_and_f_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            // type2
            if($credentials['document']=="PF"){
                $path="pf";
                $pf_file_name=$file_name;
                $pf_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            if($credentials['document']=="Gratuity"){
                $path="gratuity";
                $gratuity_file_name=$file_name;
                $gratuity_file_url="documents/".$emp_id."/".$path."/".$file_name."";
            }
            // end type2
            // make mail doc end

        $count++;
        }

        $unique_file_type=array_unique($string_file_type);
        // $unique_file_type=array_values($unique_file_type_1);

        $all_submit_doc=implode(",",$unique_file_type); 

        $final_pay_slip_file_url=implode(",",$pay_slip_file_url);
        $final_form16_file_url=implode(",",$form16_file_url);


        // update query status to completed 
        $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$emp_id );

        if(isset($get_doc_rows[0]))
        {

            $pre_doc_uploaded=array();
            $count_i=0;
            while($count_i<count($get_doc_rows)){
                $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
            $count_i++;
            }
            $unique_doc_array=array_unique($pre_doc_uploaded);

            // $unique_doc_array=array_values($unique_doc_array_1);


            if(session()->get('user_type')=="F_F_HR"){
                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity"); 
            }
            
            
            $check_diff_array=array_diff($marks,$unique_doc_array);
            $check_diff=array();
            foreach ($check_diff_array as $a) {
                $check_diff[]= $a;
            } 
            if(isset($check_diff[0]) ){
                $doc_status="Pending";
            }
            else{
                $doc_status="Completed";
            }
        }
        else{
            $doc_status="Pending";
        }


        if(session()->get('user_type')=="F_F_HR" || session()->get('user_type')=="HR-LEAD"){
            $credentials=[
                'emp_id'=>$emp_id, 
                'doc_status'=>$doc_status,
                'remark'=>$remark,
                'ff_doc_updated_by'=>session()->get('emp_id'),
            ];
            $update_query = $this->emp_task->update_docstatus_and_rem( $credentials );
        }
        

        

        // send mail to employee
        $getempdetail = $this->emp_task->get_employee_detail( $emp_id );
        // send query mail
        // To Master Mail 
        $company_email = $getempdetail[0]->email;
        // $company_email ="lakshminarayanan@hemas.in"; 

        $body_content1 = "Hello! ".$getempdetail[0]->emp_name;
        $body_content2 = "Please find attached your";
        $body_content3 = 'Pls raise a query in your login if you need further assistance,';
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
            'pay_slip_file_url' => $final_pay_slip_file_url, 
            'ff_statement_file_url' => $ff_statement_file_url, 
            'form16_file_url' => $final_form16_file_url, 
            'rel_letter_file_url' => $rel_letter_file_url, 
            'ser_letter_file_url' => $ser_letter_file_url,

            'bonus_file_url' => $bonus_file_url, 
            'performance_incentive_file_url' => $performance_incentive_file_url, 
            'sales_travel_claim_file_url' => $sales_travel_claim_file_url, 
            'parental_medical_reimbursement_file_url' => $parental_medical_reimbursement_file_url, 

            // bonus form
            'f_and_f_file_url' => $f_and_f_file_url, 

            // t2
            'pf_file_url' => $pf_file_url, 
            'gratuity_file_url' => $gratuity_file_url, 
            // t2 end

            'others_doc_file_url' => '', 
            'all_submit_doc' => $all_submit_doc,
            
        ];

        // in proper laravel method mail send plz enable below link
        \Mail::to($company_email)->send(new \App\Mail\QueryUpdate_doc($details));

        $response= "success";
        return response()->json( ['response' => $response,'sed_mail_to'=>$company_email]);

       
    }

    public function send_doc_mail(Request $request) 
    {
        
        // send mail to employee
        $emp_id=$request->input('emp_id');
        $pay_slip_file_url=$request->input('pay_slip_file_url');
        $ff_statement_file_url=$request->input('ff_statement_file_url');
        $form16_file_url=$request->input('form16_file_url');
        $rel_letter_file_url=$request->input('rel_letter_file_url');
        $ser_letter_file_url=$request->input('ser_letter_file_url');

        $bonus_file_url=$request->input('bonus_file_url');
        $performance_incentive_file_url=$request->input('performance_incentive_file_url');
        $sales_travel_claim_file_url=$request->input('sales_travel_claim_file_url');
        $parental_medical_reimbursement_file_url=$request->input('parental_medical_reimbursement_file_url');
        // bonus 
        $f_and_f_file_url=$request->input('f_and_f_file_url');
        // t2 
        $pf_file_url=$request->input('pf_file_url');
        $gratuity_file_url=$request->input('gratuity_file_url');
        // t2 end
        
        $getempdetail = $this->emp_task->get_employee_detail( $emp_id );
        // send query mail
        // To Master Mail 
        $company_email = $getempdetail[0]->email;
        // $company_email ="lakshminarayanan@hemas.in"; 

        $body_content1 = "Dear ".$getempdetail[0]->emp_name;
        $body_content2 = "We are happy to Shared your Documents";
        $body_content3 = 'Please find attached Documents.';
        $body_content4 = "Have any queries please contact our support Team."; 
        $body_content5 = "Support Number : 9087428914"; 

        $details = [
            'subject' => 'CITPL',
            'title' => 'Your Documents - CITPL',
            'body_content1' => $body_content1,
            'body_content2' => $body_content2,
            'body_content3' => $body_content3,
            'body_content4' => $body_content4, 
            'body_content5' => $body_content5, 
            'pay_slip_file_url' => $pay_slip_file_url, 
            'ff_statement_file_url' => $ff_statement_file_url, 
            'form16_file_url' => $form16_file_url, 
            'rel_letter_file_url' => $rel_letter_file_url, 
            'ser_letter_file_url' => $ser_letter_file_url, 

            'bonus_file_url' => $bonus_file_url, 
            'performance_incentive_file_url' => $performance_incentive_file_url, 
            'sales_travel_claim_file_url' => $sales_travel_claim_file_url, 
            'parental_medical_reimbursement_file_url' => $parental_medical_reimbursement_file_url, 
        

            // bonus
            'f_and_f_file_url' => $f_and_f_file_url, 
            // t2
            'pf_file_url' => $pf_file_url, 
            'gratuity_file_url' => $gratuity_file_url, 
            // t2 end
            
            'others_doc_file_url' => '', 

        ];
        // in proper laravel method mail send plz enable below link
        \Mail::to($company_email)->send(new \App\Mail\QueryUpdate_doc($details));

        // send mail to employee end
        $response= "success";
        return response()->json( ['response' => $response,'sent_mail_to'=>$company_email]);
    }

    public function alumni_doc_updated_detail(Request $request)
    {
        $credentials=[
            'emp_id'=>$request->input('emp_id'),
        ];
        $update_query_doc = $this->doc_task->get_updated_doc_detail( $credentials );

        $show_div="";
        foreach ($update_query_doc as $key => $get_query) {
            if($get_query->document=="Pay Slips"){
                $path="pay_slip";
            }
            if($get_query->document=="F&F Statement"){
                $path="ff_statement";
            }
            if($get_query->document=="Form 16"){
                $path="form16";
            }
            if($get_query->document=="Relieving Letter"){
                $path="rel_letter";
            }
            if($get_query->document=="Service Letter"){
                $path="ser_letter";
            }
            if($get_query->document=="Bonus"){
                $path="bonus";
            }
            if($get_query->document=="Performance Incentive"){
                $path="performance_incentive";
            }
            if($get_query->document=="Sales Travel claim"){
                $path="sales_travel_claim";
            }
            if($get_query->document=="Parental medical reimbursement"){
                $path="parental_medical_reimbursement";
            }
            // bonus document from hr
            if($get_query->document=="F&F Document"){
                $path="f_and_f_document";
            }
            // type 2
            if($get_query->document=="PF"){
                $path="pf";
            }
            if($get_query->document=="Gratuity"){
                $path="gratuity";
            }
            // end type 2

            $get_tracker_files = $this->doc_task->get_data_with_where2('f__f_tracker_files',"emp_id",$get_query->emp_id,'s_g_id',10);
            
            $file_name=$get_query->file_name;
            
            // For Form 16 documents, detect the form type from filename
            $display_document_name = $get_query->document;
            if($get_query->document == "Form 16") {
                $filename_without_ext = pathinfo($file_name, PATHINFO_FILENAME);
                $filename_upper = strtoupper($filename_without_ext);
                
                // Check if it's Form 16 Part B (contains PARTB)
                if(strpos($filename_upper, '_PARTB_') !== false) {
                    $display_document_name = "Form 16 B";
                } else {
                    // Check if it follows Form 16 Part A pattern (PAN_YEAR without PARTB)
                    $pattern_a = '/^[A-Z0-9]{10}_\d{4}-\d{2}$/';
                    if(preg_match($pattern_a, $filename_upper)) {
                        $display_document_name = "Form 16 A";
                    }
                }
            }
            
            $show_div.='
            <a href="../documents/'.$credentials['emp_id'].'/'.$path.'/'.$file_name.'" target="_blank">
            <button class="btn btn-outline-primary" style="margin-bottom: 5px;" tabindex="0" aria-controls="completed_query_tbl" type="button" data-toggle="tooltip" data-placement="bottom"  title="PDF"><span><i class="fa fa-file-pdf" style="margin: 5px 5px 5px 5px;"></i>  '.$display_document_name.'</span></button>
            </a>&nbsp;&nbsp;&nbsp;';

        }

        $response= "success";
        return response()->json( ['response' => $response,'show_div'=>$show_div] );
    }

}
