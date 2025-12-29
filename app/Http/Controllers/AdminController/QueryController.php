<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Repositories\IEmpRepository;
use App\Repositories\IQueryRepository;
use DataTables; 
use Image;
use Mail;
use Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB; 


class QueryController extends Controller
{
    // 
    public function __construct( IEmpRepository $emp_task,IQueryRepository  $query_task ) {
        $this->middleware( 'adminLog' );
        $this->query_task = $query_task;
        $this->emp_task = $emp_task;
    }
    public function query_manage_landing()
    {
        return view('Admin.query_manage_landing');
    }
    
    public function get_admin_query_datatable(Request $request){
    
        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : ('');
                if($start_date || $end_date ){
                    $filter_data = [ 
                        'start_date' => $start_date, 
                        'end_date'=> $end_date,
                        'status'=> $type, 
                    ]; 
                    $getquerydetails = $this->query_task->get_admin_query($filter_data);
                }
                else{
                    // login_admin type
                    // $get=$this->query_task->Get_reassign_Query();
                    //  print_r($get);
                   
                        $document=array();
                        if(session()->get('user_type')=="HR-LEAD"){
                            $document=["Pay Slips","F&F Statement","Form 16","Form 16 Part A","Form 16 Part B","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Others","Parental medical reimbursement","Gratuity","PF"];
                        }
                         
                        else if(session()->get('user_type')=="Claims" || session()->get('user_type')=="Payroll_Finance" || session()->get('user_type')=="F_F_HR" || session()->get('user_type')=="Payroll_HR"){
                            // foreach($get as $set){
                                // print_r($set->reassign_to);
                                // $user=$set->reassign_to;
                                // if($user==""){
                                   
                                    if(session()->get('user_type')=="Claims"){
                                        $document=["Sales Travel claim"];
                                    }else if(session()->get('user_type')=="Payroll_Finance"){
                                        $document=[];
                                    }
                                    else if(session()->get('user_type')=="F_F_HR"){
                                        $document=["Relieving Letter","F&F Statement","Service Letter","PF","Others","Form 16","Form 16 Part A","Form 16 Part B"];
                                    }
                                    else if(session()->get('user_type')=="Payroll_HR"){
                                        $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
                                        // $document=["Pay Slips","F&F Statement","Performance Incentive","Sales Travel claim","Bonus","Gratuity"];
                                        // $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
                                    }
                                // } else{
                                //     if(session()->get('user_type')==$set->reassign_to){
                                //         $document=[$set->document];
                                //         $ticket_id=$set->ticket_id;
                                   
                                //     }
                                    // else if(session()->get('user_type')=="Payroll_Finance"){
                                    //     $document=["Form 16"];
                                    // }
                                    // else if(session()->get('user_type')=="F_F_HR"){
                                    //     $document=["F&F Statement","Relieving Letter","Service Letter","PF","Others"];
                                    // }
                                    // else if(session()->get('user_type')=="Payroll_HR"){
                                    //     $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
                                    // }
                                // }
                            // }
                        }
                        // dd($document);
                        // if($user=="")
                        //     {
                                $filter_data = [ 
                                    'status'=> $type,  
                                    'document'=> $document, 
                                    'updated_by'=> session()->get('emp_id'),
                                    'user_type'=> session()->get('user_type'),                                     
                                ];
                                // dd($filter_data);
                        //     }else{
                        //     $filter_data = [ 
                        //         'status'=> $type,  
                        //         'document'=> $document, 
                        //         'updated_by'=> session()->get('emp_id'),
                        //         'user_type'=> session()->get('user_type'),   
                        //     ];
                        // }
                        
                        $get_ticket_id = $this->query_task->get_ticket_id($filter_data);

                        $ticketid = array();
                        foreach($get_ticket_id as $t_id){
                            $ticketid[] = $t_id->ticket_id;
                        }

                        $filter_data['ticket_id'] = $ticketid;

                        $get_not_completed = $this->query_task->get_not_completed_tickets($filter_data);
                        // dd($get_not_completed);

                        $not_t_id = array();
                        if(isset($get_not_completed)){
                            foreach( $get_not_completed as $not_completed){
                                $not_t_id[] = $not_completed->ticket_id;
                            }
                        }else{
                            $not_t_id[]="";
                        }
                        $t_id_new = array_diff($ticketid,$not_t_id);
                        $filter_data['t_id'] = $t_id_new;

                        if(session()->get('user_type') != "HR-LEAD"){
                            $getquerydetails = $this->query_task->get_admin_query_default($filter_data);
                        }else{
                            $getquerydetails = $this->query_task->get_admin_query_default2($filter_data);
                        }

                }

                // dd($getquerydetails);
                return Datatables::of($getquerydetails)
                        ->addIndexColumn()
                        ->addColumn('action', function($row) use($type){
                            $getempdetail = $this->emp_task->get_employee_detail($row->emp_id); 
                            $getticketdate=$this->query_task->get_ticket_date($row->ticket_id);
                            foreach($getempdetail as $getempdetail)
                            {
                                $emp_name = $getempdetail->emp_name;
                            }
                            $value="";
                            if($getticketdate!=null){
                                $date =date('Y-m-d', strtotime( $getticketdate->updated_at ));
                                date_default_timezone_set('Asia/Kolkata');
                                $todate=date('Y-m-d');
                                if($date==$todate){
                                    $value="disabled";
                                }else{
                                    $value="";
                                }
                            }
                            if($type=="Pending"){
                                $approve="Approved";
                                $decline="Declined"; 
                                if(session()->get('user_type') == "HR-LEAD"){
                                    $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Reassign To" onclick="reassign_query_click('."'".$row->ticket_id."'".','."'".$row->emp_id."'".');" '.$value.'><i class="fas fa-exchange-alt"></i></a>';
                                }
                                else{
                                    $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Approve Query" onclick="update_query_click('."'".$row->ticket_id."'".','."'".$approve."'".','."'".$row->doc_id."'".');"><i class="fas fa-check"></i></a>
                                    <a href="#" class="btn btn-icon btn-danger ac_btn" title="Decline Query" onclick="update_query_click('."'".$row->ticket_id."'".','."'".$decline."'".','."'".$row->doc_id."'".');"><i class="fas fa-times"></i></a>';
                                }
                            }
                            if($type=="Approved"){ 
                                $final_doc=array();
                                $document=array();
                                if(session()->get('user_type')=="HR-LEAD"){
                                    $document=["Pay Slips","F&F Statement","Form 16","Form 16 Part A","Form 16 Part B","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Others","Parental medical reimbursement","Gratuity","PF"];
                                }
                                else if(session()->get('user_type')=="Claims" || session()->get('user_type')=="Payroll_Finance" || session()->get('user_type')=="F_F_HR" || session()->get('user_type')=="Payroll_HR"){
                                    if(session()->get('user_type')=="Claims"){
                                        $document=["Sales Travel claim"];
                                    }
                                    else if(session()->get('user_type')=="Payroll_Finance"){
                                        $document=[];
                                    }
                                    else if(session()->get('user_type')=="F_F_HR"){
                                        
                                        $document=["F&F Statement","Relieving Letter","Service Letter","PF","Others","Form 16","Form 16 Part A","Form 16 Part B"];
                                    }
                                    else if(session()->get('user_type')=="Payroll_HR"){
                                        $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
                                    }
                                    
                                } 
                                $credentials=[
                                    'ticket_id'=>$row->ticket_id, 
                                    'document'=>$document, 
                                ]; 
                                $get_docs = $this->query_task->Get_docs_Query($credentials);
                                foreach( $get_docs as $docs){
                                    if($docs->file_name == "" && $docs->remark ==""){
                                        $final_doc[]=$docs->document;
                                    }
                                }
                                $final_doc_st=implode(",",$final_doc);

                                $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Upload document" onclick="upload_document('."'".$row->ticket_id."'".','."'".$emp_name."'".','."'".$row->emp_id."'".','."'".$final_doc_st."'".');"><i class="fas fa-upload"></i></a>';
                            }
                            if($type=="Completed"){
                                $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="View document" onclick="doc_detail('."'".$row->ticket_id."'".','."'".$row->emp_id."'".','."'".$emp_name."'".');"><i class="fas fa-eye"></i>&nbsp</a>';
                            }
                            if($type=="Declined"){
                                $approve="Approved";
                                $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Approve Query" onclick="update_query_click_dec_tab('."'".$row->ticket_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>';
                            }
                            return $action;
                        })
                        ->addColumn('remark', function($row) use($type){
                            if($type=="Declined"){
                                if(session()->get('user_type') != "HR-LEAD"){  
                                    $remark = '<b>Remark</b> : '.$row->remark.'<br><b>Decline Remark : </b><br>'.$row->dec_remark.'';
                                }else{
                                    $remark = '<b>Remark</b> : '.$row->remark.'<br><b>Decline Remarks ( Declined By ) : </b><br>';
                                    $get_decline_remark = $this->query_task->get_decline_remark( $row->ticket_id );
                                    $i=1;
                                    if(isset($get_decline_remark[0])){
                                        foreach($get_decline_remark as $row){
                                            if($row->dec_remark !=""){
                                                $remark .= $i.'. '.$row->dec_remark.' ( '.$row->updated_by.' )<br>';
                                                $i++;
                                            }
                                        }
                                    }
                                    
                                }
                            }
                            else{
                                $remark = $row->remark; 
                            }
                            return $remark;
                        })
                    
                        ->addColumn('document_div', function($row) use($type){
                            $doc_arr=explode(",",$row->document); 
                            $d_i=0;
                            $doc='';
                            $document_div='';
                    
                            if($type=="Completed" || session()->get('user_type') == "HR-LEAD"){
                                while($d_i<count($doc_arr)){ 
                                    if(session()->get('user_type')=="Claims"  && $doc_arr[$d_i] =="Sales Travel claim" ){
                                        if($doc_arr[$d_i] !=""){
                                            $doc .= $doc_arr[$d_i].', ';
                                        }
                                    }
                                    elseif(session()->get('user_type')=="F_F_HR"  && ( $doc_arr[$d_i] =="F&F Statement" || $doc_arr[$d_i] =="Others" ||  $doc_arr[$d_i] =="Relieving Letter" || $doc_arr[$d_i] =="Service Letter"|| $doc_arr[$d_i] =="PF" || $doc_arr[$d_i] =="Form 16")){

                                        if($doc_arr[$d_i] !=""){
                                            $doc .= $doc_arr[$d_i].', ';
                                        }
                                    }
                                    // elseif(session()->get('user_type')=="Payroll_HR"  && ($doc_arr[$d_i] =="Pay Slips" || $doc_arr[$d_i] =="Bonus" || $doc_arr[$d_i] =="Performance Incentive" || $doc_arr[$d_i] =="Bonus" || $doc_arr[$d_i] =="Parental medical reimbursement" ||  $doc_arr[$d_i] =="Gratuity")){
                                    elseif(session()->get('user_type')=="Payroll_HR"  && ($doc_arr[$d_i] =="Pay Slips" || $doc_arr[$d_i] =="Bonus" || $doc_arr[$d_i] =="Performance Incentive" || $doc_arr[$d_i] =="Parental medical reimbursement" || $doc_arr[$d_i] =="Gratuity")){

                                        if($doc_arr[$d_i] !=""){
                                            $doc .= $doc_arr[$d_i].', ';
                                        }
                                    }
                                    elseif(session()->get('user_type')=="HR-LEAD"){
                                        if($doc_arr[$d_i] !=""){
                                            $doc .= $doc_arr[$d_i].', ';
                                        }
                                    }
                                    $d_i++;
                                }
                                $tool_tip='<a style="cursor:pointer;" title="'.substr($doc, 0, -2).'"><button class="btn btn btn-sm btn-info"><i class="fa fa-file" aria-hidden="true"></i></button></a>';
                                return $tool_tip;
                            }
                            if($type=="Approved" && session()->get('user_type') != "HR-LEAD"){
                                 if(session()->get('user_type')=="Claims" || session()->get('user_type')=="Payroll_Finance" || session()->get('user_type')=="F_F_HR" || session()->get('user_type')=="Payroll_HR"){
                                    if(session()->get('user_type')=="Claims"){
                                        $document=["Sales Travel claim"];
                                    }
                                    else if(session()->get('user_type')=="Payroll_Finance"){
                                        $document=[];

                                    }
                                    else if(session()->get('user_type')=="F_F_HR"){
                                        $document=["F&F Statement","Relieving Letter","Service Letter","PF","Others","Form 16"];


                                    }
                                    else if(session()->get('user_type')=="Payroll_HR"){
                                        // $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
                                        // $document=["Pay Slips","F&F Statement","Performance Incentive","Sales Travel claim","Bonus","Gratuity"];
                                        $document=["Pay Slips","Performance Incentive","Bonus","Gratuity","Parental medical reimbursement"];

                                    }
                                } 
                                // dd($document);
                                $credentials=[
                                    'ticket_id'=>$row->ticket_id, 
                                    'document'=>$document, 
                                ]; 
                                $get_docs = $this->query_task->Get_docs_Query($credentials);
                                foreach( $get_docs as $docs){
                                    if($docs->file_name == "" && $docs->remark ==""){
                                        $document_div.='<div class="badge badge-danger doc_name">'.$docs->document.'</div><br>';
                                    }else{
                                        $document_div.='<div class="badge badge-success doc_name">'.$docs->document.'</div><br>';
                                    }
                                }
                                return $document_div;
                            }
                            else{

                            while($d_i<count($doc_arr)){ 
                                if(session()->get('user_type')=="Claims"  && $doc_arr[$d_i] =="Sales Travel claim" ){
                                    $document_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                                }
                                elseif(session()->get('user_type')=="F_F_HR"  && ( $doc_arr[$d_i] =="F&F Statement"  ||$doc_arr[$d_i] =="Others" ||  $doc_arr[$d_i] =="Relieving Letter" || $doc_arr[$d_i] =="Service Letter"|| $doc_arr[$d_i] =="PF" || $doc_arr[$d_i] =="Form 16")){
                                    $document_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                                }
                                elseif(session()->get('user_type')=="Payroll_HR"  && ($doc_arr[$d_i] =="Pay Slips" || $doc_arr[$d_i] =="Bonus" || $doc_arr[$d_i] =="Performance Incentive" ||  $doc_arr[$d_i] =="Gratuity" || $doc_arr[$d_i] =="Parental medical reimbursement")){
                                    // elseif(session()->get('user_type')=="Payroll_HR"  && ($doc_arr[$d_i] =="Pay Slips" || $doc_arr[$d_i] =="Bonus" || $doc_arr[$d_i] =="Performance Incentive" || $doc_arr[$d_i] =="F&F Statement" || $doc_arr[$d_i] =="Sales Travel claim" || $doc_arr[$d_i] =="Gratuity")){
                                    $document_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                                }elseif(session()->get('user_type')=="HR-LEAD"){
                                    $document_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                                }
                                $d_i++;
                            }
                                // dd($document_div);

                            return $document_div;
                            }

                        })
                        ->addColumn('status', function($row){
                            if($row->status=="Pending"){
                                $sts_clr='warning';
                            }
                            else if($row->status=="Approved"){
                                $sts_clr='primary';
                            }
                            else if($row->status=="Declined"){ 
                                $sts_clr='danger';
                            }
                            else if($row->status=="Completed"){
                                $sts_clr='success';
                            }
                            $status_btn = '<div class="badge badge-'.$sts_clr.'">'.$row->status.'</div>';
                            return $status_btn;
                        })
                        ->addColumn('type_of_leaving', function($row){
                            $getempdetail = $this->emp_task->get_employee_detail($row->emp_id);
                            
                            foreach($getempdetail as $getempdetails){
                                $type_of_leaving1 = $getempdetails->type_of_leaving;
                            }
                            if(!$type_of_leaving1== ""|| !$type_of_leaving1== null){
                                $type_of_leaving = '';
                                if($type_of_leaving1 == "Abscond" || $type_of_leaving1 == "Terminated"){
                                    $type_of_leaving.='<div class="badge badge-danger doc_name">'.$type_of_leaving1.'</div><br>';
                                }
                                elseif($getempdetails->type_of_leaving == "Transferred"){
                                    
                                    $type_of_leaving.='<div class="badge badge-primary doc_name">'.$type_of_leaving1.'</div><br>';
                                }else{
                                    $type_of_leaving.='<div class="badge badge-success doc_name">'.$type_of_leaving1.'</div><br>';
                                }
                                return $type_of_leaving;
                            }
                            else{
                                return "-----";
                            }
                            
                        })
                        ->addColumn('created_at', function($row){
                            $created_at=date('d-m-Y h:i:s', strtotime($row->created_at));
                            return $created_at;
                        })
                        ->rawColumns(['action','remark','document_div','status','type_of_leaving','created_at'])
                        ->make(true);
        }
        return view(' Admin.query_manage_landing ');
    }
    public function get_employee_detail( Request $req ){
        $emp_id = $req->input('emp_id');
        $getempdetail = $this->emp_task->get_employee_detail( $emp_id );

        return $getempdetail; 
    } 
    public function update_query_status(Request $request) 
    {

      
        $document=array();
        
        if(session()->get('user_type')=="HR-LEAD"){
            $document=["Pay Slips","F&F Statement","Form 16","Form 16 Part A","Form 16 Part B","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Others","Parental medical reimbursement","Gratuity","PF"];
        }
      else if(session()->get('user_type')=="Claims" || session()->get('user_type')=="Payroll_Finance" || session()->get('user_type')=="F_F_HR" || session()->get('user_type')=="Payroll_HR"){
            if(session()->get('user_type')=="Claims"){
                $document=["Sales Travel claim"];
            }
            else if(session()->get('user_type')=="Payroll_Finance"){
                $document=[];

            }
            else if(session()->get('user_type')=="F_F_HR"){
                $document=["F&F Statement","Relieving Letter","Service Letter","Others","PF","Form 16","Form 16 Part A","Form 16 Part B"];

            }
            else if(session()->get('user_type')=="Payroll_HR"){
                // $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
                $document=["Pay Slips","Performance Incentive","Sales Travel claim","Bonus","Parental medical reimbursement","Gratuity"];

            }
        } 

        if($request->input('type') == "Declined"){
            $mes = ['dec_remark.required'=>"The Decline Remark field is required."];
            $validator = Validator::make($request->all(),[
                'dec_remark' =>'required',
            ],$mes);
            if($validator->fails()){
                return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            }
        }

        $credentials=[
            'ticket_id'=>$request->input('ticket_id'),
            'doc_id'=>$request->input('doc_id'),
            'status'=>$request->input('type'),
            'dec_remark'=>$request->input('dec_remark'),
            'updated_by'=>session()->get('emp_id'),
            'document'=>$document,
            'sts'=>"Declined",
        ];

        $update_query2 = $this->query_task->update_query_status_doc( $credentials );

        $get_ticket_count = $this->query_task->get_ticket_count($credentials);
        $get_status_count = $this->query_task->get_status_count($credentials);

        if($request->input('type') == "Declined"){
            if($get_ticket_count == $get_status_count){
                $update_query = $this->query_task->update_query_status( $credentials );
            }
        }else{
            $update_query = $this->query_task->update_query_status( $credentials );
        }



        $response= "success";
        return response()->json( ['response' => $response,'ticket_id'=>$request->input('ticket_id')]);

    }

    public function send_qry_stats_mail(Request $request)
    {
        # code...
        $ticket_id=$request->input('ticket_id');

        // get user id
        $get_ticket_row = $this->query_task->get_query_u_tic_id( $ticket_id );

        $emp_id = $get_ticket_row[0]->emp_id;
        $getempdetail = $this->emp_task->get_employee_detail( $emp_id );
        // send query mail
        
        // To Master Mail 
        $company_email = $getempdetail[0]->email;
        // $company_email ="lakshminarayanan@hemas.in"; 

        $body_content1 = "Dear ".$getempdetail[0]->emp_name;
        $body_content2 = "Ticket ID: ".$ticket_id;
        $body_content3 = 'Your Query Moved to: '.$get_ticket_row[0]->status.' Status';
        $body_content4 = 'Pls raise a query in your login if you need further assistance,';
        $body_content5 = "https://citpl_alumni.cavinkare.in/index.php/login"; 
        $body_content6 = "Cheers";
        $body_content7 = "Team HR"; 

      

        $details = [
            'subject' => 'CITPL',
            'title' => 'Your Query Moved to: '.$get_ticket_row[0]->status,
            'body_content1' => $body_content1,
            'body_content2' => $body_content2,
            'body_content3' => $body_content3,
            'body_content4' => $body_content4, 
            'body_content5' => $body_content5, 
            'body_content6' => $body_content6, 
            'body_content7' => $body_content7, 
        ];

        // in proper laravel method mail send plz enable below link
        \Mail::to($company_email)->send(new \App\Mail\QueryUpdate($details));
        $response= "success";
        return response()->json( ['response' => $response,'send_mail_to'=>$company_email]);

    }

    public function doc_updated_detail(Request $request)
    {
        $credentials=[
            'ticket_id'=>$request->input('ticket_id'),
            'emp_id'=>$request->input('emp_id'),
        ];
        $update_query_doc = $this->query_task->get_updated_doc_detail( $credentials );

        $show_div="";
        $remark="";

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
            if($get_query->document=="Form 16 Part A"){
                $path="form16";
            }
            if($get_query->document=="Form 16 Part B"){
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
            // type 2
            if($get_query->document=="PF"){
                $path="pf";
            }
            if($get_query->document=="Gratuity"){
                $path="gratuity";
            } 
            // end type 2

            if($get_query->document=="Others"){
                $path="others_doc";
            }
            $file_name=$get_query->file_name;
          
           

            if($file_name==""){
                // file empty
                $file_check="cursor: not-allowed; pointer-events: none";
                $btn_style="cursor: not-allowed;    pointer-events: none;";
                $msg="<p>Document Not Uploaded.!</p>";
            }
            else{
                $file_check="";
                $btn_style="";
                $msg="";
            }

            $get_query_docs ="";
            $rem ="";

            if(session()->get('user_type')=="Claims"  && $get_query->document =="Sales Travel claim" ){
                $get_query_docs.=$get_query->document;
                $rem.=$get_query->remark;
            }elseif(session()->get('user_type')=="F_F_HR"  && ( $get_query->document =="F&F Statement" ||  $get_query->document =="Relieving Letter" || $get_query->document =="Service Letter" || $get_query->document =="PF" || $get_query->document =="Others" || $get_query->document =="Form 16" || $get_query->document =="Form 16 Part A" || $get_query->document =="Form 16 Part B")){
                $get_query_docs.=$get_query->document;
                $rem.=$get_query->remark;
            }
            elseif(session()->get('user_type')=="Payroll_HR"  && ($get_query->document =="Pay Slips" || $get_query->document =="Bonus" || $get_query->document =="Performance Incentive" || $get_query->document =="Bonus" || $get_query->document =="Parental medical reimbursement" || $get_query->document =="Gratuity")){
                $get_query_docs.=$get_query->document;
                $rem.=$get_query->remark;
            }elseif(session()->get('user_type')=="HR-LEAD"){
                $get_query_docs.=$get_query->document;
                $rem.=$get_query->remark;
            }elseif(session()->get('user_type')!="Claims" && session()->get('user_type')!="Payroll_Finance" && session()->get('user_type')!="F_F_HR" && session()->get('user_type')!="Payroll_HR" && session()->get('user_type')!="HR-LEAD" ){
                $get_query_docs.=$get_query->document;
                $rem.=$get_query->remark;
            }	

                $remark='<div class="form-group col-md-8" '.$get_query_docs.'"> 
            <label>Remark</label><br>
            <span  >'.$rem.'</span>
            </div>';

            if($get_query_docs !=""){
                if($get_query_docs == "Pay Slips"){
                    // Handle Pay Slips with multiple files
                    $files = explode(',',$file_name);
                    $show_div.='';
                    foreach($files as $file){
                        $file = trim($file); // Remove any extra spaces
                        if(empty($file)) continue; // Skip empty file names
                        
                        $show_div.='<div class="row">
                    <div class="col-md-4">
                    <label class="text-dark" style="font-size:12px;">Document</label><br>
                        <a style="'.$file_check.'" href="../query/'.$credentials['emp_id'].'/'.$path.'/'.$file.'" target="_blank">
                            <button style="'.$btn_style.'" class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><span><i class="fa fa-file-pdf"></i> '.$get_query_docs.'</span></button>'.$msg.'
                        </a>
                    </div>'.$remark.'
                    </div><hr>';
                    }
                } else if($get_query_docs == "Form 16"){
                    // Handle combined Form 16 entries - separate Part A and Part B with their respective remarks
                    $files = explode(',',$file_name);
                    $remarks_text = $rem; // Get the combined remarks
                    
                    // Parse the combined remarks to extract Part A and Part B remarks
                    $part_a_remark = '';
                    $part_b_remark = '';
                    
                    if(!empty($remarks_text)){
                        // Split by | to get individual part remarks
                        $remark_parts = explode(' | ', $remarks_text);
                        foreach($remark_parts as $remark_part){
                            $remark_part = trim($remark_part);
                            if(strpos($remark_part, 'Part A:') === 0){
                                $part_a_remark = trim(substr($remark_part, 8)); // Remove "Part A: " prefix and trim
                            } elseif(strpos($remark_part, 'Part B:') === 0){
                                $part_b_remark = trim(substr($remark_part, 8)); // Remove "Part B: " prefix and trim
                            }
                        }
                    }
                    
                    $show_div.='';
                    foreach($files as $file){
                        $file = trim($file); // Remove any extra spaces
                        if(empty($file)) continue; // Skip empty file names
                        
                        // Determine the display label and specific remark based on file name
                        $display_label = $get_query_docs;
                        $specific_remark = '';
                        
                        if(strpos($file, 'form16_part_A') !== false || strpos($file, 'form16_part_a') !== false){
                            $display_label = "Form 16 Part A";
                            $specific_remark = $part_a_remark;
                        } elseif(strpos($file, 'form16_part_B') !== false || strpos($file, 'form16_part_b') !== false){
                            $display_label = "Form 16 Part B";
                            $specific_remark = $part_b_remark;
                        }
                        
                        // Create the remark HTML for this specific part
                        $part_remark_html = '<div class="form-group col-md-8"> 
                            <label>Remark</label><br>
                            <span>'.(!empty($specific_remark) ? htmlspecialchars($specific_remark) : 'No remark provided').'</span>
                            </div>';
                        
                        $show_div.='<div class="row">
                    <div class="col-md-4">
                    <label class="text-dark" style="font-size:12px;">Document</label><br>
                        <a style="'.$file_check.'" href="../query/'.$credentials['emp_id'].'/'.$path.'/'.$file.'" target="_blank">
                            <button style="'.$btn_style.'" class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><span><i class="fa fa-file-pdf"></i> '.$display_label.'</span></button>'.$msg.'
                        </a>
                    </div>'.$part_remark_html.'
                    </div><hr>';
                    }
                } else {
                    // Handle all other documents including Form 16 Part A and Part B individually
                    $show_div.='<div class="row">
                    <div class="col-md-4">
                    <label class="text-dark" style="font-size:12px;">Document</label><br>
                        <a style="'.$file_check.'" href="../query/'.$credentials['emp_id'].'/'.$path.'/'.$file_name.'" target="_blank">
                            <button style="'.$btn_style.'" class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><span><i class="fa fa-file-pdf"></i> '.$get_query_docs.'</span></button>'.$msg.'
                        </a>
                    </div>'.$remark.'
                    </div><hr>';
                }
                
            }
        }

        $response= "success";
        return response()->json( ['response' => $response,'show_div'=>$show_div] );
    }

    public function doc_upload_admin_submit(Request $request)
    {
      
        $emp_id=$request->input('emp_id');
        $ticket_id=$request->input('ticket_id');
        $document_pop=$request->input('pop_document');
        $remark=$request->input('remark');
        $doc_arr=explode(",",$document_pop);  

        $marks = array("Pay Slips","F&F Statement","Form 16","Form 16 Part A","Form 16 Part B","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity","Others"); 
        $i=0;

        $doc_row=array();
        $form16_processed = false; // Flag to ensure Form 16 is processed only once

        while($i<count($doc_arr)){
            if (in_array($doc_arr[$i], $marks)==true)
            {
                if($doc_arr[$i]=="Pay Slips"){
                    $files = $request->file('pay_slip');
                    if(is_array($files)){
                        $ps_count=count($files);
                        if($request->hasfile('pay_slip')){
                            $p_s_c=0;
                            $ah_name = array();
                            foreach ($files as $file) {
                                $ah_name[] = 'payslip'.time().'_'.$p_s_c.'.'.$file->extension();
                                $file->move(public_path().'/query/'.$emp_id.'/pay_slip', $ah_name[$p_s_c]); 
                                $p_s_c++;
                            }
                            $doc_row[]=[
                                'doc_type'=>'Pay Slips',
                                'doc_name'=>implode(',',$ah_name),
                                'remark'=>$request->input('pay_slip_remark'),
                            ];
                        }
                        else if($request->input('pay_slip_remark')!=""){
                            $doc_row[]=[
                                'doc_type'=>'Pay Slips',
                                'doc_name'=>"",
                                'remark'=>$request->input('pay_slip_remark'),
                            ];
                        }
                    }
                    else if($request->input('pay_slip_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Pay Slips',
                            'doc_name'=>"",
                            'remark'=>$request->input('pay_slip_remark'),
                        ];
                    }
                }

                if($doc_arr[$i]=="F&F Statement"){
                    if($request->hasfile('ff_statement')){
                        $ah_name = 'ff_statement'.time().'.'.$request->file('ff_statement')->extension();
                        $request->file('ff_statement')->move(public_path().'/query/'.$emp_id.'/ff_statement', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'F&F Statement',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('ff_statement_remark'),
                        ];
                    }
                    else if($request->input('ff_statement_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'F&F Statement',
                            'doc_name'=>"",
                            'remark'=>$request->input('ff_statement_remark'),
                        ];
                    }
                }
                // Handle Form 16 processing - process all Form 16 related documents together
                if(($doc_arr[$i]=="Form 16" || $doc_arr[$i]=="Form 16 Part A" || $doc_arr[$i]=="Form 16 Part B") && !$form16_processed){
                    $form16_processed = true; // Mark as processed to avoid duplicate processing
                    
                    $timestamp = time();
                    $form16_path = public_path().'/query/'.$emp_id.'/form16';
                    if (!file_exists($form16_path)) {
                        mkdir($form16_path, 0777, true);
                    }
                    
                    // Combine both Part A and Part B into a single Form 16 entry
                    $combined_files = [];
                    $combined_remarks = [];
                    $validation_errors = [];
                    
                    // Get employee PAN number for validation
                    $employee = DB::table('emp_profile_tbls')->where('emp_id', $emp_id)->first();
                    if (!$employee || empty($employee->pan_no)) {
                        $validation_errors['form_16_part_a'] = ['Employee PAN number not found in system'];
                    } else {
                        $employee_pan = strtoupper($employee->pan_no);
                        
                        // Validate Form 16 Part A
                        if($request->hasfile('form_16_part_a')){
                            $file_a = $request->file('form_16_part_a');
                            $original_name_a = pathinfo($file_a->getClientOriginalName(), PATHINFO_FILENAME);
                            
                            // Validate Part A file name format: PANNO_YYYY-YY
                            if (!preg_match('/^([A-Z0-9]{10})_(\d{4}-\d{2})$/', $original_name_a, $matches_a)) {
                                $validation_errors['form_16_part_a'] = ['Part A file name must be in format: PANNO_YYYY-YY (e.g., SDTY276112_2025-26)'];
                            } else {
                                $file_pan_a = $matches_a[1];
                                $file_year_a = $matches_a[2];
                                
                                // Validate PAN number matches employee PAN
                                if ($file_pan_a !== $employee_pan) {
                                    $validation_errors['form_16_part_a'] = ['Part A file PAN number (' . $file_pan_a . ') does not match employee PAN (' . $employee_pan . ')'];
                                } else {
                                    // File is valid, process it
                                    $ah_name_a = 'form16_part_A_'.$timestamp.'_'.$emp_id.'.'.$file_a->extension();
                                    $file_a->move($form16_path, $ah_name_a);  
                                    $combined_files[] = $ah_name_a;
                                }
                            }
                        }
                        
                        // Validate Form 16 Part B
                        if($request->hasfile('form_16_part_b')){
                            $file_b = $request->file('form_16_part_b');
                            $original_name_b = pathinfo($file_b->getClientOriginalName(), PATHINFO_FILENAME);
                            
                            // Validate Part B file name format: PANNO_PARTB_YYYY-YY
                            if (!preg_match('/^([A-Z0-9]{10})_PARTB_(\d{4}-\d{2})$/', $original_name_b, $matches_b)) {
                                $validation_errors['form_16_part_b'] = ['Part B file name must be in format: PANNO_PARTB_YYYY-YY (e.g., SDTY276112_PARTB_2025-26)'];
                            } else {
                                $file_pan_b = $matches_b[1];
                                $file_year_b = $matches_b[2];
                                
                                // Validate PAN number matches employee PAN
                                if ($file_pan_b !== $employee_pan) {
                                    $validation_errors['form_16_part_b'] = ['Part B file PAN number (' . $file_pan_b . ') does not match employee PAN (' . $employee_pan . ')'];
                                } else {
                                    // File is valid, process it
                                    $ah_name_b = 'form16_part_B_'.($timestamp + 1).'_'.$emp_id.'.'.$file_b->extension();
                                    $file_b->move($form16_path, $ah_name_b);  
                                    $combined_files[] = $ah_name_b;
                                }
                            }
                        }
                    }
                    
                    // Add remarks for valid files
                    if($request->input('form_16_part_a_remark')!="" && !isset($validation_errors['form_16_part_a'])){
                        $combined_remarks[] = 'Part A: ' . $request->input('form_16_part_a_remark');
                    }
                    if($request->input('form_16_part_b_remark')!="" && !isset($validation_errors['form_16_part_b'])){
                        $combined_remarks[] = 'Part B: ' . $request->input('form_16_part_b_remark');
                    }
                    
                    // If there are validation errors, return them
                    if (!empty($validation_errors)) {
                        return response()->json(['status' => 0, 'error' => $validation_errors]);
                    }
                    
                    // Create a single Form 16 entry with combined information (only if no errors)
                    if(!empty($combined_files) || !empty($combined_remarks)){
                        $doc_row[]=[
                            'doc_type'=>'Form 16',
                            'doc_name'=>implode(', ', $combined_files),
                            'remark'=>implode(' | ', $combined_remarks),
                        ];
                    }
                }
                // Skip individual Form 16 Part A and Part B processing since they're handled above
                else if($doc_arr[$i]=="Form 16 Part A" || $doc_arr[$i]=="Form 16 Part B"){
                    // Skip - already processed in the Form 16 block above
                }
                if($doc_arr[$i]=="Relieving Letter"){
                    if($request->hasfile('rel_letter')){
                        $ah_name = 'rel_letter'.time().'.'.$request->file('rel_letter')->extension();
                        $request->file('rel_letter')->move(public_path().'/query/'.$emp_id.'/rel_letter', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Relieving Letter',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('rel_letter_remark'),
                        ];
                    }
                    else if($request->input('rel_letter_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Relieving Letter',
                            'doc_name'=>"",
                            'remark'=>$request->input('rel_letter_remark'),
                        ];
                    }
                }
                if($doc_arr[$i]=="Service Letter"){
                    if($request->hasfile('ser_letter')){
                        $ah_name = 'ser_letter'.time().'.'.$request->file('ser_letter')->extension();
                        $request->file('ser_letter')->move(public_path().'/query/'.$emp_id.'/ser_letter', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Service Letter',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('ser_letter_remark'),
                        ];
                    }
                    else if($request->input('ser_letter_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Service Letter',
                            'doc_name'=>"",
                            'remark'=>$request->input('ser_letter_remark'),
                        ];
                    }
                }

                if($doc_arr[$i]=="Bonus"){
                    if($request->hasfile('bonus')){
                        $ah_name = 'bonus'.time().'.'.$request->file('bonus')->extension();
                        $request->file('bonus')->move(public_path().'/query/'.$emp_id.'/bonus', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Bonus',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('bonus_remark'),
                        ];
                    }
                    else if($request->input('bonus_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Bonus',
                            'doc_name'=>"",
                            'remark'=>$request->input('bonus_remark'),
                        ];
                    }
                }
                if($doc_arr[$i]=="Performance Incentive"){
                    if($request->hasfile('performance_incentive')){
                        $ah_name = 'performance_incentive'.time().'.'.$request->file('performance_incentive')->extension();
                        $request->file('performance_incentive')->move(public_path().'/query/'.$emp_id.'/performance_incentive', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Performance Incentive',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('performance_incentive_remark'),
                        ];
                    }
                    else if($request->input('performance_incentive_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Performance Incentive',
                            'doc_name'=>"",
                            'remark'=>$request->input('performance_incentive_remark'),
                        ];
                    }
                }
                if($doc_arr[$i]=="Sales Travel claim"){
                    if($request->hasfile('sales_travel_claim')){
                        $ah_name = 'sales_travel_claim'.time().'.'.$request->file('sales_travel_claim')->extension();
                        $request->file('sales_travel_claim')->move(public_path().'/query/'.$emp_id.'/sales_travel_claim', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Sales Travel claim',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('sales_travel_claim_remark'),
                        ];
                    }
                    else if($request->input('sales_travel_claim_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Sales Travel claim',
                            'doc_name'=>"",
                            'remark'=>$request->input('sales_travel_claim_remark'),
                        ];
                    }
                }
                if($doc_arr[$i]=="Parental medical reimbursement"){
                    if($request->hasfile('parental_medical_reimbursement')){
                        $ah_name = 'parental_medical_reimbursement'.time().'.'.$request->file('parental_medical_reimbursement')->extension();
                        $request->file('parental_medical_reimbursement')->move(public_path().'/query/'.$emp_id.'/parental_medical_reimbursement', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Parental medical reimbursement',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('parental_medical_reimbursement_remark'),
                        ];
                    }
                    else if($request->input('parental_medical_reimbursement_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Parental medical reimbursement',
                            'doc_name'=>"",
                            'remark'=>$request->input('parental_medical_reimbursement_remark'),
                        ];
                    }
                }
                // type 2
                if($doc_arr[$i]=="PF"){
                    if($request->hasfile('pf')){
                        $ah_name = 'pf'.time().'.'.$request->file('pf')->extension();
                        $request->file('pf')->move(public_path().'/query/'.$emp_id.'/pf', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'PF',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('pf_remark'),
                        ];
                    }
                    else if($request->input('pf_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'PF',
                            'doc_name'=>"",
                            'remark'=>$request->input('pf_remark'),
                        ];
                    }
                }
                if($doc_arr[$i]=="Gratuity"){
                    if($request->hasfile('gratuity')){
                        $ah_name = 'gratuity'.time().'.'.$request->file('gratuity')->extension();
                        $request->file('gratuity')->move(public_path().'/query/'.$emp_id.'/gratuity', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Gratuity',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('gratuity_remark'),
                        ];
                    }
                    else if($request->input('gratuity_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Gratuity',
                            'doc_name'=>"",
                            'remark'=>$request->input('gratuity_remark'),
                        ];
                    }
                }
                // end type 2

                if($doc_arr[$i]=="Others"){
                    if($request->hasfile('others_doc')){
                        $ah_name = 'others_doc'.time().'.'.$request->file('others_doc')->extension();
                        $request->file('others_doc')->move(public_path().'/query/'.$emp_id.'/others_doc', $ah_name);  
                        $doc_row[]=[
                            'doc_type'=>'Others',
                            'doc_name'=>$ah_name,
                            'remark'=>$request->input('others_remark'),
                        ];
                    }
                    else if($request->input('others_remark')!=""){
                        $doc_row[]=[
                            'doc_type'=>'Others',
                            'doc_name'=>"",
                            'remark'=>$request->input('others_remark'),
                        ];
                    }
                }

            }
            $i++;
        }

        // Continue with document processing

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
            // t2
            $pf_file_url="";
            $gratuity_file_url="";
            // t2 end
            $others_doc_file_url="";

            $string_file_type=array();

            $pay_slip_file_url=array();
            $form16_file_url=array();

        while($count<count($doc_row)){
            // Check if the required keys exist
            if(!isset($doc_row[$count]['doc_type']) || !isset($doc_row[$count]['remark'])){
                $count++;
                continue;
            }
            
            // Ensure doc_name is set (can be empty string)
            if(!isset($doc_row[$count]['doc_name'])){
                $doc_row[$count]['doc_name'] = '';
            }
            
            // save query document row
            $credentials=[
                'ticket_id'=>$ticket_id,  
                'document'=>$doc_row[$count]['doc_type'],
                'file_name'=>$doc_row[$count]['doc_name'],
                'remark'=>$doc_row[$count]['remark'],
                'status'=>"Completed",
                'sts'=>"Completed",  // Add the correct key for get_status_count method
                'updated_by'=>session()->get('emp_id'),
            ];

            $saved_query_ticket_id = $this->query_task->QueryDocumentEntry( $credentials );

            $get_ticket_count = $this->query_task->get_ticket_count($credentials);
            $get_status_count = $this->query_task->get_status_count($credentials);

          if($get_ticket_count == $get_status_count){
              $update_overall_status = $this->query_task->update_overall_status($credentials);
          }
            // make mail doc
            
            $string_file_type[]=$credentials['document'];

            $file_name=$credentials['file_name'];
           
            if($credentials['document']=="Pay Slips"){
                $path="pay_slip";
                $pay_slip_file_name= explode(',',$file_name) ;
                if($pay_slip_file_name!=""){
                    foreach($pay_slip_file_name as $file){
                        $pay_slip_file_url[]="query/".$emp_id."/".$path."/".$file."";
                    }
                }
            }
            if($credentials['document']=="F&F Statement"){
                $path="ff_statement";
                $ff_statement_file_name=$file_name;
                if($ff_statement_file_name!=""){
                    $ff_statement_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            if($credentials['document']=="Form 16"){
                $path="form16";
                $form16_file_name = explode(',',$file_name) ;
                if($form16_file_name!=""){
                    foreach($form16_file_name as $file){
                        $form16_file_url[]="query/".$emp_id."/".$path."/".$file."";
                    }
                }
            }
            if($credentials['document']=="Relieving Letter"){
                $path="rel_letter";
                $rel_letter_file_name=$file_name;
                if($rel_letter_file_name!=""){
                    $rel_letter_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            if($credentials['document']=="Service Letter"){
                $path="ser_letter";
                $ser_letter_file_name=$file_name;
                if($ser_letter_file_name!=""){
                    $ser_letter_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }

            if($credentials['document']=="Bonus"){
                $path="bonus";
                $bonus_file_name=$file_name;
                if($bonus_file_name!=""){
                    $bonus_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            if($credentials['document']=="Performance Incentive"){
                $path="performance_incentive";
                $performance_incentive_file_name=$file_name;
                if($performance_incentive_file_name!=""){
                    $performance_incentive_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            if($credentials['document']=="Sales Travel claim"){
                $path="sales_travel_claim";
                $sales_travel_claim_file_name=$file_name;
                if($sales_travel_claim_file_name!=""){
                    $sales_travel_claim_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            if($credentials['document']=="Parental medical reimbursement"){
                $path="parental_medical_reimbursement";
                $parental_medical_reimbursement_file_name=$file_name;
                if($parental_medical_reimbursement_file_name!=""){
                    $parental_medical_reimbursement_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            // type 2
            if($credentials['document']=="PF"){
                $path="pf";
                $pf_file_name=$file_name;
                if($pf_file_name!=""){
                    $pf_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            if($credentials['document']=="Gratuity"){
                $path="gratuity";
                $gratuity_file_name=$file_name;
                if($gratuity_file_name!=""){
                    $gratuity_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            // end type 2

            if($credentials['document']=="Others"){
                $path="others_doc";
                $others_doc_file_name=$file_name;
                if($others_doc_file_name!=""){
                    $others_doc_file_url="query/".$emp_id."/".$path."/".$file_name."";
                }
            }
            
            // make mail doc end 

        $count++;
        }
        
        $unique_file_type_1=array_unique($string_file_type);
        $unique_file_type=array_values($unique_file_type_1);

        $all_submit_doc=implode(",",$unique_file_type);

        $final_pay_slip_file_url=implode(",",$pay_slip_file_url);
        $final_form16_file_url=implode(",",$form16_file_url);

        // get query ticket base and verify its completed or not

        $credentials=[
            'ticket_id'=>$ticket_id, 
        ];

        $get_ticket_row = $this->query_task->get_query_u_tic_id( $credentials['ticket_id'] );

        $q_r_array_doc=explode(",",$get_ticket_row[0]->document);

        $update_query_doc = $this->query_task->get_updated_doc_detail( $credentials );

        $completed_doc=array();
        foreach($update_query_doc as $doc_row){
            $completed_doc[]= $doc_row->document;
        }

        $document_different_0=array_diff($q_r_array_doc,$completed_doc);

        $check_diff=array();
        $document_different=array();
        foreach ($document_different_0 as $a) {
            if($a!=""){
                $document_different[]= $a;
            }
        } 

        if(count($document_different)!==0){

            $status="Approved";
        }
        else{
            $status="Completed";

        }

        // end get query ticket base and verify its completed or not 

        // update query status to completed
        $update_credentials=[
            'ticket_id'=>$ticket_id, 
            // 'admin_remark'=>$remark,
            'status'=>$status,
            'updated_by'=>session()->get('emp_id'),
        ];
        // $update_query = $this->query_task->update_query_status_and_rem( $update_credentials );

        // send mail to employee
        
        // get user id
        // $get_ticket_row = $this->query_task->get_query_u_tic_id( $credentials['ticket_id'] );

        $emp_id = $get_ticket_row[0]->emp_id;
        $getempdetail = $this->emp_task->get_employee_detail( $emp_id );
        // send query mail
        // To Master Mail 
        $company_email = $getempdetail[0]->email;
        // $company_email ="lakshminarayanan@hemas.in"; 

        $body_content1 = "Dear ".$getempdetail[0]->emp_name;
        $body_content2 = "Ticket ID: ".$ticket_id;
        $body_content3 = 'Your Query Moved to: '.$get_ticket_row[0]->status.' Status';
        $body_content4 = 'Pls raise a query in your login if you need further assistance,';
        $body_content5 = "https://citpl_alumni.cavinkare.in/index.php/login"; 
        $body_content6 = "Cheers";
        $body_content7 = "Team HR"; 


        $details = [
            'subject' => 'CITPL',
            'title' => 'Your Query Moved to: '.$get_ticket_row[0]->status,
            'body_content1' => $body_content1,
            'body_content2' => $body_content2,
            'body_content3' => $body_content3,
            'body_content4' => $body_content4, 
            'body_content5' => $body_content5,  
            'body_content6' => $body_content6, 
            'body_content7' => $body_content7, 
            
            'pay_slip_file_url' => $final_pay_slip_file_url, 
            'ff_statement_file_url' => $ff_statement_file_url, 
            'form16_file_url' => $final_form16_file_url, 
            'rel_letter_file_url' => $rel_letter_file_url, 
            'ser_letter_file_url' => $ser_letter_file_url, 

            'bonus_file_url' => $bonus_file_url, 
            'performance_incentive_file_url' => $performance_incentive_file_url, 
            'sales_travel_claim_file_url' => $sales_travel_claim_file_url, 
            'parental_medical_reimbursement_file_url' => $parental_medical_reimbursement_file_url, 
            
            // t2
            'pf_file_url' => $pf_file_url, 
            'gratuity_file_url' => $gratuity_file_url, 
            // t2 end
            'others_doc_file_url' => $others_doc_file_url, 
            'all_submit_doc' => $all_submit_doc,

        ];



        // send 2 nd mail method two
        // $footer_img='<img src="https://citpl_alumni.cavinkare.in/assets/img/logo.png" alt="" style="width:90px;">';
        // $footer_th='<p>Thank you</p>';
        // $footer_ad='<b>The Cavinkare Team</b>';

        // $to      = $company_email;
        // $subject = $details['subject'];
        // $message = '<html>
        // <body><p>'.$body_content1."</p>\r\n<h3>".$body_content2."</h3>\r\n<p>".$body_content3."</p>\r\n<p>".$body_content4."</p>\r\n<p>".$body_content5."</p>\r\n".$footer_img."\r\n".$footer_th."\r\n".$footer_ad."</body>
        // </html>";


        // $email = new PHPMailer();
        // $email->SetFrom('ambassador@cavinkare.com', 'Cavinkare'); //Name is optional
        // $email->Subject   = $subject;
        // $email->Body      = $message;
        // $email->AddAddress( $to );
        // $email->IsHTML(true); 

        // if($details['pay_slip_file_url']!==""){
        //     $file_to_attach = public_path($details['pay_slip_file_url']);
        //     $email->AddAttachment( $file_to_attach );
        // }
        // if($details['ff_statement_file_url']!==""){
        //     $file_to_attach = public_path($details['ff_statement_file_url']);
        //     $email->AddAttachment( $file_to_attach );
        // }
        // if($details['form16_file_url']!==""){
        //     $file_to_attach = public_path($details['form16_file_url']);
        //     $email->AddAttachment( $file_to_attach );
        // }
        // if($details['rel_letter_file_url']!==""){
        //     $file_to_attach = public_path($details['rel_letter_file_url']);
        //     $email->AddAttachment( $file_to_attach );
        // }
        // if($details['ser_letter_file_url']!==""){
        //     $file_to_attach = public_path($details['ser_letter_file_url']);
        //     $email->AddAttachment( $file_to_attach );
        // }
        // if($details['others_doc_file_url']!==""){
        //     $file_to_attach = public_path($details['others_doc_file_url']);
        //     $email->AddAttachment( $file_to_attach );
        // } 


        // $email->Send();

        // send 2 nd mail method two end
    
        // in proper laravel method mail send plz enable below link
        try {
            \Mail::to($company_email)->send(new \App\Mail\QueryUpdate_doc_2($details));
        } catch (\Exception $e) {
            // Log the email error but don't break the process
            \Log::error('Email sending failed in doc_upload_admin_submit', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticket_id,
                'email' => $company_email
            ]);
        }

        // send mail to employee end

        $response= "success";
        return response()->json( ['response' => $response,'sed_mail_to'=>$company_email] );
    }
    //reassign 
    public function reassign_query_status(Request $request) 
    {
        $credentials=[
            'ticket_id'=>$request->input('ticket_id'),
            'emp_id'=>$request->input('emp_id'),
        ];

        $reassign_query = $this->query_task->reassign_query_status_doc( $credentials );
        // print_r($reassign_query);
       return json_encode($reassign_query);

    }
    public function update_reassign_form(Request $request){
        $mes = ['assign_to.required'=>"Select Department in field is required.",
        ];
        $validator= Validator::make($request->all(),[
            'assign_to' =>'required',
            ],$mes);
            $user=$request->assign_to;
            $document=array();
            if($user=="Claims"){
                $document=["Sales Travel claim"];
            }
            else if($user=="Payroll_Finance"){
                $document=["Form 16"];
            }
            else if($user=="F_F_HR"){
                $document=["F&F Statement","Relieving Letter","Service Letter","Others","PF"];
            }
            else if($user=="Payroll_HR"){
                $document=["Pay Slips","Performance Incentive","Bonus","Parental medical reimbursement","Gratuity"];
            }
            $document_string=implode(",",$document);
            if($validator->passes()){
                $credential = [
                    'emp_id' => $request->u_emp_id,
                    'ticket_id' => $request->ticket_id,
                    'from_docu'=> $request->from_docu, 
                    'to_docu'=> $document_string,
                    'created_by' => "HRL001",
                    'assign_to' => $user,
                    'assign_from'=> $request->assign_from, 
                    'updated_by'=>session()->get('emp_id'),
                    'status' =>'Approved',   
                ];
                $document2=$request->from_docu;
                $document_string2=explode(",",$document2);

                foreach($document_string2 as $row2){
                    $credentials3['document']=$row2;
                    $credentials3['ticket_id']=$request->ticket_id;
                    $doc = $this->query_task->deleteDocEntry($credentials3);
                }
                $update = $this->query_task->update_reassign_form($credential);
                foreach($document as $row){
                    $credentials2['status'] ='Pending';
                    $credentials2['document']=$row;
                    $credentials2['ticket_id']=$request->ticket_id;
                    $doc = $this->query_task->QueryDocEntry($credentials2);
                }
                return response()->json(['res'=>"success"]);
            }else{
                return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            }
       
    }
    
}
