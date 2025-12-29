<?php

namespace App\Repositories;

use DB;
use App\Models\admin_tbl;
 
class AdminRepository implements IAdminRepository
{
    
    public function admin_login_check($credentials){
        $get_result = DB::table('admin_tbls')
        ->select('admin_tbls.*')
        ->where('email','=',$credentials['emp_id'])
        ->where('password','=',$credentials['password'])
        ->limit(1)  
        ->get(); 

        return $get_result;
    }

    public function under_admin_emp($credentials)
    {
        $get_result = DB::table('admin_tbls')
        ->select('admin_tbls.*')
        ->where('head','=',$credentials['user_id'])
        ->where('status','=',"Active")
        ->get(); 

        return $get_result;
    }

    public function upd_adm_pass_u_empid($credentials)
    {
        $update_row = new admin_tbl();
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['password' => base64_encode($credentials['password']) ,'real_pass' => $credentials['password'] ]);
    }

}