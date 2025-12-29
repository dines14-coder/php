<?php

namespace App\Http\Controllers\S_AdminController;

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
    public function __construct( IEmpRepository $emp_task,IDocRepository  $doc_task ) {
        $this->middleware( 'adminLog' );
        $this->emp_task = $emp_task;
        $this->doc_task = $doc_task;
    }
    public function document_manage_landing()
    {
        return view('S_Admin.document_manage_landing');
    }

    public function p_s_document_manage_landing()
    {
        return view('P_S_Admin.document_manage_landing');
    }

    public function get_admin_alumni_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : (''); 
            $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');


            if($start_date || $end_date ){
                
                if(session()->get('user_type')=="F_F_Admin"){
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
                if(session()->get('user_type')=="F_F_Admin"){  
                    $filter_data = [  
                        'doc_status_col'=> 'doc_status',
                        'doc_status'=> $type, 
                        'updated_by'=> $hr_id, 
                        'check_col'=> "ff_doc_updated_by", 
                    ];
                }else if(session()->get('user_type')=="Payroll_QC"){
                    $filter_data = [  
                        'doc_status_col'=> 'Payroll_QC',
                        'doc_status'=> $type, 
                        'updated_by'=> $hr_id, 
                        'check_col'=> "ff_doc_updated_by", 
                    ];
                }
               
                $getquerydetails = $this->doc_task->get_ambassador_default_2($filter_data);
            }


            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use($type){
                        if($type=="Fresh"){
                            if(session()->get('user_type')=="F_F_Admin"){
                                $document_string="Pay Slips,F&F Statement,Form 16,Relieving Letter,Service Letter,Bonus,Performance Incentive,Sales Travel claim,Parental medical reimbursement,PF,Gratuity";
                            }
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Upload document" onclick="upload_document('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$document_string."'".','."'".$type."'".');"><i class="fas fa-upload"></i>&nbsp;Document</a>';
                        }
                        if($type=="Pending"){
                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );
                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){
                                $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                            $count_i++;
                            }
                            $unique_doc_array=array_unique($pre_doc_uploaded);

                            if(session()->get('user_type')=="F_F_Admin" || session()->get('user_type')=="Payroll_QC"){
                                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity"); 
                            }

                            $check_diff_array=array_diff($marks,$unique_doc_array);
                            $check_diff=array();
                            foreach ($check_diff_array as $a) {
                                $check_diff[]= $a;
                            } 
                            $document_string=implode(",",$check_diff);
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Upload document" onclick="upload_document('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$document_string."'".','."'".$type."'".');"><i class="fas fa-upload"></i>&nbsp;Document</a>';
                        }
                        if($type=="Completed"){
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="View document" onclick="doc_detail('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$row->remark."'".');"><i class="fas fa-eye"></i>&nbsp;Document</a>';
                        }
                        return $action;
                    })
                    ->addColumn('remark', function($row) use($type){

                        $remark = '<b>Remark:</b>';
                        
                        return $remark;
                    })
                    ->addColumn('document_div', function($row) use($type){
                        $document_div=''; 
                        
                        if($type=="Fresh"){

                            if(session()->get('user_type')=="F_F_Admin"){
                                $document_div.='<div class="badge badge-primary doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Gratuity</div><br>';
                            }
                           

                        }
                        if($type=="Pending"){

                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );

                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){

                                if(session()->get('user_type')=="F_F_Admin" || session()->get('user_type')=="Payroll_QC"){
                                    $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity");
                                    if(in_array($get_doc_rows[$count_i]['document'],$marks)==true)
                                    {
                                        $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                                    }
                                }
                               
                            $count_i++;
                            }
                            $unique_doc_array_1=array_unique($pre_doc_uploaded);

                            $unique_doc_array=array_values($unique_doc_array_1);


                            

                            if(session()->get('user_type')=="F_F_Admin" || session()->get('user_type')=="Payroll_QC"){
                                $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity"); 
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

                            if(session()->get('user_type')=="F_F_Admin" || session()->get('user_type')=="Payroll_QC"){
                                
                                $document_div.='<div class="badge badge-success doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-success doc_name">Gratuity</div><br>';
                                
                            }
                           
                            

                        }

                        return $document_div;
                    })
                    ->addColumn('status', function($row){

                        if(session()->get('user_type')=="F_F_Admin" || session()->get('user_type')=="Payroll_QC"){
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
                    ->addColumn('created_at', function($row){
                        $created_at=date('d-m-Y', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->addColumn('type_of_leaving', function($row){
                        if(!$row->type_of_leaving== ""|| !$row->type_of_leaving== null){
                            $type_of_leaving = '';
                            if($row->type_of_leaving == "Abscond" || $row->type_of_leaving == "Terminated"){
                                $type_of_leaving.='<div class="badge badge-danger doc_name">'.$row->type_of_leaving.'</div><br>';
                            }
                            elseif($row->type_of_leaving == "Transferred"){
                                $type_of_leaving.='<div class="badge badge-primary doc_name">'.$row->type_of_leaving.'</div><br>';
                            }else{
                                $type_of_leaving.='<div class="badge badge-success doc_name">'.$row->type_of_leaving.'</div><br>';
                            }
                            return $type_of_leaving;
                        }
                        else{
                            return "-----";
                        }
                    })
                    ->rawColumns(['action','remark','type_of_leaving','document_div','status','created_at'])
                    ->make(true);
        }
        return view('S_Admin.document_manage_landing ');
    }

    public function get_p_s_admin_alumni_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : (''); 
            $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');

            if($start_date || $end_date ){
                
                if(session()->get('user_type')=="F_F_Admin"){
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
                $filter_data = [  
                    'doc_status_col_1'=> 'doc_status',
                    'doc_status'=> $type, 
                ];
                $getquerydetails = $this->doc_task->get_ambassador_default_ps($filter_data);
            }


            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use($type){
                        if($type=="Fresh"){
                            $document_string="Pay Slips,F&F Statement,Form 16,Relieving Letter,Service Letter,Bonus,Performance Incentive,Sales Travel claim,Parental medical reimbursement,PF,Gratuity";
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Upload document" onclick="upload_document('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$document_string."'".','."'".$type."'".');"><i class="fas fa-upload"></i>&nbsp;Document</a>';
                        }
                        if($type=="Pending"){
                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );
                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){
                                $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                            $count_i++;
                            }
                            $unique_doc_array=array_unique($pre_doc_uploaded);

                            $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity"); 
                           
                            $check_diff_array=array_diff($marks,$unique_doc_array);
                            $check_diff=array();
                            foreach ($check_diff_array as $a) {
                                $check_diff[]= $a;
                            } 
                            $document_string=implode(",",$check_diff);
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="Upload document" onclick="upload_document('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$document_string."'".','."'".$type."'".');"><i class="fas fa-upload"></i>&nbsp;Document</a>';
                        }
                        if($type=="Completed"){
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" title="View document" onclick="doc_detail('."'".$row->emp_id."'".','."'".$row->emp_name."'".','."'".$row->remark."'".');"><i class="fas fa-eye"></i>&nbsp;Document</a>';
                        }
                        return $action;
                    })
                    ->addColumn('remark', function($row) use($type){

                        $remark = '<b>Remark:</b>'.$row->remark.'';
                        
                        return $remark;
                    })
                    ->addColumn('updated_by', function($row) {

                        $updated_by = '<b>F&F HR:</b> '.$row->ff_doc_updated_by.'<br>';
                        
                        return $updated_by;
                    })
                    ->addColumn('document_div', function($row) use($type){
                        $document_div=''; 
                        
                        if($type=="Fresh"){

                            if(session()->get('user_type')=="F_F_Admin"){
                                $document_div.='<div class="badge badge-primary doc_name">Pay Slips</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">F&F Statement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Form 16</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Relieving Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Service Letter</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Bonus</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Performance Incentive</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Sales Travel claim</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Parental medical reimbursement</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">PF</div><br>';
                                $document_div.='<div class="badge badge-primary doc_name">Gratuity</div><br>';
                            }
                           

                        }
                        if($type=="Pending"){

                            $get_doc_rows = $this->doc_task->get_doc_entry( "emp_id",$row->emp_id );

                            $pre_doc_uploaded=array();
                            $count_i=0;
                            while($count_i<count($get_doc_rows)){

                                    $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity");
                                    if(in_array($get_doc_rows[$count_i]['document'],$marks)==true)
                                    {
                                        $pre_doc_uploaded[]=$get_doc_rows[$count_i]['document'];
                                    }
                                
                            $count_i++;
                            }
                            $unique_doc_array_1=array_unique($pre_doc_uploaded);

                            $unique_doc_array=array_values($unique_doc_array_1);

                            $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Parental medical reimbursement","Sales Travel claim","PF","Gratuity"); 

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

                            $document_div.='<div class="badge badge-success doc_name">Pay Slips</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">F&F Statement</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Form 16</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Relieving Letter</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Service Letter</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Bonus</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Performance Incentive</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Sales Travel claim</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Parental medical reimbursement</div><br>';
                                
                            $document_div.='<div class="badge badge-success doc_name">PF</div><br>';
                            $document_div.='<div class="badge badge-success doc_name">Gratuity</div><br>';

                        }

                        return $document_div;
                    })
                    
                    ->addColumn('type_of_leaving', function($row){
                        if(!$row->type_of_leaving== ""|| !$row->type_of_leaving== null){
                            $type_of_leaving = '';
                            if($row->type_of_leaving == "Abscond" || $row->type_of_leaving == "Terminated"){
                                $type_of_leaving.='<div class="badge badge-danger doc_name">'.$row->type_of_leaving.'</div><br>';
                            }
                            elseif($row->type_of_leaving == "Transferred"){
                                $type_of_leaving.='<div class="badge badge-primary doc_name">'.$row->type_of_leaving.'</div><br>';
                            }else{
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
                    ->rawColumns(['updated_by','action','remark','type_of_leaving','document_div','created_at'])
                    ->make(true);
        }
        return view('P_S_Admin.document_manage_landing ');
    }

}
