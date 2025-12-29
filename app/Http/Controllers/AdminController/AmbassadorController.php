<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\IEmpRepository;
use App\Repositories\IQueryRepository;
use App\Repositories\IDocRepository;
use DataTables;
use Image;
use Mail;
use Illuminate\Support\Str;
use DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 
use App\Imports\UsersImport;
use App\Models\emp_profile_tbl; 
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;
use Illuminate\Validation\Rule;
use App\Repositories\IF_F_tracker_Repository;
use App\Repositories\ICheck_point_Repository;
use Validator;
use PDF;
use App;
use File;
use App\Mail\checklistNotifyMail;
use App\Mail\NewCaseEmailNotification;


class AmbassadorController extends Controller
{
    public function __construct( IDocRepository  $doc_task,IEmpRepository $emp_task,IQueryRepository  $query_task,ICheck_point_Repository  $check_point_task, IF_F_tracker_Repository $f_f_tracker_task ) {
        $this->middleware( 'adminLog' ); 
        $this->query_task = $query_task;
        $this->emp_task = $emp_task;
        $this->doc_task = $doc_task;
        $this->check_point_task = $check_point_task;
        $this->f_f_tracker_task = $f_f_tracker_task;

    }
    public function alumni_manage_landing()
    {
        return view('Admin.ambassador_manage_landing');
    }
    public function view_alumni_landing()
    {
        return view('Admin.view_ambassador_landing');
    }

    public function view_reg_alumni_landing()
    {
        return view('Admin.view_registered_ambassador_landing');
    }

    public function downloadPDF(Request $request){

        // $request->emp_id="EMP003";

        $checkdetail=[
            'emp_id'=>$request->emp_id,
        ];

        
        $get_emp = $this->emp_task->get_employee_detail( $checkdetail['emp_id'] );

        $get_questions = $this->check_point_task->get_c_p_data( $checkdetail );

        $pfd_data = [
            'get_emp' => $get_emp,
            'get_questions' => $get_questions, 
        ];
        
        $pdf = PDF::loadView('pdf', $pfd_data);
        $path = public_path().'/F_F_check_point/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $fileName = ''.$request->emp_id.'-F_F-check-point.pdf';
        $pdf->save($path . '/' . $fileName);
        return '../F_F_check_point/'.$fileName;
    }


    public function f_and_f_document_popup(Request $request)
    {
        $checkdetail=[
            'emp_id'=>$request->emp_id,
        ];
        $get_emp = $this->emp_task->get_employee_detail( $checkdetail['emp_id'] );

        $data="";
        $data .= '<table class="table table-bordered" id="ff_checkpoint_tbl">';
        $data .= '<tr style="width:2px;"><td colspan=""><b>Employee ID : '.$request->emp_id.'</b></td><td colspan="4"><b>Employee Name : '.$get_emp[0]->emp_name.'</b></td></tr>';
        $data .= '<tr><td class="text-dark" width="10%"><b>S.No</b></td><td class="text-dark" width="70%"><b>Questions</b></td><td class="text-dark" width="5%"><b>Ratings</b></td><td class="text-dark" width="10%"><b>Remarks</b></td><td class="text-dark" width="5%"><b>Checked By</b></td></tr>';
        $data .= '<tr>';

        $get_questions = $this->check_point_task->get_c_p_data( $checkdetail );

        $i=1;
        foreach($get_questions as $row){

            $checkdetail=[
                'question_id'=>$row->question_id,
                'emp_id'=>$request->emp_id,
            ];

            $get_emp_q_rec = $this->check_point_task->check_q_availablity( $checkdetail );

            $data .= '<tr>';
            $data .= '<td>'.$i.'</td>';
            $data .= '<td>'.$row->questions.'</td>';
            if(isset($get_emp_q_rec[0]))
            {
                if($get_emp_q_rec[0]->rating==""){
                    $data .= '<td style="color:red;">-</td>';
                    $data .= '<td style="color:red;">-</td>';
                    $data .= '<td style="color:red;">-</td>';
                }
                else if($get_emp_q_rec[0]->rating=="Yes"){
                    $data .= '<td>'.$get_emp_q_rec[0]->rating.'</td>';
                    $data .= '<td>'.$get_emp_q_rec[0]->remarks.'</td>';
                    $get_admin_tbl = $this->check_point_task->get_admin_tbl('admin_tbls','emp_id',$get_emp_q_rec[0]->created_by);
                    $data .= '<td>'.$get_admin_tbl[0]->department.'</td>';
                }
                else if($get_emp_q_rec[0]->rating!=="Yes"){
                    $data .= '<td style="background-color:#f305052b;">'.$get_emp_q_rec[0]->rating.'</td>';
                    $data .= '<td>'.$get_emp_q_rec[0]->remarks.'</td>';
                    $get_admin_tbl = $this->check_point_task->get_admin_tbl('admin_tbls','emp_id',$get_emp_q_rec[0]->created_by);
                    $data .= '<td>'.$get_admin_tbl[0]->department.'</td>';
                }
            }
            else{
                $data .= '<td style="color:red;">-</td>';
                $data .= '<td style="color:red;">-</td>';
                $data .= '<td style="color:red;">-</td>';
            }
            $data .= '</tr>';
            $i++;
        }
        $data .= '</tr></table>';

        return response()->json(['response'=>'Success','data'=>$data]);
    }


    public function get_all_alumni_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            if($start_date || $end_date ){
                $filter_data = [ 
                    'start_date' => $start_date,
                    'end_date'=> $end_date,  
                ];

                $getquerydetails = $this->doc_task->get_all_ambassador($filter_data);
            }  
            else{
                $filter_data = [ 
                    'user_type' => session()->get('user_type'),
                ];
                $getquerydetails = $this->doc_task->get_all_ambassador_default($filter_data);
            }
            $details=DB::table('f_f_check_points')->select('f_f_check_points.emp_id')->get();
            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );
                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){
                                $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                                $count_i++;
                            }
                            $unique_doc_array_1=array_unique($pre_doc_uploaded);
                            $unique_doc_array=array_values($unique_doc_array_1);

                            $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity"); 
                            $check_diff_array=array_diff($marks,$unique_doc_array);
                            $check_diff=array();
                            foreach ($check_diff_array as $a) {
                                $check_diff[]= $a;
                            } 
                            $document_string=implode(",",$check_diff);
                            $action = "";
                            if(session()->get('user_type') == "HR-LEAD"){
                                $action.= '<a href="#" class="btn btn-sm  btn-warning mr-1 mb-1" data-toggle="tooltip" data-placement="bottom" title="Reset Password" onclick="reset_ambassador_password('."'".$row->emp_id."'".','."'".$row->emp_name."'".');"><i class="fa fa-undo" aria-hidden="true"></i>&nbsp;</a>';
                                $action.= '<a href="#" class="btn btn-sm  btn-info  mr-1 mb-1" data-toggle="tooltip" data-placement="bottom" title="Edit Employee" onclick="edit_ambassador('."'".$row->id."'".','."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$row->pan_no."'".','."'".$row->dob."'".','."'".$row->mobileno."'".','."'".$row->email."'".','."'".$row->last_working_date."'".');"><i class="fas fa-edit"></i>&nbsp;</a>';
                            }
                            if(session()->get('user_type') != "Payroll_Finance"){
                                $action.= '<a href="#" class="btn btn-sm  btn-success  mr-1 mb-1" data-toggle="tooltip" data-placement="top" title="View Document Details" onclick="doc_detail('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$row->remark_2."'".');"><i class="fas fa-eye"></i>&nbsp;</a>';
                            }
                            if($row->f_f_document == "Yes"){
                                $action.= '<a  href="#"  class="btn btn-primary btn-sm mb-1" data-toggle="tooltip" data-placement="bottom" title="F&F Check Points PDF" title="F&F Check Points PDF" data-controls-modal="f_and_f_document_popup" data-backdrop="static" data-keyboard="false"    onclick="f_and_f_document_popup('."'".$row->emp_id."'".');"><i class="fas fa-flag"></i>&nbsp;</a>';
                            }
                        return $action;
                    })
                    ->addColumn('remark', function($row){
                        $remark = $row->remark;
                        return $remark;
                    })
                    ->addColumn('emp_name', function($row){
                        $emp_name = $row->emp_name;
                        // Remove employee ID if it exists in the format "Name - ID"
                        if (strpos($emp_name, ' - ') !== false) {
                            $emp_name = explode(' - ', $emp_name)[0];
                        }
                        return $emp_name;
                    })
                    ->addColumn('type_of_leaving', function($row){
                        if(!$row->type_of_leaving== ""|| !$row->type_of_leaving== null){
                            $type_of_leaving = '';
                            if($row->type_of_leaving == "Abscond" || $row->type_of_leaving == "Terminated"){
                                $type_of_leaving.='<div class="badge badge-danger doc_name">'.$row->type_of_leaving.'</div><br>';
                            }
                            elseif($row->type_of_leaving == "Transferred"){
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
                    ->addColumn('document_div', function($row) {
                        $document_div='';
                        if(session()->get('user_type') == "Payroll_Finance"){

                            $department = array("Claims","HR-LEAD","IT-INFRA","Payroll_Finance","Payroll_HR","Payroll_IT");

                            $departments = array("Claims","HR LEAD","IT INFRA","Payroll Finance","Payroll HR","Payroll IT");
                            $department2 =array();
                            $get_admin_tbl = $this->doc_task->get_admin_tbl('admin_tbls','department',$departments,'emp_id');
                            foreach($get_admin_tbl as $dep){
                                $department2[] = $dep->emp_id;
                            }
                            $counts=array();
                            $counts_entry=array();
                            for($i=0;$i<count($department);$i++){
                                $question_count = $this->doc_task->get_count('questions_table','role_id',$department[$i]);
                                $counts[] = $question_count;
                            }
                            for($j=0;$j<count($department2);$j++){
                                $empty="";
                                $question_entry_count = $this->doc_task->get_entry_count('f_f_check_points','created_by',$department2[$j],'emp_id',$row->emp_id,'rating',$empty,'!=');
                                $counts_entry[] = $question_entry_count;
                            }


                            for($k=0;$k<count($department);$k++){
                                if($counts[$k] == $counts_entry[$k]){
                                    $document_div.='<div class="badge badge-success doc_name">'.$department[$k].'</div><br>';
                                }else if($counts_entry[$k] == 0){
                                    $document_div.='<div class="badge badge-danger doc_name">'.$department[$k].'</div><br>';
                                }else{
                                    $document_div.='<div class="badge text-white  doc_name" style="background-color:rgb(247,124,3);">'.$department[$k].'</div><br>';
                                }
                            }
                        }else{
                            $get_doc_rows = $this->doc_task->get_doc_entry("emp_id",$row->emp_id);
                            // $get_tracker_files = $this->doc_task->get_data_with_where2('f__f_tracker_files',"emp_id",$row->emp_id,'s_g_id',10);
                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){
                                $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                                $count_i++;
                            }
                            $unique_doc_array_1=array_unique($pre_doc_uploaded);

                            // if(isset($get_tracker_files[0])){
                            //     $unique_doc_array1=array_values($unique_doc_array_1);
                            //     $sg_10_docs = array("F&F Statement","Relieving Letter","Service Letter");
                            //     $unique_doc_array = array_unique(array_merge($unique_doc_array1,$sg_10_docs));
                            // }else{
                                $unique_doc_array = array_values($unique_doc_array_1);
                            // }


                            $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity"); 
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
                        return $document_div;
                    })
                    ->addColumn('status', function($row){
                        $sts_clr='warning';
                        $status='Profile On Hold';
                        if($row->doc_status=="Fresh"){
                            $sts_clr='warning';
                            $status=$row->doc_status;
                        }
                        else if($row->doc_status=="Pending"){
                            $sts_clr='primary';
                            $status=$row->doc_status;
                        }
                        else if($row->doc_status=="Completed"){
                            $sts_clr='success';
                            $status=$row->doc_status;
                        }
                        $status_btn = '<div class="badge badge-'.$sts_clr.'">'.$status.'</div>';
                        return $status_btn;
                    })
                    ->addColumn('created_at', function($row){
                        $created_at=date('d-m-Y', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->rawColumns(['action','remark' ,'emp_name','type_of_leaving','document_div','status','created_at'])
                    ->make(true);
        }
        return view(' Admin.view_ambassador_landing '); 
    }

    public function get_all_reg_alumni_datatable(Request $request){
      
        if ($request->ajax()) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            if($start_date || $end_date ){
                $filter_data = [ 
                    'start_date' => $start_date,
                    'end_date'=> $end_date, 
                    'status'=> 'Hold', 
                ];
                $getquerydetails = $this->doc_task->get_all_reg_ambassador($filter_data);
            } 
            else{ 
                $filter_data = [ 
                    'status'=> 'Hold', 
                ];
                $getquerydetails = $this->doc_task->get_all_reg_ambassador_default($filter_data);
            }
          
            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $approve="Approved";
                        $decline="Declined";
                        $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Approve Registered Alumni" onclick="update_reg_amb('."'".$row->emp_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>
                            <a href="#" class="btn btn-icon btn-danger ac_btn" title="Decline Registered Alumni" onclick="update_reg_amb('."'".$row->emp_id."'".','."'".$decline."'".');"><i class="fas fa-times"></i></a>';
                        return $action;
                    })
                    
                    
                    ->addColumn('created_at', function($row){
                        $created_at=date('d-m-Y h:i:s', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->rawColumns(['action','created_at'])
                    ->make(true);
        }
        return view(' Admin.view_ambassador_landing ');
    }

    public function get_all_declined_alumni_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            if($start_date || $end_date ){
                $filter_data = [ 
                    'start_date' => $start_date,
                    'end_date'=> $end_date, 
                    'status'=> 'Declined', 
                ];
                $getquerydetails = $this->doc_task->get_all_reg_ambassador($filter_data);
            } 
            else{ 
                $filter_data = [ 
                    'status'=> 'Declined', 
                ];
                $getquerydetails = $this->doc_task->get_all_reg_ambassador_default($filter_data);
            }
            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $approve="Approved";
                        $decline="Declined";
                        $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Approve Registered Alumni" onclick="update_reg_amb('."'".$row->emp_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>';
                        return $action;
                    })
                    
                    
                    ->addColumn('created_at', function($row){
                        $created_at=date('d-m-Y h:i:s', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->rawColumns(['action','created_at'])
                    ->make(true);
        }
        return view(' Admin.view_ambassador_landing ');
    }

    public function update_reg_alumni(Request $request) 
    {

        if($request->input('f_f_document')=='Yes' && $request->input('status')=="Active"){
            $f_f_status="Fresh";
            $f_f_c_s_g = "1";
        }
        else{
            $f_f_status="";
            $f_f_c_s_g ="";
        }
        if($request->input('status')=="Active"){
           
            $validator = Validator::make($request->all(),[
                'type_of_leaving' =>'required',
                'last_working_date' =>'required',
            ]);

            if($validator->passes()){
                // Generate secure password
                $securePassword = $this->generateSecurePassword();
                
                $credentials=[
                    'emp_id'=>$request->emp_id,
                    'status'=>$request->status,
                    'password'=>$securePassword,
                    'doc_status'=>'Fresh', 
                    'type_of_leaving'=>$request->type_of_leaving,
                    'last_working_date'=>$request->last_working_date,
                    'f_f_document'=>$request->f_f_document,
                    'f_f_c_s_g'=>$f_f_c_s_g,
                    'cl_c_p'=>$f_f_status,
                    'fn_c_p'=>$f_f_status,
                    'pr_c_p'=>$f_f_status,
                    'hr_ld_c_p'=>$f_f_status,
                    'it_c_p'=>$f_f_status,
                    'it_inf_c_p'=>$f_f_status,
                    'dec_remark'=>"",
                ];
                $update_query = $this->emp_task->approve_amb_status($credentials);
                // $update_query = $this->emp_task->approve_amb_status($credentials);
 
                $cred = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "0",
                    'to_sg' => "1",
                    'alert_to' => "HR001",
                    'v_status' => "0",
                    'sts' => "Active",
                ];
                $history = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "0",
                    'to_sg' => "1",
                    'created_by' => session('emp_id'),
                    'sender_to' => "HR001",
                ];
                $date_credential=[
                    'emp_id'=>$request->input('emp_id'),
                    's_g'=>"0",
                    'created_by'=>session()->get('emp_id'),
                ];
                $result_history = $this->f_f_tracker_task->save_history($history);
                $result3 = $this->f_f_tracker_task->save_notification($cred);
                $result1 = $this->f_f_tracker_task->insert_date_of_completed( $date_credential );

                $u_type = "IT-INFRA";
                // $infra_mail = $this->f_f_tracker_task->get_email($u_type);
                $u_type = "Claims";
                // $claims_mail = $this->f_f_tracker_task->get_email($u_type);

                // dd($infra_mail->email);
                // $infra_mail = 'srinivasan.d@hepl.com';
                // $claims_mail = 'Arunkumar.b@hepl.com';
                // $claims_mail = 'suba.s@hepl.com';
                $claims_mails = ['Arunkumar.b@hepl.com', 'krishnapriya.d@hepl.com'];
                // $claims_mails = ['vendarsan.m@hepl.com'];


                $body_content_n1 = 'Hi IT Infra Team! ';
                // 'body_content2' => 'Hi, the '.$team.' team has raised a new F & F request that requires your attention.',
                $body_content_n2 = 'New checklist has received for your attention. Kindly please login on to the portal';
                $body_content_n3 = 'https://citpl_alumni.cavinkare.in/index.php/login';
                $body_content_n4 = 'Cheers';
                $body_content_n5 = 'Team HR';

                $body_content_c1 = 'Hi IT Claims! ';
                // 'body_content2' => 'Hi, the '.$team.' team has raised a new F & F request that requires your attention.',
                $body_content_c2 = 'New checklist has received for your attention. Kindly please login on to the portal';
                $body_content_c3 = 'https://citpl_alumni.cavinkare.in/index.php/login';
                $body_content_c4 = 'Cheers';
                $body_content_c5 = 'Team HR';

                $details1 = [
                    'body_content1' => $body_content_n1,
                    'body_content2' => $body_content_n2,
                    'body_content3' => $body_content_n3,
                    'body_content4' => $body_content_n4, 
                    'body_content5' => $body_content_n5, 
                ];

                $details2 = [
                    'body_content1' => $body_content_c1,
                    'body_content2' => $body_content_c2,
                    'body_content3' => $body_content_c3,
                    'body_content4' => $body_content_c4, 
                    'body_content5' => $body_content_c5, 
                ];
                
                // \Mail::to($infra_mail->email)->send(new \App\Mail\checklistNotifyMail($details1));
                // \Mail::to($claims_mail->email)->send(new \App\Mail\checklistNotifyMail($details1));
                // \Mail::to($claims_mail)->send(new \App\Mail\checklistNotifyMail($details2));
                // \Mail::to($infra_mail)->send(new \App\Mail\checklistNotifyMail($details1));
                // foreach ($claims_mails as $claims_mail) {
                //     // print_r($claims_mail);
                //     \Mail::to($claims_mail)->send(new \App\Mail\checklistNotifyMail($details2));
                // }
                $toname = 'HRSS';
                $team = 'Alumni';
                // $tomail = 'hrsupport4@cavinkare.com';
                $tomail = 'hrss@hepl.com';
                $raiseDeti = [
                    'body_content1' => 'Hello '.$toname.'! ',
                    'body_content2' => 'Hi, the '.$team.' team has raised a new F & F request that requires your attention.',
                    'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                    'body_content5' => 'Cheers',
                    'body_content6' => 'Team '.$team,
                ];
                // \Mail::to($tomail)->send(new \App\Mail\NewCaseEmailNotification($raiseDeti));
                $checkdetail=[
                    'emp_id'=>$request->input('emp_id'),
                ];
                $get = $this->emp_task->get_employee_detail( $checkdetail['emp_id'] );
                return response()->json( ['response' => "success",'emp_id'=>$request->emp_id]);
            }else{
                return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            }
        }
        else{
            $validator = Validator::make($request->all(),[
                'dec_remark' =>'required',
            ]);
            if($validator->passes()){
                $credentials=[
                    'emp_id'=>$request->emp_id, 
                    'status'=>$request->status,
                    'dec_remark'=>$request->dec_remark,
                ];
                $update_query = $this->emp_task->declines_amb_status( $credentials );
                return response()->json( ['response' => "success",'emp_id'=>$request->emp_id]);
            }else{
                return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            }
        }
    }
    
    public function send_emp_status_mail(Request $request) 
    {

            $checkdetail=[
                'emp_id'=>$request->input('emp_id'),
            ];
            $get = $this->emp_task->get_employee_detail( $checkdetail['emp_id'] );

            // Generate secure password
            $securePassword = $this->generateSecurePassword();

            // send mail
            // To Master Mail 
            $company_email = $get[0]->email;
            // $company_email ="lakshminarayanan@hemas.in"; 

            $body_content1 = "Hello! ".$get[0]->emp_name;
            $body_content2 = "Welcome onboard the Alumni Portal -  one stop shop for all your queries relating to your tenure at CITPL";
            $body_content3 = 'Please use this one-time password to login and update your password immediately';
            $body_content4 = $get[0]->email; 
            $body_content5 = $securePassword; 
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

            // end send mail

        $response= "success";
        return response()->json( ['response' => $response,'emp_id'=>$request->input('emp_id')]);

    }

    /**
     * Generate secure password using Laravel's built-in method
     */
    private function generateSecurePassword()
    {
        return Str::random(8);
    }

    public function add_alumni_submit(Request $request)
    {
        $dt = new \Carbon\Carbon();
        $before = $dt->subYears(18)->format('d-m-Y');
        $mes = ['name.required'=>"The Employee Name field is required.",
        'emp_id.required'=>"The Employee Id  field is required.",
        'email.required'=>"The Email field is required.",
        'pan_num.required'=>"The Pan Number field is required.",
        'dob.required'=>"The DOB field is required.",
        'mobileno.required'=>"The Contact Number field is required.",
        'type_of_leaving.required'=>"The Type of Leaving field is required.",
        'working_date.required'=>"The Last Working Date field is required.",

        'emp_id.unique'=>"The Employee Id has already been taken.",
        'email.unique'=>"The Email has already been taken.",
        'email.email'=>"The Email must be a valid email address.",
        'pan_num.unique'=>"The Pan Number has already been taken.",
        'mobileno.unique'=>"The Contact Number has already been taken.",
        'mobileno.min'=>"The Contact Number must be at least 10 characters.",
        'pan_num.min'=>"The Pan Number must be at least 10 characters.",

        'dob.before'=>"The DOB must be a date before $before",
    ];
        $validator= Validator::make($request->all(),[
            'name' =>'required|string|max:255',
            'emp_id' =>'required|string|max:50|unique:emp_profile_tbls,emp_id',
            'email' =>'required|email:rfc,dns|max:255|unique:emp_profile_tbls,email',
            'pan_num' =>'required|string|size:10|unique:emp_profile_tbls,pan_no',
            'dob' =>'required|date|before:'.$before,
            'mobileno' =>'required|string|size:10|unique:emp_profile_tbls,mobileno',
            'type_of_leaving' =>'required|in:Relieved,Terminated,Abscond,Transferred',
            'working_date' =>'required|date',
            ],$mes);
        if($validator->passes()){  
        $name = explode(' - ', $request->input('name'))[0];
        $emp_id = trim(str_replace(" ","", $request->input('emp_id')));
        
        // Generate secure password
        $securePassword = $this->generateSecurePassword();
        
        $credentials=[
            'emp_name'=>$name,
            'emp_id'=>$emp_id,
            'pan_no'=>$request->input('pan_num'),
            'dob'=>$request->input('dob'),
            'mobileno'=>$request->input('mobileno'),
            'email'=>$request->input('email'),
            'type_of_leaving'=>$request->input('type_of_leaving'),
            'last_working_date'=>$request->input('working_date'),
            'f_f_document'=>"Yes",
            'f_f_c_s_g'=>"1",

            'cl_c_p'=>"Fresh",
            'fn_c_p'=>"Fresh",
            'pr_c_p'=>"Fresh",
            'hr_ld_c_p'=>"Fresh",
            'it_c_p'=>"Fresh",
            'it_inf_c_p'=>"Fresh",

            'doc_status'=>"Fresh",
            'ff_doc_updated_by'=>"",
            's_doc_updated_by'=>"", 
            'status'=>"Active",
            'password'=>$securePassword,
            'is_first_login'=>true,
        ];

        $check = $this->emp_task->check_row_based_con("emp_id", $credentials['emp_id'] );
        if(isset($check[0])){
            $resp="Employee ID Already Exist";
            return response()->json(['response'=>$resp]);
        }
        $check = $this->emp_task->check_row_based_con("pan_no", $credentials['pan_no'] );
        if(isset($check[0])){
            $resp="Pan No Already Exist";
            return response()->json(['response'=>$resp]);
        }
        $check = $this->emp_task->check_row_based_con("mobileno", $credentials['mobileno'] );
        if(isset($check[0])){
            $resp="Mobile No Already Exist";
            return response()->json(['response'=>$resp]);
        }
        $check = $this->emp_task->check_row_based_con("email", $credentials['email'] );
        if(isset($check[0])){
            $resp="Email Already Exist";
            return response()->json(['response'=>$resp]);
        }
 
        // add ambassador
        $add_ambassador = $this->emp_task->add_ambassador( $credentials ); 

        $date_credential=[
            'emp_id'=>$request->input('emp_id'),
            's_g'=>"0",
            'created_by'=>session()->get('emp_id'),
        ];
        $cred = [
            'emp_id' => $request->emp_id,
            'from_sg' => "0",
            'to_sg' => "1",
            'alert_to' => "HR001",
            'v_status' => "0",
            'sts' => "Active",
        ];
        $history = [
            'emp_id' => $request->emp_id,
            'from_sg' => "0",
            'to_sg' => "1",
            'created_by' => session('emp_id'),
            'sender_to' => "HR001",
        ];
        $result_history = $this->f_f_tracker_task->save_history($history);
        $result3 = $this->f_f_tracker_task->save_notification($cred);
        $result1 = $this->f_f_tracker_task->insert_date_of_completed( $date_credential );
        // send mail
        // To Master Mail 
        $company_email = $credentials['email'];
        // $company_email ="lakshminarayanan@hemas.in"; 

        $body_content1 = "Hello! ".$credentials['emp_name'];
        $body_content2 = "Welcome onboard the Alumni Portal -  one stop shop for all your queries relating to your tenure at CITPL";
        $body_content3 = 'Please use this one-time password to login and update your password immediately';
        $body_content4 = $credentials['email']; 
        $body_content5 = $securePassword; 
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
        \Mail::to($company_email)->send(new \App\Mail\Amb_Welcome($details));
            $u_type = "IT-INFRA";
            // $infra_mail = $this->f_f_tracker_task->get_email($u_type);
            $u_type = "Claims";
            // $claims_mail = $this->f_f_tracker_task->get_email($u_type);

            // dd($infra_mail->email);
            // $infra_mail = 'srinivasan.d@hepl.com';
            // $claims_mail = 'Arunkumar.b@hepl.com';
            $claims_mails = ['Arunkumar.b@hepl.com', 'krishnapriya.d@hepl.com'];
            // $claims_mails = ['vendarsan.m@hepl.com'];


            $body_content_n1 = 'Hi IT Infra Team! ';
            // 'body_content2' => 'Hi, the '.$team.' team has raised a new F & F request that requires your attention.',
            $body_content_n2 = 'Please login to the portal to review the new checklist for '.$credentials['emp_id'].' that requires your attention.';
            $body_content_n3 = 'https://citpl_alumni.cavinkare.in/index.php/login';
            $body_content_n4 = 'Cheers';
            $body_content_n5 = 'Team HR';

            $body_content_c1 = 'Hi IT Claims! ';
            // 'body_content2' => 'Hi, the '.$team.' team has raised a new F & F request that requires your attention.',
            $body_content_c2 = 'Please login to the portal to review the new checklist for '.$credentials['emp_id'].' that requires your attention.';
            $body_content_c3 = 'https://citpl_alumni.cavinkare.in/index.php/login';
            $body_content_c4 = 'Cheers';
            $body_content_c5 = 'Team HR';

            $details1 = [
                'body_content1' => $body_content_n1,
                'body_content2' => $body_content_n2,
                'body_content3' => $body_content_n3,
                'body_content4' => $body_content_n4, 
                'body_content5' => $body_content_n5, 
            ];

            $details2 = [
                'body_content1' => $body_content_c1,
                'body_content2' => $body_content_c2,
                'body_content3' => $body_content_c3,
                'body_content4' => $body_content_c4, 
                'body_content5' => $body_content_c5, 
            ];
            
            // \Mail::to($infra_mail->email)->send(new \App\Mail\checklistNotifyMail($details1));
            // \Mail::to($claims_mail->email)->send(new \App\Mail\checklistNotifyMail($details1));
            // \Mail::to($claims_mail)->send(new \App\Mail\checklistNotifyMail($details2));
            // \Mail::to($infra_mail)->send(new \App\Mail\checklistNotifyMail($details1));
            // foreach ($claims_mails as $claims_mail) {
            //     \Mail::to($claims_mail)->send(new \App\Mail\checklistNotifyMail($details2));
            // }
            

        $resp="Success";
        return response()->json(['response'=>$resp]);
    }else{
            return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
        }
    }

   

    public function amb_bulk_upl_submit(Request $request) 
    {
        try{
            if( $request->file('upload_file') ) {
                $path1 = $request->file('upload_file')->store('temp'); 
                $path=storage_path('app').'/'.$path1;  
            } else {
                $path =""; 
            }
            $data = \Excel::import(new UsersImport,$path);
            $res['response'] ="Success";
        }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = 'Errors!<br>';
            foreach ($failures as $failure) {
                if(isset($failure->errors()[0])){
                    $messages .= 'Row '.$failure->row(). ' : '.$failure->errors()[0].'<br>';
                }
            }
            $res['messages'] = $messages;
            $res['response'] = "Failed";
       }

        return response()->json($res);
    } 

    public function update_amb_form(Request $request){
        $dt = new \Carbon\Carbon();
        $before = $dt->subYears(18)->format('d-m-Y');
        $mes = ['name.required'=>"The Employee Name field is required.",
        'email.required'=>"The Email field is required.",
        'pan_num.required'=>"The Pan Number field is required.",
        'dob.required'=>"The DOB field is required.",
        'mobileno.required'=>"The Contact Number field is required.",
        'type_of_leaving.required'=>"The Type of Leaving field is required.",
        'working_date.required'=>"The Last Working Date field is required.",
        'email.unique'=>"The Email has already been taken.",
        'email.email'=>"The Email must be a valid email address.",
        'pan_num.unique'=>"The Pan Number has already been taken.",
        'mobileno.unique'=>"The Contact Number has already been taken.",
        'dob.before'=>"The DOB must be a date before $before",
        ];
        $validator= Validator::make($request->all(),[
            'name' =>'required',
            'lwd' =>'required',
            'email' => [
                'required','email:rfc,dns',
                Rule::unique('emp_profile_tbls', 'email')->ignore($request->u_id)
            ],
            'pan_num' => [
                'required',
                Rule::unique('emp_profile_tbls', 'pan_no')->ignore($request->u_id)
            ],
            'mobileno' => [
                'required',
                Rule::unique('emp_profile_tbls', 'mobileno')->ignore($request->u_id)
            ],
            'dob' =>'required|before:'.$before,
            ],$mes);

            if($validator->passes()){
                $credential = [
                    'emp_id' => $request->u_emp_id,
                    'emp_name' => $request->name,
                    'pan_no' => $request->pan_num,
                    'dob' => $request->dob,
                    'mobileno' => $request->mobileno,
                    'email' => $request->email,
                    'lwd' => $request->lwd,
                ];
                $update = $this->emp_task->update_amb_form($credential);
                return response()->json(['res'=>"success"]);
            }else{
                return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            }
       
    }

    public function reset_password(Request $request){
        // Generate secure password
        $securePassword = $this->generateSecurePassword();
        
        $credential = [
            'emp_id' => $request->emp_id,
            'password' => $securePassword,
            'mark_not_first_login' => false, // Reset to first login
        ];
        
        // Update password and set as first login
        $update = $this->emp_task->upd_amb_pass_u_empid($credential);
        
        // Also update is_first_login to true
        DB::table('emp_profile_tbls')
            ->where('emp_id', $request->emp_id)
            ->update(['is_first_login' => true]);
        
        return response()->json(['res'=>"success", 'new_password' => $securePassword]);
    }

    public function form16_bulk_upload(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'form16_files' => 'required|array|min:1',
                'form16_files.*' => 'required|file|mimes:pdf|max:10240' // 10MB max per file
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $files = $request->file('form16_files');
            
            $upload_results = [];
            $success_count = 0;
            $failed_count = 0;
            $total_files = count($files);
            $employee_files = []; // Array to collect files per employee

            // Base documents directory (same as other document uploads)
            $base_documents_path = public_path('documents');
            
            // Create base documents directory if it doesn't exist
            if (!file_exists($base_documents_path)) {
                mkdir($base_documents_path, 0777, true);
            }

            foreach ($files as $file) {
                $result = [
                    'filename' => $file->getClientOriginalName(),
                    'status' => 'failed',
                    'message' => 'Unknown error'
                ];

                try {
                    // Get original filename
                    $filename = $file->getClientOriginalName();
                    
                    // Auto-detect form type and validate filename format
                    $validation_result = $this->validateAndDetectFormType($filename);
                    
                    if (!$validation_result['valid']) {
                        $result['message'] = $validation_result['message'];
                        $upload_results[] = $result;
                        $failed_count++;
                        continue;
                    }
                    
                    $pan_number = $validation_result['pan'];
                    $document_type = 'Form 16'; // Always save as "Form 16" regardless of Part A or B

                    // Find employee by PAN number
                    $employee = DB::table('emp_profile_tbls')
                        ->where('pan_no', $pan_number)
                        ->first();

                    if (!$employee) {
                        $result['message'] = "Employee not exist for this PAN no: $pan_number";
                        $upload_results[] = $result;
                        $failed_count++;
                        continue;
                    }

                    // Create employee-specific directory structure: documents/{emp_id}/form16/
                    $employee_documents_path = $base_documents_path . '/' . $employee->emp_id;
                    $form16_documents_path = $employee_documents_path . '/form16';
                    
                    // Create directories if they don't exist
                    if (!file_exists($employee_documents_path)) {
                        mkdir($employee_documents_path, 0777, true);
                    }
                    if (!file_exists($form16_documents_path)) {
                        mkdir($form16_documents_path, 0777, true);
                    }
                    
                    // Keep original filename for storage (same as database)
                    $original_filename = $file->getClientOriginalName();
                    
                    
                    
                    // Check if file already exists
                    $final_filename = $original_filename;
                    if (file_exists($form16_documents_path . '/' . $final_filename)) {
                        $result['message'] = 'File already exists: ' . $original_filename;
                        $failed_count++;
                        $upload_results[] = $result;
                        continue; // Skip this file and continue with next one
                    }
                    
                    // Move file to the expected directory structure
                    $file->move($form16_documents_path, $final_filename);

                    // Save to database with the final filename (in case of duplicates)
                    DB::table('amb_document_tbls')->insert([
                        'emp_id' => $employee->emp_id,
                        'document' => $document_type,
                        'file_name' => $final_filename, // Store final filename (handles duplicates)
                        'status' => 'Active',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Collect files for this employee to send email later
                    if (!isset($employee_files[$employee->emp_id])) {
                        $employee_files[$employee->emp_id] = [
                            'employee' => $employee,
                            'files' => []
                        ];
                    }
                    $employee_files[$employee->emp_id]['files'][] = $form16_documents_path . '/' . $final_filename;

                    $result['status'] = 'success';
                    $result['message'] = "Successfully uploaded as Form 16 for employee: {$employee->emp_id}";
                    $success_count++;

                } catch (\Exception $e) {
                    $result['message'] = 'Upload failed: ' . $e->getMessage();
                    $failed_count++;
                }

                $upload_results[] = $result;
            }

            // Send emails to employees after all files are processed
            foreach ($employee_files as $emp_id => $emp_data) {
                $this->sendForm16Email($emp_data['employee'], $emp_data['files']);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bulk upload completed',
                'total_files' => $total_files,
                'success_count' => $success_count,
                'failed_count' => $failed_count,
                'results' => $upload_results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bulk upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-detect form type and validate filename format
     * Form 16 Part A: PAN_YEAR.pdf (e.g., AVDPP2235J_2025-26.pdf)
     * Form 16 Part B: PAN_PARTB_YEAR.pdf (e.g., AVDPP2235J_PARTB_2025-26.pdf)
     */
    private function validateAndDetectFormType($filename)
    {
        // Remove extension
        $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);
        $filename_upper = strtoupper($filename_without_ext);
        
        // Check for Form B format first (contains PARTB)
        $pattern_b = '/^([A-Z0-9]{10})_PARTB_(\d{4}-\d{2})$/';
        if (preg_match($pattern_b, $filename_upper, $matches)) {
            $pan = $matches[1];
            $year = $matches[2];
            
            return [
                'valid' => true,
                'pan' => $pan,
                'year' => $year,
                'document_type' => 'Form 16 Part B',
                'form_type' => 'B',
                'message' => 'Valid Form 16 Part B format'
            ];
        }
        
        // Check for Form A format (no PARTB)
        $pattern_a = '/^([A-Z0-9]{10})_(\d{4}-\d{2})$/';
        if (preg_match($pattern_a, $filename_upper, $matches)) {
            $pan = $matches[1];
            $year = $matches[2];
            
            return [
                'valid' => true,
                'pan' => $pan,
                'year' => $year,
                'document_type' => 'Form 16 Part A',
                'form_type' => 'A',
                'message' => 'Valid Form 16 Part A format'
            ];
        }
        
        return [
            'valid' => false,
            'pan' => null,
            'year' => null,
            'document_type' => null,
            'form_type' => null,
            'message' => "Invalid filename format. Expected formats:\n" .
                        "Form 16 Part A: PAN_YEAR.pdf (e.g., AVDPP2235J_2025-26.pdf)\n" .
                        "Form 16 Part B: PAN_PARTB_YEAR.pdf (e.g., AVDPP2235J_PARTB_2025-26.pdf)\n" .
                        "Got: $filename"
        ];
    }

    /**
     * Extract PAN number from filename (legacy function - kept for backward compatibility)
     */
    private function extractPanFromFilename($filename)
    {
        // Remove extension
        $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);
        
        // Standard PAN format: 5 letters + 4 digits + 1 letter (e.g., ABCDE1234F)
        $pattern1 = '/[A-Z]{5}[0-9]{4}[A-Z]{1}/';
        if (preg_match($pattern1, strtoupper($filename_without_ext), $matches)) {
            return $matches[0];
        }
        
        // Alternative PAN format with separators: 5 letters + 4 digits + 1 letter
        $pattern2 = '/[A-Z]{5}[-_\s]?[0-9]{4}[-_\s]?[A-Z]{1}/';
        if (preg_match($pattern2, strtoupper($filename_without_ext), $matches)) {
            return preg_replace('/[-_\s]/', '', $matches[0]);
        }
        
        // Non-standard PAN format: 3 letters + 7 digits (e.g., GTR5665555)
        $pattern3 = '/[A-Z]{3}[0-9]{7}/';
        if (preg_match($pattern3, strtoupper($filename_without_ext), $matches)) {
            return $matches[0];
        }
        
        // More flexible pattern: letters + digits combination (minimum 8 characters)
        $pattern4 = '/[A-Z]{2,5}[0-9]{4,8}[A-Z]?/';
        if (preg_match($pattern4, strtoupper($filename_without_ext), $matches)) {
            return $matches[0];
        }
        
        // If no pattern matches, try to extract the main part of filename (before any special characters)
        $pattern5 = '/^([A-Z0-9]+)/';
        if (preg_match($pattern5, strtoupper($filename_without_ext), $matches)) {
            // Only return if it looks like it could be a PAN (has both letters and numbers)
            if (preg_match('/[A-Z]/', $matches[1]) && preg_match('/[0-9]/', $matches[1])) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Test filename validation function (for debugging)
     * You can call this via route to test filename validation
     */
    public function testPanExtraction(Request $request)
    {
        $filename = $request->input('filename', 'AVDPP2235J_2025-26.pdf');
        
        // Test new auto-detection function
        $validation_result = $this->validateAndDetectFormType($filename);
        
        // Test legacy extraction function
        $legacy_pan = $this->extractPanFromFilename($filename);
        
        return response()->json([
            'filename' => $filename,
            'auto_detection_result' => $validation_result,
            'legacy_extracted_pan' => $legacy_pan,
            'status' => $validation_result['valid'] ? 'success' : 'failed'
        ]);
    }
    
    /**
     * Send Form 16 email to employee with all their files
     */
    private function sendForm16Email($employee, $form16_file_paths)
    {
        try {
            // Prepare email content similar to F&F Tracker format
            $body_content1 = "Hello! " . $employee->emp_name;
            $body_content2 = "Please find attached your";
            $body_content3 = 'Pls raise a query in your login if you need further assistance,';
            $body_content4 = "https://citpl_alumni.cavinkare.in/index.php/login";
            $body_content5 = "Cheers";
            $body_content6 = "Team HR";

            // Convert array of file paths to comma-separated string (similar to F&F Tracker format)
            $form16_files_string = is_array($form16_file_paths) ? implode(',', $form16_file_paths) : $form16_file_paths;

            $details = [
                'subject' => 'Form 16 Document',
                'title' => 'Your Form 16 Document - CITPL',
                'body_content1' => $body_content1,
                'body_content2' => $body_content2,
                'body_content3' => $body_content3,
                'body_content4' => $body_content4,
                'body_content5' => $body_content5,
                'body_content6' => $body_content6,
                'form16_file_url' => $form16_files_string,
            ];

            // Send email to employee
            \Mail::to($employee->email)->send(new \App\Mail\Form16_doc($details));
            
        } catch (\Exception $e) {
            // Log error but don't stop the upload process
            \Log::error('Failed to send Form 16 email to ' . $employee->email . ': ' . $e->getMessage());
        }
    }





}
