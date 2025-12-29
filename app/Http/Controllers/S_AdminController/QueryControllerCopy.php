<?php

namespace App\Http\Controllers\S_AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\IEmpRepository;
use App\Repositories\IQueryRepository;
use App\Repositories\IAdminRepository;
use DataTables; 
use Image;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 

class QueryController extends Controller 
{
    //
    public function __construct( IEmpRepository $emp_task,IQueryRepository  $query_task,IAdminRepository $admin_task ) {
        $this->middleware( 'adminLog' );
        $this->query_task = $query_task;
        $this->emp_task = $emp_task;
        $this->admin_task = $admin_task;

    }
    public function query_manage_landing()
    {
        return view('S_Admin.query_manage_landing');
    }
    public function p_s_query_manage_landing()
    {
        return view('P_S_Admin.query_manage_landing');
    }

    public function s_daily_report()
    {
        return view('S_Admin.daily_report');
    }

    public function p_s_daily_report()
    {
        return view('P_S_Admin.daily_report');
    }

    

    public function get_admin_query_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : ('');
            $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');

            if($start_date || $end_date ){
                $filter_data = [ 
                    'start_date' => $start_date, 
                    'end_date'=> $end_date,
                    'status'=> $type, 
                    'updated_by'=> $hr_id, 
                ]; 
                $getquerydetails = $this->query_task->get_admin_query($filter_data);
            }
            else{
                // login_admin type
                if(session()->get('user_type')=="F_F_Admin"){ 
                    $approved_lvl=1;
                }
                else if(session()->get('user_type')=="Statutory_Admin"){
                    $approved_lvl=2;
                }

                $filter_data = [ 
                    'approved_lvl'=> $approved_lvl,
                    'status'=> $type, 
                    'updated_by'=> $hr_id,  
                ];
                $getquerydetails = $this->query_task->get_admin_query_default_2($filter_data);
            }


            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use($type){


                        $getempdetail = $this->emp_task->get_employee_detail( $row->emp_id ); 

                        foreach($getempdetail as $getempdetail)
                        {
                            $emp_name=$getempdetail->emp_name;
                        }

                        if($type=="Pending"){
                            $approve="Approved";
                            $decline="Declined";
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="update_query_click('."'".$row->ticket_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>
                            <a href="#" class="btn btn-icon btn-danger ac_btn" onclick="update_query_click('."'".$row->ticket_id."'".','."'".$decline."'".');"><i class="fas fa-times"></i></a>';
                        }
                        if($type=="Approved"){
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="upload_document('."'".$row->ticket_id."'".','."'".$emp_name."'".','."'".$row->emp_id."'".','."'".$row->document."'".');"><i class="fas fa-upload"></i>&nbsp;Document</a>';
                        }
                        if($type=="Completed"){
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="doc_detail('."'".$row->ticket_id."'".','."'".$row->emp_id."'".','."'".$emp_name."'".','."'".$row->admin_remark."'".');"><i class="fas fa-eye"></i>&nbsp;Document</a>';
                        }
                        if($type=="Declined"){
                            $approve="Approved";
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="update_query_click_dec_tab('."'".$row->ticket_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>';
                        }
                        
                        return $action;
                    })
                    ->addColumn('remark', function($row) use($type){

                        if($type=="Declined"){
                            $remark = '<b>Remark</b>:'.$row->remark.'<br><b>Dec Remark:</b>'.$row->dec_remark.'';
                        }
                        else{
                            $remark = '<b>Remark:</b>'.$row->remark.'';
                        }
                        
                        return $remark;
                    })
                    ->addColumn('document_div', function($row) use($type){
                        $doc_arr=explode(",",$row->document);
                        $d_i=0;
                        $document_div='';

                            // while($d_i<count($doc_arr)){
                            //     $document_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                            //     $d_i++;
                            // }
                            // return $document_div;

                            $tool_tip='<a style="cursor:pointer;" title="'.$row->document.'"><button class="btn btn btn-sm btn-info"><i class="fa fa-file" aria-hidden="true"></i></button></a>';
                            return $tool_tip;
                        
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
                        if(!$row->type_of_leaving== ""|| !$row->type_of_leaving== null){
                            $type_of_leaving =$row->type_of_leaving;
                            return $type_of_leaving;
                        }
                        else{
                            return "-----";
                        }
                    })
                    ->addColumn('created_at', function($row){
                        $created_at=date('Y-m-d', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->rawColumns(['action','remark','document_div','status','created_at'])
                    ->make(true);
        }
        return view('S_Admin.query_manage_landing ');
    }


    public function get_admin_d_report_datatable(Request $request){
        if ($request->ajax()) {
            $filter_date = (!empty($_POST["filter_date"])) ? ($_POST["filter_date"]) : ('');
            $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');

            if($filter_date || $hr_id ){

                if(session()->get('user_type')=="F_F_Admin"){ 
                    $approved_lvl=1;
                }
                else if(session()->get('user_type')=="Statutory_Admin"){
                    $approved_lvl=2;
                }

                $filter_data = [ 
                    'filter_date' => $filter_date, 
                    'updated_by'=> $hr_id, 
                    'approved_lvl'=> $approved_lvl,

                ];
               
                if($hr_id==""){
                    $getquerydetails = $this->query_task->get_admin_daily_report_query_filter_All($filter_data);
                }
                else{
                    $getquerydetails = $this->query_task->get_admin_daily_report_query_filter($filter_data);
                }
                 
            }

            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    // ->addColumn('Document', function($row) {

                    //     $credentials=[
                    //         'ticket_id'=>$row->ticket_id,
                    //     ];

                    //     $update_query_doc = $this->query_task->get_updated_doc_detail( $credentials );

                    //     foreach($update_query_doc as $doc_row){
                    //         $doc[]=doc_row->document;
                    //     }

                        
                    //     return $action;
                    // })
                    
                    // ->rawColumns(['action'])
                    ->make(true);
        }
        return view('S_Admin.query_manage_landing ');
    }

    
    public function get_p_s_admin_d_report_datatable(Request $request){
        if ($request->ajax()) {
            $filter_date = (!empty($_POST["filter_date"])) ? ($_POST["filter_date"]) : ('');
            // $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');

            if($filter_date ){

                $filter_data = [ 
                    'filter_date' => $filter_date,  
                ];
               
                $getquerydetails = $this->query_task->get_p_s_admin_daily_report_query_filter($filter_data);
                 
            }

            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    // ->addColumn('Document', function($row) {

                    //     $credentials=[
                    //         'ticket_id'=>$row->ticket_id,
                    //     ];

                    //     $update_query_doc = $this->query_task->get_updated_doc_detail( $credentials );

                    //     foreach($update_query_doc as $doc_row){
                    //         $doc[]=doc_row->document;
                    //     }

                        
                    //     return $action;
                    // })
                    
                    // ->rawColumns(['action'])
                    ->make(true);
        }
        return view('S_Admin.query_manage_landing ');
    }

    public function get_p_s_admin_query_datatable(Request $request){
        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : ('');
            $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');

            if($start_date || $end_date ){
                $filter_data = [ 
                    'start_date' => $start_date, 
                    'end_date'=> $end_date,
                    'status'=> $type, 
                    'updated_by'=> $hr_id, 
                ]; 
                $getquerydetails = $this->query_task->get_admin_query($filter_data);
            }
            else{
                $filter_data = [ 
                    'status'=> $type, 
                ];
                $getquerydetails = $this->query_task->get_admin_query_default_ps($filter_data);
            }


            return Datatables::of($getquerydetails)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use($type){


                        $getempdetail = $this->emp_task->get_employee_detail( $row->emp_id ); 

                        foreach($getempdetail as $getempdetail)
                        {
                            $emp_name=$getempdetail->emp_name;
                        }

                        if($type=="Pending"){
                            $approve="Approved";
                            $decline="Declined";
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="update_query_click('."'".$row->ticket_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>
                            <a href="#" class="btn btn-icon btn-danger ac_btn" onclick="update_query_click('."'".$row->ticket_id."'".','."'".$decline."'".');"><i class="fas fa-times"></i></a>';
                        }
                        if($type=="Approved"){
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="upload_document('."'".$row->ticket_id."'".','."'".$emp_name."'".','."'".$row->emp_id."'".','."'".$row->document."'".');"><i class="fas fa-upload"></i>&nbsp;Document</a>';
                        }
                        if($type=="Completed"){
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="doc_detail('."'".$row->ticket_id."'".','."'".$row->emp_id."'".','."'".$emp_name."'".','."'".$row->admin_remark."'".');"><i class="fas fa-eye"></i>&nbsp;Document</a>';
                        }
                        if($type=="Declined"){
                            $approve="Approved";
                            $action = '<a href="#" class="btn btn-icon btn-success ac_btn" onclick="update_query_click_dec_tab('."'".$row->ticket_id."'".','."'".$approve."'".');"><i class="fas fa-check"></i></a>';
                        }
                        
                        return $action;
                    })
                    ->addColumn('remark', function($row) use($type){

                        if($type=="Declined"){
                            $remark = '<b>Remark</b>:'.$row->remark.'<br><b>Dec Remark:</b>'.$row->dec_remark.'';
                        }
                        else{
                            $remark = '<b>Remark:</b>'.$row->remark.'';
                        }
                        
                        return $remark;
                    })
                    ->addColumn('document_div', function($row){
                        $doc_arr=explode(",",$row->document);
                        $d_i=0;
                        $document_div='';
                        while($d_i<count($doc_arr)){
                            $document_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                            $d_i++;
                        }
                        return $document_div;
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
                    ->addColumn('emp.type_of_leaving', function($row){
                        if(!$row->type_of_leaving== ""|| !$row->type_of_leaving== null){
                            return $type_of_leaving =$row->type_of_leaving;
                        }
                        else{
                            return "-----";
                        }
                    })
                    ->addColumn('created_at', function($row){
                        $created_at=date('Y-m-d', strtotime($row->created_at));
                        return $created_at;
                    })
                    ->rawColumns(['action','remark','document_div','status','type_of_leaving','created_at'])
                    ->make(true);
        }
        return view('S_Admin.query_manage_landing ');
    }

    

}
