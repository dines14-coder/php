<?php

namespace App\Repositories;

use DB;
// use App\Models\admin_tbl;
 
class F_and_F_Repository implements IF_and_F_Repository
{
    
    public function get_all_data($credentials){
        $get_result = DB::table('questions_table')
        ->select('questions_table.*')
        // ->where('emp_id','=',$credentials['emp_id'])
        // ->where('password','=',$credentials['password'])
        // ->limit(1)  
        ->get(); 

        return $get_result;
    }

}