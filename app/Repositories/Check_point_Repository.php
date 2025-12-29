<?php

namespace App\Repositories;

use App\Models\emp_profile_tbl;
use App\Models\f_f_check_point;
use DB;
use Auth;
use Mail;

class Check_point_Repository implements ICheck_point_Repository
{

    public function get_c_p_data($credentials)
    {
        $get_result = DB::table('questions_table')
            ->select('questions_table.*')->where('status',1)
            ->get();
        return $get_result;
    }

    public function get_c_p_data_to_fill($credentials)
    {
        $get_result = DB::table('questions_table')
            ->select('questions_table.*')
            ->where('role_id', '=', $credentials['user_type'])
            ->get();
        return $get_result;
    }   

    public function get_all_check_point_b_role()
    {
        $userType = session()->get('user_type');

        if ($userType == "F_F_HR") {
            $check_col = "HR-LEAD";
        } else {
            $check_col = $userType;
        }
        $get_result = DB::table('questions_table')
            ->select('questions_table.*')
            ->where('role_id', '=', $check_col)
            ->get();
// dd($get_result);
        return $get_result;
    }

    public function get_all_completed_check_point($credentials)
    {
        $userType = session()->get('user_type');

        if ($userType == "F_F_HR") {
            $get_result = DB::table('f_f_check_points')
            ->select('*')
            ->where('emp_id', '=', $credentials['emp_id'])
            ->where('created_by', '=', $credentials['created_by'])
            ->orWhere('created_by', '=', 'HRL001')
            ->where('rating', '!=', "")
            ->get();
        return $get_result;
        } else {
            $get_result = DB::table('f_f_check_points')
            ->select('*')
            ->where('emp_id', '=', $credentials['emp_id'])
            ->where('created_by', '=', $credentials['created_by'])
            ->where('rating', '!=', "")
            ->get();
        return $get_result;
        }
        
    }

    public function get_c_p_emp_data($credentials)
    {
        if (session()->get('user_type') == "Claims") {
            $check_col = "cl_c_p";
        }
        if (session()->get('user_type') == "Payroll_Finance") {
            $check_col = "fn_c_p";
        }
        if (session()->get('user_type') == "Payroll_HR") {
            $check_col = "pr_c_p";
        }
        if (session()->get('user_type') == "HR-LEAD") {
            $check_col = "hr_ld_c_p";
        }
        if (session()->get('user_type') == "Payroll_IT") {
            $check_col = "it_c_p";
        }
        if (session()->get('user_type') == "IT-INFRA") {
            $check_col = "it_inf_c_p";
        }
        if (session()->get('user_type') == "F_F_HR") {
            $check_col = "hr_ld_c_p";
        }
        
        $get_result = DB::table('emp_profile_tbls');
        $get_result = $get_result->select('emp_profile_tbls.emp_id', 'emp_profile_tbls.f_f_c_s_g');
        $get_result = $get_result->where('f_f_document', 'yes');
        
        // Apply specific visibility rules based on user type and F&F Tracker stage
        if (session()->get('user_type') == "F_F_HR") {
            // F_F_HR can see tickets at any stage
            $get_result = $get_result->where($check_col, '=', $credentials['status']);
        } 
        else if (session()->get('user_type') == "Payroll_HR") {
            // Payroll_HR (akashraj@hepl.com) can only see tickets after F_F_HR approves (stage 2)
            $get_result = $get_result->where($check_col, '=', $credentials['status'])
                                    ->where('f_f_c_s_g', '>=', '2');
        }
        else if (session()->get('user_type') == "Payroll_QC") {
            // Payroll_QC can only see tickets after Payroll_HR approves (stage 3)
            $get_result = $get_result->where($check_col, '=', $credentials['status'])
                                    ->where('f_f_c_s_g', '>=', '3');
        }
        else if (session()->get('user_type') == "Claims") {
            // Claims can only see tickets after Payroll_QC approves (stage 3.5)
            $get_result = $get_result->where($check_col, '=', $credentials['status'])
                                    ->where('f_f_c_s_g', '>=', '3.5');
        }
        else if (session()->get('user_type') == "Payroll_Finance") {
            // Payroll_Finance (accounts@hepl.com) can only see tickets after Claims approves (stage 4)
            $get_result = $get_result->where($check_col, '=', $credentials['status'])
                                    ->where('f_f_c_s_g', '>=', '4')
                                    ->where('checkpoint_Status', 'yes');
        }
        
        if ($credentials['emp_id'] != "") {
            $get_result = $get_result->where('emp_id', '=', $credentials['emp_id']);
        }
        
        $get_result = $get_result->get();
        return $get_result;
    }

    public function check_q_availablity($credentials)
    {
        $get_result = DB::table('f_f_check_points')
            ->select('*')
            ->where('emp_id', '=', $credentials['emp_id'])
            ->where('question_id', '=', $credentials['question_id'])
            ->get();
        return $get_result;
    }

    public function inset_q_rating($credentials)
    {
        $querytbl = new f_f_check_point();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->question_id = $credentials['question_id'];
        $querytbl->rating = $credentials['rating'];
        $querytbl->remarks = $credentials['remarks'];
        $querytbl->created_by = $credentials['created_by'];
        $querytbl->save();

        // Update F&F Tracker stage when Claims approves
        if(session()->get('user_type') == "Claims"){
            $emp_profile = DB::table('emp_profile_tbls')
                ->where('emp_id', $credentials['emp_id'])
                ->where('f_f_c_s_g', '3.5')
                ->first();
                
            if($emp_profile) {
                DB::table('emp_profile_tbls')
                    ->where('emp_id', $credentials['emp_id'])
                    ->update(['f_f_c_s_g' => '4']);
            }
        }
    }
    public function get_claims($emp_id){
        $update_row =  DB::table('f_f_check_points');
        $update_row =  $update_row->select('emp_id','created_at');
        $update_row = $update_row->where('emp_id', '=', $emp_id);
        $update_row = $update_row->where('created_by', '=', 'CL001');
        $update_row = $update_row->first();
        return $update_row;

    }

    public function update_q_rating($credentials)
    {
        $update_row = new f_f_check_point();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row = $update_row->where('question_id', '=', $credentials['question_id']);
        $update_row->update(['rating' => $credentials['rating'], 'remarks' => $credentials['remarks']]);
        // dd($update_row);
        return $update_row;
        // $user_id = session('user_type') ;
        // dd($user_id);
        
    }

    public function update_f_f_status($credentials)
    {
        if (session()->get('user_type') == "Claims") {
            $check_col = "cl_c_p";
        }
        if (session()->get('user_type') == "Payroll_Finance") {
            $check_col = "fn_c_p";
        }
        if (session()->get('user_type') == "Payroll_HR") {
            $check_col = "pr_c_p";
        }
        if (session()->get('user_type') == "HR-LEAD") {
            $check_col = "hr_ld_c_p";
        }
        if (session()->get('user_type') == "Payroll_IT") {
            $check_col = "it_c_p";
        }
        if (session()->get('user_type') == "IT-INFRA") {
            $check_col = "it_inf_c_p";
        }
        if (session()->get('user_type') == "F_F_HR") {
            $check_col = "hr_ld_c_p";
        }

        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update([$check_col => $credentials['status']]);
        
        // Update F&F Tracker stage when Claims completes their checkpoints
        if (session()->get('user_type') == "Claims" && $credentials['status'] == "Completed") {
            $emp_profile = DB::table('emp_profile_tbls')
                ->where('emp_id', $credentials['emp_id'])
                ->where('f_f_c_s_g', '3.5')
                ->first();
                
            if($emp_profile) {
                DB::table('emp_profile_tbls')
                    ->where('emp_id', $credentials['emp_id'])
                    ->update(['f_f_c_s_g' => '4']);
                    
                // Send notification to accounts@hepl.com
                $toname = 'Accounts Team';
                $team = 'Employee Claims';
                $tomail = 'accounts@hepl.com';
                
                $raiseDeti = [
                    'body_content1' => 'Hello '.$toname.'! ',
                    'body_content2' => 'Hi, the '.$team.' team has completed F&F Checkpoints for '.$credentials['emp_id'].' that requires your attention.',
                    'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                    'body_content5' => 'Cheers',
                    'body_content6' => 'Team '.$team,
                ];
                \Mail::to($tomail)->send(new \App\Mail\NewCaseEmailNotification($raiseDeti));
            }
        }
    }

    public function get_admin_tbl($table, $column, $value)
    {
        $record = DB::table($table)->where($column, $value)->get();
        return $record;
    }

}
