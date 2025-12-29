<?php

namespace App\Repositories;

use App\Models\admin_tbl;
use App\Models\amb_document_tbl;
use App\Models\emp_profile_tbl;
use App\Models\f_f_check_point;
use App\Models\F_F_tracker_alumni_data;
use App\Models\ReopenHistory;
use App\Models\F_F_tracker_date_info;
use App\Models\F_F_tracker_files;
use App\Models\History_f_f;
use App\Models\hold_salary;
use App\Models\Notifications;
use App\Models\recovery_data;
use App\Models\revert_table;
use Carbon\Carbon;
use DB;

class F_F_tracker_Repository implements IF_F_tracker_Repository
{

    public function insert_form_f_f_data($credentials)
    {
        $querytbl = new F_F_tracker_alumni_data();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->supervisor_clearance = $credentials['supervisor_clearance'];
        $querytbl->c_admin_clearance = $credentials['c_admin_clearance'];
        $querytbl->finanace_clearance = $credentials['finanace_clearance'];
        $querytbl->it_clearance = $credentials['it_clearance'];
        $querytbl->grade_set = $credentials['grade_set'];
        $querytbl->grade = $credentials['grade'];
        $querytbl->department = $credentials['department'];
        $querytbl->work_location = $credentials['work_location'];
        $querytbl->supervisor_name = $credentials['supervisor_name'];
        $querytbl->reviewer_name = $credentials['reviewer_name'];
        $querytbl->headquarters = $credentials['headquarters'];
        $querytbl->hrbp_name = $credentials['hrbp_name'];
        $querytbl->last_working_date = $credentials['last_working_date'];
        $querytbl->seperation_date = $credentials['seperation_date'];
        $querytbl->date_of_joining = $credentials['date_of_joining'];
        $querytbl->date_of_resignation = $credentials['date_of_resignation'];
        $querytbl->created_by = $credentials['created_by'];
        $querytbl->save();
    }

    public function insert_date_of_completed($credentials)
    {
        $querytbl = new F_F_tracker_date_info();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->s_g_id = $credentials['s_g'];
        $querytbl->created_by = $credentials['created_by'];
        $querytbl->save();
    }

    public function update_form_f_f_data($credentials)
    {
        $update_row = new F_F_tracker_alumni_data();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update([
            'supervisor_clearance' => $credentials['supervisor_clearance'],
            'c_admin_clearance' => $credentials['c_admin_clearance'],
            'finanace_clearance' => $credentials['finanace_clearance'],
            'it_clearance' => $credentials['it_clearance'],
            'grade_set' => $credentials['grade_set'],
            'grade' => $credentials['grade'],
            'department' => $credentials['department'],
            'work_location' => $credentials['work_location'],
            'supervisor_name' => $credentials['supervisor_name'],
            'reviewer_name' => $credentials['reviewer_name'],
            'headquarters' => $credentials['headquarters'],
            'hrbp_name' => $credentials['hrbp_name'],
            'last_working_date' => $credentials['last_working_date'],
            'seperation_date' => $credentials['seperation_date'],
            'date_of_joining' => $credentials['date_of_joining'],
            'date_of_resignation' => $credentials['date_of_resignation'],
        ]);
    }

    public function update_form_f_f_data_set_2($credentials)
    {
        $update_row = new F_F_tracker_alumni_data();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        // dd($credentials);
        $update_row->update([

            'basic' => $credentials['basic'],
            'da' => $credentials['da'],
            'other_allowance' => $credentials['other_allowance'],
            'hra' => $credentials['hra'],
            'addl_hra' => $credentials['addl_hra'],
            'conveyance' => $credentials['conveyance'],
            'lta' => $credentials['lta'],
            'medical' => $credentials['medical'],
            'spl_allowance' => $credentials['spl_allowance'],
            'nps' => $credentials['nps'],
            'super_annuation' => $credentials['super_annuation'],
            'fixed_stipend' => $credentials['fixed_stipend'],
            'sales_incentive' => $credentials['sales_incentive'],
            'fixed_vehicle_allowance' => $credentials['fva'],
            'gross' => $credentials['gross'],
            'leave_balance_cl' => $credentials['leave_balance_cl'],
            'leave_balance_pl' => $credentials['leave_balance_pl'],
            'leave_balance_sl' => $credentials['leave_balance_sl'],
            'is_probation_completed' => $credentials['is_probation_completed'],
            're_open_ct' => $credentials['re_open_ct'],
            're_opened_by' => $credentials['re_opened_by'],

        ]);
        // if($update_row){
        //     dd(1);
        // }else{
        //     dd(2);
        // }
    }

    public function update_form_f_f_data_set_3($credentials)
    {
        $update_row = new F_F_tracker_alumni_data();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update([
            'sap_doc_no' => $credentials['sap_doc_no'],
            'posting_date' => $credentials['posting_date'],
            'pay_rec' => $credentials['pay_rec'],
            'ff_amount' => $credentials['ff_amount'],
        ]);
    }

    public function check_f_f_inp($credentials)
    {
        $get_result = new F_F_tracker_alumni_data();
        $get_result = $get_result->where('emp_id', '=', $credentials['emp_id']);
        $get_result = $get_result->get();
        return $get_result;
    }

    public function getEmployeeList($credentials)
    {
        $result = new emp_profile_tbl();
        if ($credentials['type'] == "fresh") {
            $result = $result->whereIn('f_f_c_s_g', $credentials['f_f_c_s_g']);
        } else if ($credentials['type'] == "greater") {
            $result = $result->where('f_f_c_s_g', '>=', $credentials['f_f_c_s_g']);
        } else if ($credentials['type'] == "equal") {
            $result = $result->where('f_f_c_s_g', $credentials['f_f_c_s_g']);
        }
        return $result->get();
    }

    public function save_f_f_file_row($credentials)
    {
        $querytbl = new F_F_tracker_files();
        $querytbl->flow = $credentials['flow'];
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->s_g_id = $credentials['s_g_id'];
        $querytbl->doc_type = $credentials['doc_type'];
        $querytbl->filename = $credentials['filename'];
        $querytbl->remark = $credentials['remark'];
        $querytbl->created_by = $credentials['created_by'];
        $querytbl->save();

    }

    public function get_tracker_alumni_data($emp_id)
    {
        $get_result = F_F_tracker_alumni_data::where('emp_id', $emp_id)->get();
        return $get_result;
    }

    public function get_status($emp_id, $check_col)
    {

        $status = emp_profile_tbl::where('emp_id', '=', $emp_id);
        if ($check_col != "") {
            $status->select($check_col);
        }
        return $status->get();
    }

    public function get_count()
    {
        $record = DB::table('questions_table')->count();
        return $record;
    }

    public function update_revert($credentials)
    {
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update([
            'revert_status' => $credentials['revert_status'],
            'f_f_c_s_g' => $credentials['to_sg'],
        ]);
    }

    public function save_revert($credentials)
    {
        $tbl = new revert_table();
        $tbl->emp_id = $credentials['emp_id'];
        $tbl->flow = $credentials['flow'];
        $tbl->from_sg = $credentials['from_sg'];
        $tbl->to_sg = $credentials['to_sg'];
        $tbl->remark = $credentials['remark'];
        $tbl->re_open_status = $credentials['re_open_status'];
        $tbl->created_by = $credentials['created_by'];
        $tbl->reverted_to = $credentials['reverted_to'];
        $tbl->save();
    }

    public function get_f__f_tracker_files($emp_id, $sg_id)
    {
        $record = DB::table('f__f_tracker_files')
            ->where('emp_id', $emp_id)
            ->whereIn('s_g_id', $sg_id)
            ->get();
        return $record;
    }

    public function get_f__f_reverted_tracker_files($emp_id, $file_id)
    {
        $record = DB::table('f__f_tracker_files')
            ->where('emp_id', $emp_id)
            ->whereIn('id', $file_id)
            ->get();
        return $record;
    }

    public function get_revert_docs($flow_id, $emp_id)
    {
        $record = DB::table('f__f_tracker_files')
            ->where('emp_id', $emp_id)
            ->where('flow', $flow_id)
            ->orderBy('s_g_id', 'DESC')
            ->get();
        return $record;
    }

    public function get_revert_remarks($emp_id, $sg_id, $flow_id)
    {
        $record = DB::table('revert_tables')
            ->where('emp_id', $emp_id)
            ->where('from_sg', $sg_id)
            ->where('flow', $flow_id)
            ->get();
        return $record;
    }

    public function get_reverts($emp_id)
    {
        $record = DB::table('revert_tables')
            ->where("emp_id", $emp_id)
            ->limit(1)->orderBy('id', 'DESC')
            ->get();
        return $record;
    }

    public function get_data_with_where($val, $column, $table, $con)
    {
        $record = DB::table($table)
            ->where($column, $con, $val)
            ->get();
        return $record;
    }

    public function get_data_with_where2($table, $column, $value, $column2, $value2)
    {
        $record = DB::table($table)
            ->where($column, $value)
            ->where($column2, $value2)
            ->get();
        return $record;
    }

    public function get_data_with_where_onlyfiles($table, $column, $value, $column2, $value2){
        $record = DB::table($table)
            ->where($column, $value)
            ->where($column2, $value2)
            ->orderBy('id','desc')
            ->select('filename')
            ->first();
        return $record;
    }
    public function get_check_points($emp_id, $q_id)
    {
        $record = DB::table('f_f_check_points')
            ->where('emp_id', $emp_id)
            ->where('question_id', $q_id)
            ->get();
        return $record;
    }

    public function update_qc_status($id, $status)
    {
        $update_row = new f_f_check_point();
        $update_row = $update_row->where('id', $id);
        $update_row->update([
            'qc_status' => $status,
        ]);
    }

    public function get_all_data($table)
    {
        $record = DB::table($table)->get();
        return $record;
    }

    public function get_data_with_where_in($table, $column, $value, $column2, $value2, $column3, $array)
    {
        $record = DB::table($table)
            ->where($column, $value)
            ->where($column2, $value2)
            ->whereIn($column3, $array)
            ->get();
        return $record;
    }

    public function save_recovery($credentials)
    {
        $tbl = new recovery_data();
        $tbl->emp_id = $credentials['emp_id'];
        $tbl->r_id = $credentials['r_id'];
        $tbl->values = $credentials['values'];
        $tbl->remark = $credentials['remark'];
        $tbl->save();
    }

    public function delete_recovery($id)
    {
        recovery_data::where('id', $id)->delete();
    }

    public function update_recovery($credentials)
    {
        $update_row = new recovery_data();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row = $update_row->where('r_id', '=', $credentials['r_id']);
        $update_row->update([
            'values' => $credentials['values'],
            'remark' => $credentials['remark'],
        ]);
    }

    public function save_hold_salry($cred)
    {
        $hold_salry = new hold_salary();
        $hold_salry->emp_id = $cred['emp_id'];
        $hold_salry->month_year = $cred['month_year'];
        $hold_salry->amount = $cred['amount'];
        $hold_salry->save();
    }

    public function save_notification($credentials)
    {
        $querytbl = new Notifications();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->from_sg = $credentials['from_sg'];
        $querytbl->to_sg = $credentials['to_sg'];
        $querytbl->alert_to = $credentials['alert_to'];
        $querytbl->visible_status = $credentials['v_status'];
        $querytbl->status = $credentials['sts'];
        $querytbl->save();

    }

    public function get_notify_count($cred)
    {
        return Notifications::where('visible_status', $cred['v_status'])->where('status', $cred['sts'])->where('alert_to', $cred['alert_to'])->count();
    }
    public function get_notify_users($cred)
    {
        return Notifications::where('alert_to', $cred['alert_to'])->where('status', $cred['sts'])->orderBy('created_at', 'DESC')->get();
    }

    public function notify_deactive($cred)
    {
        $update_row = new Notifications();
        $update_row = $update_row->where('emp_id', $cred['emp_id']);
        $update_row->update([
            'status' => $cred['d_sts'],
        ]);
    }

    public function update_notify($cred)
    {
        $update_row = new Notifications();
        $update_row = $update_row->where('alert_to', $cred['alert_to']);
        $update_row->update([
            'visible_status' => $cred['v_status'],
        ]);
    }

    public function get_qc_status_count($cred)
    {
        return f_f_check_point::where('emp_id', $cred['emp_id'])->where('qc_status', '!=', "")->count();
    }

    public function get_data_with_where_3($cred)
    {
        $record = DB::table($cred['table'])
            ->where($cred['col1'], $cred['val1'])
            ->where($cred['col2'], $cred['val2'])
            ->where($cred['col3'], $cred['val3'])
            ->get();
        return $record;
    }

    public function get_provided_doc_emp_id($input)
    {
        $get_emp_data = new amb_document_tbl;
        $get_emp_data = $get_emp_data->whereIn('document', $input['document']);
        if (!empty($input['start_date'])) {
            $get_emp_data = $get_emp_data->whereDate('created_at', '>=', $input['start_date']);
        }
        if (!empty($input['end_date'])) {
            $get_emp_data = $get_emp_data->whereDate('created_at', '<=', $input['end_date']);
        }
        $get_emp_data = $get_emp_data->pluck('emp_id')->toArray();
        return $get_emp_data;
    }

    public function get_query_data($input)
    {
        $record = DB::table('query_document_tbls as qt')
            ->join('query_tbls as qry', 'qt.ticket_id', '=', 'qry.ticket_id')
            ->select(
                'qt.*', 'qry.updated_by',
                'qt.updated_by as up_by',
                'qry.emp_id',
                'qry.created_at as qry_created_at',
            );
        if ($input['type'] != "unresolved") {
            $record = $record->whereIn('qt.document', $input['document']);
        }
        $record = $record->whereIn('qt.status', $input['status']);
        if (isset($input['start_date']) && $input['end_date'] != '') {
            $record = $record->whereDate('qt.' . $input['filter_date_col'], '>=', $input['start_date']);
            $record = $record->whereDate('qt.' . $input['filter_date_col'], '<=', $input['end_date']);
        }
        $record = $record->orderBy('qt.created_at', 'desc')->groupBy('qt.ticket_id')->get();
        return $record;
    }

    public function update_form_f_f_data_set_4($credentials)
    {
        $update_row = new F_F_tracker_alumni_data();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update([
            'payout_amount' => $credentials['payout_amount'],
        ]);
    }

    public function get_email($credentials)
    {
        $querytbl = new admin_tbl;
        $querytbl = $querytbl->where('user_type', $credentials);
        $querytbl = $querytbl->where('status', 'Active');
        $querytbl = $querytbl->first();
        return $querytbl;
    }

    public function save_history($credentials)
    {
        $querytbl = new History_f_f();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->from_sg = $credentials['from_sg'];
        $querytbl->to_sg = $credentials['to_sg'];
        $querytbl->date = Carbon::now()->format('Y-m-d');
        $querytbl->time = Carbon::now()->format('H:i:s');
        $querytbl->created_by = $credentials['created_by'];
        $querytbl->sender_to = $credentials['sender_to'];
        $querytbl->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $querytbl->save();

    }

    // public function get_history()
    // {
    //     $result = new History_f_f;
    //     $result = $result->where('created_by',session('emp_id'));
    //     $result = $result->orWhere('created_by',session('emp_id'));
    //     $result = $result->join('emp_profile_tbls','emp_profile_tbls.emp_id','history_f_f.emp_id');
    //     return $result->get();
    // }

    

    public function get_history($type,$function)
    {
        $result = new History_f_f;
        // $result = $result->where('created_by',session('emp_id'));
        
 
        // if($type == "daily_report"){
        //     $result = $result->Where('sender_to',session('emp_id'));
        //     $result = $result->groupBy('history_f_f.emp_id');
        //     $result = $result->where('date',date('Y-m-d'));
        // }elseif($type == "weekly_report"){
        //     $result = $result->Where('sender_to',session('emp_id'));
        //     $result = $result->groupBy('history_f_f.emp_id');
        //     // $startOfWeek = date('Y-m-d', strtotime('last monday', strtotime('this week')));
        //     // $endOfWeek = date('Y-m-d', strtotime('next sunday', strtotime('this week')));
        //     // dd($startOfWeek, $endOfWeek);
        //     // Filter by date within the current week
 
        //     $startDate = date('Y-m-d', strtotime('-7 days'));
        //     $endDate = date('Y-m-d', strtotime('-1 days'));
 
        //     $result = $result->whereBetween('date', [$startDate, $endDate]);
        // }else
        if($type == "function_report"){
            if($function == "hrss"){
                $result = $result->where('from_sg','1');
                $result = $result->orWhere('to_sg','6');
            }elseif($function == 'payroll'){
                $result = $result->where('to_sg','2');
            }elseif($function == 'qc'){
                $result = $result->where('to_sg','3');
            }elseif($function == 'finance'){
                $result = $result->where('to_sg','4');
            }
        }
        $result = $result->join('emp_profile_tbls','emp_profile_tbls.emp_id','history_f_f.emp_id','left');
        return $result->get();
    }

    public function completed_beyond_tat($type,$function){

        $result = new History_f_f;
            // $result = $result->Where('created_by',session('emp_id'));
            // $result = $result->orWhere('created_by',session('emp_id'));
            // $result = $result->groupBy('history_f_f.emp_id');
            $result =$result ->join('escalations','history_f_f.emp_id','escalations.emp_id');
            
            if(session('user_type') == 'F_F_HR' && $type != "function_report"){
                $result = $result->Where('created_by',session('emp_id'));

                // $result = $result->where('history_f_f.from_sg','1');
                // $result = $result->orWhere('history_f_f.from_sg','6');
                $result = $result->where('escalations.stage','1');
                $result = $result->orWhere('escalations.stage','6');

            }else if(session('user_type') == 'Payroll_HR' && $type != "function_report"){
                $result = $result->Where('created_by',session('emp_id'));

                // $result = $result->where('history_f_f.from_sg','2');
                $result = $result->where('escalations.stage','2');
                // $result = $result->orWhere('escalations.stage','6');

            }else if(session('user_type') == 'Payroll_QC' && $type != "function_report"){

                // $result = $result->where('history_f_f.from_sg','3');
                $result = $result->Where('created_by',session('emp_id'));

                $result = $result->where('escalations.stage','3');
                // $result = $result->orWhere('escalations.stage','6');

            }else if(session('user_type') == 'Payroll_Finance' && $type != "function_report"){
                // $result = $result->where('history_f_f.from_sg','4');
                $result = $result->Where('created_by',session('emp_id'));

                // $result = $result->orWhere('history_f_f.from_sg','5');
                $result = $result->where('escalations.stage','4');
                $result = $result->orWhere('escalations.stage','5');
                // $result = $result->orWhere('escalations.stage','6');

            }
            if($type == 'daily_report'){
                // $result = $result->where('date',date('Y-m-d'))->get();
                $result = $result->where('history_f_f.date', date('Y-m-d'));
                // dd(date('Y-m-d'));
            }else if($type == 'weekly_report'){
                $startDate = date('Y-m-d', strtotime('-7 days'));
                $endDate = date('Y-m-d', strtotime('-1 days'));
                $result = $result->whereBetween('date', [$startDate, $endDate]);
            }else if($type == "function_report"){
        // dd(1);

                // if($function == "hrss"){
                //     $result = $result->where('from_sg','1');
                //     $result = $result->orWhere('to_sg','6');
                // }elseif($function == 'payroll'){
                //     $result = $result->where('to_sg','2');
                // }elseif($function == 'qc'){
                //     $result = $result->where('to_sg','3');
                // }elseif($function == 'finance'){
                //     $result = $result->where('to_sg','4');
                // }
                if($function == "hrss"){
                    $result = $result->where('created_by','HR001');
                    $result = $result->groupBy('history_f_f.emp_id');
                    $result = $result->where('escalations.stage','1');
                    $result = $result->orWhere('escalations.stage','6');

                    // $result = $result->where('from_sg','1');
                    // $result = $result->orWhere('to_sg','6');
                }elseif($function == 'payroll'){
                    $result = $result->where('created_by', 'PRHR001');
                    $result = $result->groupBy('history_f_f.emp_id');
                    $result = $result->where('escalations.stage','2');

                    // $result = $result->where('to_sg','2');
                }elseif($function == 'qc'){
                    // dd(1);
                    $result = $result->where('created_by', 'PRQC001');
                    $result = $result->groupBy('history_f_f.emp_id');
                    $result = $result->where('escalations.stage','3');

                    // $result = $result->where('to_sg','3');
                }elseif($function == 'finance'){
                    // $result = $result->where('to_sg','4');
                    $result = $result->where('created_by', 'PRFN001');
                    $result = $result->groupBy('history_f_f.emp_id');
                    $result = $result->where('escalations.stage','4');
                    $result = $result->orWhere('escalations.stage','5');
                }else{
                    // dd($function);
                    $result = $result->groupBy('history_f_f.emp_id');
                }
            }

            $result = $result->groupBy('history_f_f.emp_id');

            
        // dd($result->get());
        return $result->get();

    }
    public function completed_within_tat($type,$function)
    {

            // $result = new History_f_f;
            // $result = $result->where('created_by', session('emp_id'));
            //     // ->orWhere('created_by', session('emp_id'));
            // $result = $result->groupBy('history_f_f.emp_id');
            $result = new emp_profile_tbl;
            $result = $result->where('emp_profile_tbls.f_f_document','Yes');
            $result = $result->leftJoin('escalations', 'emp_profile_tbls.emp_id', '=', 'escalations.emp_id');
            $result = $result->leftJoin('f__f_tracker_date_infos', 'emp_profile_tbls.emp_id', '=', 'f__f_tracker_date_infos.emp_id');
            $result = $result->whereNull('escalations.emp_id');
            $result = $result->select('emp_profile_tbls.*', 'escalations.emp_id AS escalations_emp_id');
            // dd($result);
            if(session('user_type') == 'F_F_HR' && $type != "function_report"){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','1');
                $result = $result->Where('emp_profile_tbls.f_f_c_s_g','!=','6');
            

            }else if(session('user_type') == 'Payroll_HR' && $type != "function_report"){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','2');

            }else if(session('user_type') == 'Payroll_Finance' && $type != "function_report"){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','5');
     
            }else if(session('user_type') == 'Payroll_QC' && $type != "function_report"){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','3');
     
            }
 
            if($type == 'daily_report'){
                $result = $result->where('f__f_tracker_date_infos.created_by', session('emp_id'));
                $result = $result->whereDate('f__f_tracker_date_infos.created_at', date('Y-m-d'));
                $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
            }else if($type == 'weekly_report'){
                $result = $result->where('f__f_tracker_date_infos.created_by', session('emp_id'));
                $result = $result->groupBy('f__f_tracker_date_infos.emp_id');

                $startDate = date('Y-m-d', strtotime('-7 days'));
                $endDate = date('Y-m-d', strtotime('-1 days'));
                $result = $result->whereBetween('f__f_tracker_date_infos.created_at', [$startDate, $endDate]);
            }else if($type == "function_report"){
                if($function == "hrss"){
                    $result = $result->where('created_by','HR001');
                    $result = $result->groupBy('f__f_tracker_date_infos.emp_id');

                }elseif($function == 'payroll'){
                    $result = $result->where('created_by', 'PRHR001');
                    $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
                }elseif($function == 'qc'){
                    $result = $result->where('created_by', 'PRQC001');
                    $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
                }elseif($function == 'finance'){
                    $result = $result->where('created_by', 'PRFN001');
                    $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
                }else{
                    $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
                }
            }else if($type == "ageing_report"){
                $result = $result->groupBy('f__f_tracker_date_infos.emp_id');

            }
            // dd($result);

            // $result = $result->leftJoin('escalations', 'history_f_f.emp_id', '=', 'escalations.emp_id');
            // $result = $result->whereNull('escalations.emp_id');
            // $result = $result->select('history_f_f.*', 'escalations.emp_id AS escalations_emp_id');
            // $result = $result->orderBy(...);
        // }
        // dd($result->get());

        // Retrieve the results
        return $result->get();
    }
    public function pending_beyond_tat($type,$function){
        // $result = new History_f_f;
        $result = new emp_profile_tbl;
        // $result = $result->where('sender_to', session('emp_id'));
            // ->orWhere('created_by', session('emp_id'));
        // $result = $result->groupBy('history_f_f.emp_id');
        $result = $result->join('f__f_tracker_date_infos', 'emp_profile_tbls.emp_id', '=', 'f__f_tracker_date_infos.emp_id');
        $result =$result ->join('escalations','emp_profile_tbls.emp_id','escalations.emp_id');
        if(session('user_type') == 'F_F_HR' && $type != "function_report"){
            $result = $result->where('emp_profile_tbls.f_f_c_s_g','1');
            $result = $result->orWhere('emp_profile_tbls.f_f_c_s_g','6');
            $result = $result->where('escalations.stage','1');
            $result = $result->orWhere('escalations.stage','6');

        }else if(session('user_type') == 'Payroll_HR' && $type != "function_report"){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['2']);
            $result = $result->where('escalations.stage','2');
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');

        }else if(session('user_type') == 'Payroll_Finance' && $type != "function_report"){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['4','5']);
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');
            $result = $result->where('escalations.stage','4');
            $result = $result->orWhere('escalations.stage','5');

        }else if(session('user_type') == 'Payroll_QC' && $type != "function_report"){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['3']);
            $result = $result->where('escalations.stage','3');
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');

        } 

        if($type == 'daily_report'){
            $result = $result->where('f__f_tracker_date_infos.created_at',date('Y-m-d'));
        }else if($type == 'weekly_report'){
            $startDate = date('Y-m-d', strtotime('-7 days'));
            $endDate = date('Y-m-d', strtotime('-1 days'));
 
            $result = $result->whereBetween('f__f_tracker_date_infos.created_at', [$startDate, $endDate]);
        }else if($type == "function_report"){
            // if($function == "hrss"){
            //     $result = $result->where('from_sg','1');
            //     $result = $result->orWhere('to_sg','6');
            // }elseif($function == 'payroll'){
            //     $result = $result->where('to_sg','2');
            // }elseif($function == 'qc'){
            //     $result = $result->where('to_sg','3');
            // }elseif($function == 'finance'){
            //     $result = $result->where('to_sg','4');
            // }
            if($function == "hrss"){
                // $result = $result->where('from_sg','1');
                // $result = $result->orWhere('to_sg','6');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['1','6']);

            }elseif($function == 'payroll'){
                // $result = $result->where('to_sg','2');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['2']);

            }elseif($function == 'qc'){
                // dd(1);  
                // $result = $result->where('to_sg','3');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['3']);

            }elseif($function == 'finance'){
                // $result = $result->where('to_sg','4');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['4','5']);
                $result = $result->where('escalations.stage','4');
                $result = $result->orWhere('escalations.stage','5');

            }
        }
        // $result = $result->select('history_f_f.*', 'escalations.emp_id AS escalations_emp_id');
        $result = $result->groupBy('f__f_tracker_date_infos.emp_id');

        return $result->get();

    }

    public function pending_within_tat($type,$function){
        $result = new emp_profile_tbl;
        
        // $result = $result->groupBy('history_f_f.emp_id');
        // $result = $result->join('emp_profile_tbls', 'emp_profile_tbls.emp_id', '=', 'history_f_f.emp_id');
        $result = $result->leftJoin('escalations', 'emp_profile_tbls.emp_id', '=', 'escalations.emp_id');
        $result = $result->leftJoin('f__f_tracker_date_infos', 'emp_profile_tbls.emp_id', '=', 'f__f_tracker_date_infos.emp_id');
        $result = $result->whereNull('escalations.emp_id');
        $result = $result->select('emp_profile_tbls.emp_id','emp_profile_tbls.emp_name', 'f__f_tracker_date_infos.created_at','f__f_tracker_date_infos.created_by','f__f_tracker_date_infos.s_g_id','escalations.emp_id AS escalations_emp_id');
        if(session('user_type') == 'F_F_HR' && $type != "function_report" ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['1','6']);
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');

        }else if(session('user_type') == 'Payroll_HR'  && $type != "function_report" ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['2']);
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');

        }else if(session('user_type') == 'Payroll_Finance' && $type != "function_report" ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['4','5']);
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');

        }else if(session('user_type') == 'Payroll_QC' && $type != "function_report" ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['3']);
            // $result = $result->where('emp_profile_tbls.f_f_c_s_g','5');

        } 


        if($type == 'daily_report'){
            $result = $result->whereDate('f__f_tracker_date_infos.created_at',date('Y-m-d'));

        }else if($type == 'weekly_report'){
            $startDate = date('Y-m-d', strtotime('-7 days'));
            $endDate = date('Y-m-d', strtotime('-1 days'));
 
            $result = $result->whereBetween('f__f_tracker_date_infos.created_at', [$startDate, $endDate]);
        }else if($type == "function_report"){
            if($function == "hrss"){
                // $result = $result->where('from_sg','1');
                // $result = $result->orWhere('to_sg','6');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['1','6']);

            }elseif($function == 'payroll'){
                // $result = $result->where('to_sg','2');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['2']);

            }elseif($function == 'qc'){
                // $result = $result->where('to_sg','3');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['3']);

            }elseif($function == 'finance'){
                // $result = $result->where('to_sg','4');
                $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['4','5']);

            }
        }
        $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
        // $result = $result->select('history_f_f.*', 'escalations.emp_id AS escalations_emp_id');
        return $result->get();

    }

    public function ageing_report(){
        $result = new emp_profile_tbl();
        $result =$result->where('status','Active');
        // $result =$result->join('f__f_tracker_date_infos as di','di.emp_id','emp_profile_tbls.emp_id');
        // $result =$result->leftJoin('f__f_tracker_date_infos as di', 'di.emp_id', 'emp_profile_tbls.emp_id');

        // $result =$result->where('di.created_by',session('emp_id'));
        if(session('user_type') == 'F_F_HR' ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g','>=','1');
 
        }else if(session('user_type') == 'Payroll_HR'){
            $result = $result->where('emp_profile_tbls.f_f_c_s_g','>=','2');
 
        }else if(session('user_type') == 'Payroll_Finance'){
            $result = $result->where('emp_profile_tbls.f_f_c_s_g','>=','4');
 
        }else if(session('user_type') == 'Payroll_QC'){
            $result = $result->where('emp_profile_tbls.f_f_c_s_g','>=','3'); 
        }
        return $result->get();
    }

    public function completed_beyond_tat_mr($from_date,$to_date){

        $result = new History_f_f;
            
            $result =$result ->join('escalations','history_f_f.emp_id','escalations.emp_id');
            
            if(session('user_type') == 'F_F_HR' ){
                $result = $result->Where('created_by',session('emp_id'));

                $result = $result->where('escalations.stage','1');
                $result = $result->orWhere('escalations.stage','6');
                $result = $result->whereBetween('date', [$from_date, $to_date]);

            }else if(session('user_type') == 'Payroll_HR'){
                $result = $result->Where('created_by',session('emp_id'));

                // $result = $result->where('history_f_f.from_sg','2');
                $result = $result->where('escalations.stage','2');
                // $result = $result->orWhere('escalations.stage','6');

            }else if(session('user_type') == 'Payroll_QC'){

                // $result = $result->where('history_f_f.from_sg','3');
                $result = $result->Where('created_by',session('emp_id'));

                $result = $result->where('escalations.stage','3');

            }else if(session('user_type') == 'Payroll_Finance'){
                $result = $result->Where('created_by',session('emp_id'));

                $result = $result->where('escalations.stage','4');
                $result = $result->orWhere('escalations.stage','5');

            }
            $result = $result->whereBetween('date', [$from_date, $to_date]);
            $result = $result->groupBy('history_f_f.emp_id');

            
        return $result->get();

    }
    public function completed_within_tat_mr($from_date,$to_date)
    {
            // $result = new History_f_f;
            // $result = $result->where('created_by', session('emp_id'));
            //     // ->orWhere('created_by', session('emp_id'));
            // $result = $result->groupBy('history_f_f.emp_id');
            $result = new emp_profile_tbl;
            $result = $result->where('emp_profile_tbls.f_f_document','Yes');
            $result = $result->leftJoin('escalations', 'emp_profile_tbls.emp_id', '=', 'escalations.emp_id');
            $result = $result->leftJoin('f__f_tracker_date_infos', 'emp_profile_tbls.emp_id', '=', 'f__f_tracker_date_infos.emp_id');
            $result = $result->whereNull('escalations.emp_id');
            $result = $result->select('emp_profile_tbls.emp_id','emp_profile_tbls.emp_name','f__f_tracker_date_infos.*', 'escalations.emp_id AS escalations_emp_id');
            // dd($result);
            if(session('user_type') == 'F_F_HR' ){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','1');
                $result = $result->orWhere('emp_profile_tbls.f_f_c_s_g','!=','6');
            

            }else if(session('user_type') == 'Payroll_HR' ){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','2');

     
            }else if(session('user_type') == 'Payroll_Finance' ){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','5');
     
            }else if(session('user_type') == 'Payroll_QC' ){
                $result = $result->where('emp_profile_tbls.f_f_c_s_g','>','3');
     
            }
            $result = $result->whereBetween('f__f_tracker_date_infos.created_at', [$from_date, $to_date]);
 
            
            $result = $result->groupBy('f__f_tracker_date_infos.emp_id');

        return $result->get();
    }
    public function pending_beyond_tat_mr($from_date,$to_date){
        $result = new emp_profile_tbl;
        $result = $result->join('f__f_tracker_date_infos', 'emp_profile_tbls.emp_id', '=', 'f__f_tracker_date_infos.emp_id');
        $result =$result ->join('escalations','emp_profile_tbls.emp_id','escalations.emp_id');
        if(session('user_type') == 'F_F_HR'){
            $result = $result->where('emp_profile_tbls.f_f_c_s_g','1');
            $result = $result->orWhere('emp_profile_tbls.f_f_c_s_g','6');
            $result = $result->where('escalations.stage','1');
            $result = $result->orWhere('escalations.stage','6');

        }else if(session('user_type') == 'Payroll_HR'){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['2']);
            $result = $result->where('escalations.stage','2');

        }else if(session('user_type') == 'Payroll_Finance' ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['4','5']);
            $result = $result->where('escalations.stage','4');
            $result = $result->orWhere('escalations.stage','5');

        }else if(session('user_type') == 'Payroll_QC'){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['3']);
            $result = $result->where('escalations.stage','3');

        } 

        
        $result = $result->whereBetween('f__f_tracker_date_infos.created_at', [$from_date, $to_date]);
        $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
        return $result->get();

    }

    public function pending_within_tat_mr($from_date,$to_date){
        $result = new emp_profile_tbl;
        
        $result = $result->leftJoin('escalations', 'emp_profile_tbls.emp_id', '=', 'escalations.emp_id');
        $result = $result->leftJoin('f__f_tracker_date_infos', 'emp_profile_tbls.emp_id', '=', 'f__f_tracker_date_infos.emp_id');
        $result = $result->whereNull('escalations.emp_id');
        $result = $result->select('emp_profile_tbls.*', 'escalations.emp_id AS escalations_emp_id');
        if(session('user_type') == 'F_F_HR'){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['1','6']);

        }else if(session('user_type') == 'Payroll_HR' ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['2']);

        }else if(session('user_type') == 'Payroll_Finance'){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['4','5']);

        }else if(session('user_type') == 'Payroll_QC' ){
            $result = $result->whereIn('emp_profile_tbls.f_f_c_s_g',['3']);

        } 

        $result = $result->whereBetween('f__f_tracker_date_infos.created_at', [$from_date, $to_date]);
        $result = $result->groupBy('f__f_tracker_date_infos.emp_id');
        return $result->get();
        
    }

    public function save_reopen_date($credentials)
    {

        $querytbl = new ReopenHistory();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->s_g_id = $credentials['s_g'];
        $querytbl->reopened_by = $credentials['created_by'];
        $querytbl->save();
    }


}
