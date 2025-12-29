<?php

namespace App\Http\Controllers\S_AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\IQueryRepository;
use App\Repositories\IEmpRepository;
use App\Repositories\IAdminRepository;

class PageController extends Controller
{
    // 
    public function __construct(IEmpRepository $emp_task,IQueryRepository  $query_task,IAdminRepository $admin_task  ) {
        $this->middleware( 'adminLog' );
        $this->query_task = $query_task; 
        $this->emp_task = $emp_task;
        $this->admin_task = $admin_task;
    }
    public function dashboard() {
        
        $get_tlt_emp_cnt = $this->emp_task->total_emp_count( );


        if(session()->get('user_type')=="F_F_Admin" ){
            $lvl="1";
        }
        


        $credential=[
            'status'=>'Active',
        ];
        $get_verified_tlt_emp_cnt = $this->emp_task->total_emp_count_w_s( $credential );

        $credential=[
            'status'=>'Pending',
            'lvl'=>$lvl,
        ];
        $get_pen_query_cnt = $this->query_task->adm_get_query_detail_count( $credential );

            $credential=[
                'status'=>'Completed',
                'lvl'=>$lvl,
            ];
            $get_com_query_cnt = $this->query_task->adm_get_query_com_count_s_a( $credential );

        
        return view( 'S_Admin.dashboard' )->with( ['get_tlt_emp_cnt'=> $get_tlt_emp_cnt,'get_verified_tlt_emp_cnt'=> $get_verified_tlt_emp_cnt,'get_pen_query_cnt'=> $get_pen_query_cnt,'get_com_query_cnt'=>$get_com_query_cnt] );
    }

    public function p_s_dashboard() {
        
        $get_tlt_emp_cnt = $this->emp_task->total_emp_count( );

        $credential=[
            'status'=>'Active',
        ];
        $get_verified_tlt_emp_cnt = $this->emp_task->total_emp_count_w_s( $credential );

        $credential=[
            'status'=>'Pending',
        ];
        $get_pen_query_cnt = $this->query_task->adm_get_query_detail_count_1( $credential );

        $credential=[
            'status'=>'Completed',
        ];
        $get_com_query_cnt = $this->query_task->adm_get_query_detail_count_1( $credential );

        return view( 'P_S_Admin.dashboard' )->with( ['get_tlt_emp_cnt'=> $get_tlt_emp_cnt,'get_verified_tlt_emp_cnt'=> $get_verified_tlt_emp_cnt,'get_pen_query_cnt'=> $get_pen_query_cnt,'get_com_query_cnt'=>$get_com_query_cnt] );
    }

    public function adm_get_emp_sel_box(Request $request)
    {
        $credentials=[
            'user_id'=>session()->get('emp_id'), 
        ];

        $get_user_row = $this->admin_task->under_admin_emp( $credentials );
       
        // $emp_div='<option value="">Choose HR</option>';
        $emp_div='<option value="">All</option>';

        foreach ($get_user_row as $key => $user_data) {
            $emp_div.='<option value="'.$user_data->emp_id.'">'.$user_data->emp_name.' / '.$user_data->emp_id.'</option>';
        }

        $resp="Success";
        return response()->json(['response'=>$resp,'emp_div'=>$emp_div]);
    }
}
