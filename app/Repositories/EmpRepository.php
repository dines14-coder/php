<?php

namespace App\Repositories;

use DB;
use App\Models\emp_profile_tbl;
 
class EmpRepository implements IEmpRepository
{
    public function add_ambassador( $credentials ) {
        $querytbl = new emp_profile_tbl();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->emp_name = $credentials['emp_name'];
        $querytbl->pan_no = $credentials['pan_no'];
        $querytbl->dob = $credentials['dob'];
        $querytbl->mobileno = $credentials['mobileno'];
        $querytbl->email = $credentials['email'];
        $querytbl->type_of_leaving = $credentials['type_of_leaving'];
        $querytbl->last_working_date = $credentials['last_working_date'];
        $querytbl->f_f_document = $credentials['f_f_document'];
        $querytbl->f_f_c_s_g = $credentials['f_f_c_s_g'];

        $querytbl->cl_c_p = $credentials['cl_c_p'];
        $querytbl->fn_c_p = $credentials['fn_c_p'];
        $querytbl->pr_c_p = $credentials['pr_c_p'];
        $querytbl->hr_ld_c_p = $credentials['hr_ld_c_p'];
        $querytbl->it_c_p = $credentials['it_c_p'];
        $querytbl->it_inf_c_p = $credentials['it_inf_c_p'];

        $querytbl->address = ""; 
        $querytbl->state = "";
        $querytbl->city = "";
        $querytbl->otp = "";
        $querytbl->password = $credentials['password'];
        $querytbl->is_first_login = $credentials['is_first_login'] ?? true;
        $querytbl->real_pass = "";
        $querytbl->status = $credentials['status'];
        $querytbl->doc_status = $credentials['doc_status'];
        $querytbl->ff_doc_updated_by = $credentials['ff_doc_updated_by'];
        $querytbl->s_doc_updated_by = $credentials['s_doc_updated_by'];
        $querytbl->remark = "";
        $querytbl->save();

    }

    public function emp_reg_check_type_1($credentials){
        $get_result = DB::table('emp_profile_tbls')
        ->select('emp_profile_tbls.*')
        ->where('emp_id','=',$credentials['emp_id'])
        // ->where('pan_no','=',$credentials['pan_no'])
        ->where('status','=',"Active")
        ->limit(1)
        ->get(); 

        return $get_result;
    }

    public function emp_reg_check_type_2($credentials){
        $get_result = DB::table('emp_profile_tbls')
        ->select('emp_profile_tbls.*')
        ->where('emp_id','=',$credentials['emp_id'])
        ->where('dob','=',$credentials['dob'])
        ->limit(1)
        ->get(); 

        return $get_result;
    }

    public function get_employee_detail($emp_id){
        $get_result = DB::table('emp_profile_tbls')
        ->select('emp_profile_tbls.*')
        ->where('emp_id','=',$emp_id)
        ->limit(1)
        ->get(); 

        return $get_result;
    }

    public function get_employee_detail_u_mail($email){
        $get_result = DB::table('emp_profile_tbls')
        ->select('emp_profile_tbls.*')
        ->where('email','=',$email)
        ->where('status','=',"Active")
        ->limit(1)
        ->get(); 

        return $get_result;
    }

    public function upd_amb_pass($credentials){
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('email', '=', $credentials['email']);
        $update_row->update(['password' => bcrypt( $credentials['password'] ),'real_pass' => $credentials['password']]);
    }

    public function update_p_one_alumni_f_f_s_g($credentials){
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        if(isset($credentials['revert_status'])){
            $update_row->update(['f_f_c_s_g' =>$credentials['f_f_c_s_g'] ,'revert_status'=>""]);
        }else{
            $update_row->update(['f_f_c_s_g' =>$credentials['f_f_c_s_g'] ]);
        }
    }

    

    public function upd_amb_pass_u_empid($credentials){
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $updateData = ['password' => bcrypt( $credentials['password'] ),'real_pass' => $credentials['password']];
        
        // Mark as not first login if updating password
        if (isset($credentials['mark_not_first_login']) && $credentials['mark_not_first_login']) {
            $updateData['is_first_login'] = false;
        }
        
        $update_row->update($updateData);
    }

    public function approve_amb_status($credentials){
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['password' => bcrypt( $credentials['password'] ),'real_pass' => $credentials['password'],'status' =>  $credentials['status'],'doc_status' =>  $credentials['doc_status'],'type_of_leaving' =>  $credentials['type_of_leaving'],'last_working_date' =>  $credentials['last_working_date'],'f_f_document' =>  $credentials['f_f_document'],'f_f_c_s_g' =>  $credentials['f_f_c_s_g'],'cl_c_p' =>  $credentials['cl_c_p'],'fn_c_p' =>  $credentials['fn_c_p'],'pr_c_p' =>  $credentials['pr_c_p'],'hr_ld_c_p' =>  $credentials['hr_ld_c_p'],'it_c_p' =>  $credentials['it_c_p'],'it_inf_c_p' =>  $credentials['it_inf_c_p'],'remark'=>$credentials['dec_remark']]);
    }

    public function declines_amb_status($credentials){
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['status' =>  $credentials['status'],'remark'=>$credentials['dec_remark']]);
    }
    
    public function emp_otp_update($credentials){

        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['otp' => $credentials['otp'],'password' => bcrypt( $credentials['otp'] ),'real_pass' => $credentials['otp'], 'status' =>  $credentials['status']]);
                
    }

    public function emp_update_after_valid_otp($credentials){

        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['mobileno' => $credentials['mobileno'],'email' => $credentials['email'],'password' => bcrypt( $credentials['password'] ),'real_pass' => $credentials['password'], 'status' =>  $credentials['status']]);
                
    }

    public function total_emp_count()
    {
        $get_result = DB::table('emp_profile_tbls')
        ->select('emp_profile_tbls.*')
        ->count(); 
        return $get_result;
    }

    public function total_emp_count_w_s($credentials)
    {
        $get_result = DB::table('emp_profile_tbls')
        ->select('emp_profile_tbls.*')
        ->where('status','=',$credentials['status'])
        ->count(); 
        return $get_result;
    }

    public function check_row_based_con($row,$credential)
    {
        $querytbl = new emp_profile_tbl(); 
        $querytbl = $querytbl->where( $row,'=', $credential );
        return $querytbl = $querytbl->get();
    }

    public function update_docstatus_and_rem($credentials)
    {
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['doc_status' => $credentials['doc_status'],'remark_2' => $credentials['remark'],'ff_doc_updated_by' => $credentials['ff_doc_updated_by']]);
    }
    // public function update_docstatus_two_and_rem($credentials)
    // {
    //     $update_row = new emp_profile_tbl();
    //     $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
    //     $update_row->update(['doc_status_two' => $credentials['doc_status_two'],'remark_2' => $credentials['remark_2'],'s_doc_updated_by' => $credentials['s_doc_updated_by']]);
    // }

    public function update_amb_form($credentials){
        $update_row = new emp_profile_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['emp_name' => $credentials['emp_name'],'pan_no' => $credentials['pan_no'],'dob' => $credentials['dob'],'mobileno' => $credentials['mobileno'],'email' => $credentials['email'],'last_working_date' => $credentials['lwd']]);
    }

    // public function CheckAlreadyExist($credentials)
    // {
    //     $get_result = emp_profile_tbl::where(function($query) use($credentials) {
    //         $query->orwhere('emp_id',$credentials['emp_id']);
    //         $query->orwhere('email',$credentials['email']);
    //         $query->orwhere('pan_no',$credentials['pan_no']);
    //         $query->orwhere('mobileno',$credentials['mobile']);
    //     })->where('status',$credentials['status'])->get();
    //     return $get_result;
    // }

    // public function update_reg_form($credentials){
    //     $update_row = new emp_profile_tbl();
    //     $update_row = $update_row->orwhere('emp_id', '=', $credentials['emp_id']);
    //     $update_row = $update_row->orwhere('email', '=', $credentials['email']);
    //     $update_row = $update_row->orwhere('pan_no', '=', $credentials['pan_no']);
    //     $update_row = $update_row->orwhere('mobileno', '=', $credentials['mobileno']);
    //     $update_row->update(['emp_id' => $credentials['emp_id'],'emp_name' => $credentials['emp_name'],'pan_no' => $credentials['pan_no'],'dob' => $credentials['dob'],'mobileno' => $credentials['mobileno'],'email' => $credentials['email'],'status' => $credentials['status']]);
    // }

}