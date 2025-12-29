<?php

namespace App\Http\Controllers;
use App\Repositories\IF_F_tracker_Repository;
use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Bank_detail_account;
use DB;

class ReportController extends Controller
{
    public function __construct( IF_F_tracker_Repository $f_f_tracker_task  ) {
        
        $this->f_f_tracker_task = $f_f_tracker_task;
        
    }

    public function report_page(){
        return view('viewReports1');
    }
    public function report_page1(){
        return view('viewReports');
    }

    public function account(){
        return view ('account');
    }
   
    public function accountdetails(Request $request){
       

        $bank_account_table = Bank_detail_account::select('*')
        ->where('status' , $request->status)
        ->get();
       
    
        return Datatables::of($bank_account_table)
            ->addIndexColumn()
            ->addColumn('cheque', function ($row) {
                $cheque_a = '<a href="../Bank_details/'.$row->emp_id.'/cheque/'.$row->cheque.'"  >'.$row->cheque.'</a>';
                return $cheque_a;
            })
            ->addColumn('passbook', function ($row) {
                $passbook_a = '<a href="../Bank_details/'.$row->emp_id.'/passbook/'.$row->passbook.'"  >'.$row->passbook.'</a>';
                return $passbook_a;
            })
            ->addColumn('action', function($row){
                $actions = '';
                if($row->status == "Pending"){
                    $actions = '<a onclick="approve_bank('.$row->id.')" class="btn btn-success" title="Approve" id="approve" name="approve"><i class="fas fa-check"></i></a>';
                    $actions .= '<a onclick="reject_bank('.$row->id.')" style="margin-left:20px;" class="btn btn-danger" title="Reject" id="reject" name="reject"><i class="fas fa-times"></i></a>';
                  
                }elseif($row->status == "Approved"){
                    $actions = '<a  class="btn btn-success" title="Approve" id="approve" name="approve">Approved</a>';
                }elseif($row->status == "Rejected"){
                    $actions = '<a  class="btn btn-danger" title="Approve" id="approve" name="approve">Rejected</a>';

                }
                  return $actions;
            })

            ->rawColumns(['cheque', 'passbook','emp_id','action'])
            ->make(true);
            return view('account');
    }
    public function update_status(Request $request)
    {
        // echo "<pre>print_r($request->id)";
        $model = Bank_detail_account::find($request->id);
        $model->status = 'Approved';
        $model->save();
        if($model){
            $response = "success";
        }
        else{
            $response = "failed";
        }

        return response()->json( ['response' => $response]);
    }
    public function updated_status(Request $request)
    {
        // echo "<pre>print_r($request->id)";
        $model = Bank_detail_account::find($request->id);
        $model->status = 'Rejected';
        $model->save();
        if($model){
            $response = "success";
        }
        else{
            $response = "failed";
        }

        return response()->json( ['response' => $response]);
    }

    public function view_report(Request $request)
    {
       if($request->type == "daily_report" || $request->type =="weekly_report"|| $request->type =="function_report"){
            if($request->table == 'completed_within_tat_tbl'){
                $history = $this->f_f_tracker_task->completed_within_tat($request->type,$request->function);
            }elseif ($request->table == 'completed_beyond_tat_tbl') {
                $history = $this->f_f_tracker_task->completed_beyond_tat($request->type,$request->function);
            }elseif ($request->table == 'pending_within_tat_tbl') {
    
                $history = $this->f_f_tracker_task->pending_within_tat($request->type,$request->function);
            }elseif ($request->table == 'pending_beyond_tat_tbl') {
                $history = $this->f_f_tracker_task->pending_beyond_tat($request->type,$request->function);
            }
            // $history = $this->f_f_tracker_task->get_history($request->type,$request->table);
        // }elseif($request->type =="function_report"){
        //     $history = $this->f_f_tracker_task->get_history($request->type,$request->function);
        //    }elseif($request->type =="ageing_report"){
        //     $history = $this->f_f_tracker_task->ageing_report($request->type,$request->function);
        }else {
            if($request->from_date){
                // dd($request->from_date);
                if($request->table == 'completed_within_tat_tbl'){
                    $history = $this->f_f_tracker_task->completed_within_tat_mr($request->from_date,$request->to_date);
                }elseif ($request->table == 'completed_beyond_tat_tbl') {
                    $history = $this->f_f_tracker_task->completed_beyond_tat_mr($request->from_date,$request->to_date);
                }elseif ($request->table == 'pending_within_tat_tbl') {
        
                    $history = $this->f_f_tracker_task->pending_within_tat_mr($request->from_date,$request->to_date);
                }elseif ($request->table == 'pending_beyond_tat_tbl') {
                    $history = $this->f_f_tracker_task->pending_beyond_tat_mr($request->from_date,$request->to_date);
                }
            }else{
                if($request->table == 'completed_within_tat_tbl'){
                    $history = $this->f_f_tracker_task->completed_within_tat($request->type,$request->function);
                }elseif ($request->table == 'completed_beyond_tat_tbl') {
                    $history = $this->f_f_tracker_task->completed_beyond_tat($request->type,$request->function);
                }elseif ($request->table == 'pending_within_tat_tbl') {
        
                    $history = $this->f_f_tracker_task->pending_within_tat($request->type,$request->function);
                }elseif ($request->table == 'pending_beyond_tat_tbl') {
                    $history = $this->f_f_tracker_task->pending_beyond_tat($request->type,$request->function);
                }
            }
            
            
        }
        //    $history = $this->f_f_tracker_task->get_history($request->type,$request->function);
        // dd($history);
        // dd($history);
            $type = $request->type;
            return Datatables::of($history)
                ->addIndexColumn()
                ->addColumn('completed_date', function ($row) {
                    $emp_name = DB::table('f__f_tracker_date_infos')->where('emp_id', $row->emp_id);
                    $emp_name = $emp_name->orderBy('id','desc');
                    $emp_name = $emp_name->first();
                    // dd($emp_name);
                    if(isset($emp_name->created_at)){
                        return date('d-m-Y', strtotime($emp_name->created_at));
                    }else{
                        return '-';
                    }
                })
                ->addColumn('emp_name', function ($row) {
                    $emp_name = DB::table('emp_profile_tbls')->where('emp_id', $row->emp_id)->first();
 
                    return $emp_name->emp_name;
                })
                ->addColumn('completed_time', function ($row) {
                    $emp_name = DB::table('f__f_tracker_date_infos')->where('emp_id', $row->emp_id);
                    $emp_name = $emp_name->orderBy('id','desc');
                    $emp_name = $emp_name->first();
                    if(isset($emp_name->created_at)){
                        return date('H:i:s', strtotime($emp_name->created_at));
                    }else{
                        return '-';
                    }
                })
                ->addColumn('age_report', function ($row) {
                    $emp_name = DB::table('f__f_tracker_date_infos')->where('emp_id', $row->emp_id);
                    if(session('user_type') == 'F_F_HR' && $row->f_f_c_s_g == 1){
                        $emp_name = $emp_name->where('created_by',session('emp_id'));
                    }else if(session('user_type') == 'F_F_HR' && $row->f_f_c_s_g == 6){
                        $emp_name = $emp_name->where('created_by','PRFN001');
                        $emp_name = $emp_name->where('s_g_id','6');
                    }else if(session('user_type') == 'Payroll_QC'){
                        $emp_name = $emp_name->where('s_g_id','2');
                    }else if(session('user_type') == 'Payroll_Finance'){
                        $emp_name = $emp_name->where('s_g_id','3');
                    }
                    $emp_name = $emp_name->orderBy('id','desc');
                    $emp_name = $emp_name->first();
                    // print_r($emp_name);
                    $received_date = isset($emp_name->created_at) ? new \DateTime($emp_name->created_at) : new \DateTime($row->created_at);

                    // $completed_date = $emp_name->created_at;
                    // $received_date = $emp_name->created_at;
                    
                    $emp_name1 = DB::table('f__f_tracker_date_infos')->where('emp_id', $row->emp_id);
                    if(session('user_type') == 'F_F_HR' && $row->f_f_c_s_g == 1){
                        $emp_name1 = $emp_name1->where('created_by',session('emp_id'));
                    }else if(session('user_type') == 'F_F_HR' && $row->f_f_c_s_g == 6){
                        $emp_name1 = $emp_name1->where('created_by','PRFN001');
                        $emp_name1 = $emp_name1->where('s_g_id','5');
                    }else if(session('user_type') == 'Payroll_QC'){
                        $emp_name1 = $emp_name1->where('s_g_id','3');
                    }else if(session('user_type') == 'Payroll_Finance'){
                        $emp_name1 = $emp_name1->where('s_g_id','6');
                    }else if(session('user_type') == 'F_F_HR'){
                        $emp_name1 = $emp_name1->where('created_by',session('emp_id'));
                    }
                    $emp_name1 = $emp_name1->orderBy('id','desc');
                    $emp_name1 = $emp_name1->first();
                    // $completed_date = $emp_name1->created_at;
                    $receivedDate = $received_date;
                    $completedDate = isset($emp_name1->created_at) ? new \DateTime($emp_name1->created_at) : new \DateTime();
                        // Calculate the difference in years
                    $age = $receivedDate->diff($completedDate)->days;
                    // dd($age);
                    if($age == 0){
                        $age_ = $receivedDate->diff($completedDate)->h;
                        
                    }else{
                        $age_ = $age;
                    }
                    // print_r($received_date);
                    // die();
                    return $age_;
                })
                ->rawColumns(['completed_date', 'completed_time','age_report'])
                ->make(true);
        return view('Admin.view_report1');
    }
}
