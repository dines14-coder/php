<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IEmpRepository;
use App\Repositories\IQueryRepository;
use Validator;


class PageController extends Controller
{
    //
    public function __construct( IEmpRepository $emp_task, IQueryRepository  $query_task ) {
        $this->middleware( 'auth' );
        $this->middleware( 'check.first.login' );
        $this->query_task = $query_task;
        $this->emp_task = $emp_task;
    }

    public function password_update_landing() {
        $user = auth()->user();
        $isFirstLogin = $user->is_first_login ?? false;
        
        return view( 'password_update_landing', compact('isFirstLogin') );
    }

    public function check_password(Request $req) { 
        $old_password = $req->input( 'old_password' );
        $emp_id = auth()->user()->emp_id;

        $credentials = [ 
            'emp_id' => $emp_id,
            'password' => $old_password,
        ];

        if ( auth()->attempt( $credentials, true ) ) {
            return response()->json( ['logstatus' => 'Matching'] );
        }
        else{ 
            return response()->json( ['logstatus' => 'Not Matching'] );
        }
    

    }

    public function update_pass(Request $req) { 
        $mes = [
            'new_password.required'=>"The New Password field is required.",
            'new_confirm_password.required'=>"The Confirm Password field is required.",
            'old_password.required'=>"The Old Password field is required.",
            'new_password.different'=>"New password must be different from old password."
        ];
        
        $validator= Validator::make($req->all(),[
            'new_password' =>'required|different:old_password',
            'new_confirm_password' =>'required|same:new_password',
            'old_password' =>'required'
        ],$mes);
        
        if($validator->passes()){ 
            // Verify old password
            $emp_id = auth()->user()->emp_id;
            $credentials = [ 
                'emp_id' => $emp_id,
                'password' => $req->input('old_password'),
            ];
            
            if (!auth()->attempt($credentials, true)) {
                return response()->json([
                    'status' => 0,
                    'error' => ['old_password' => ['Old password is incorrect']]
                ]);
            }
            
            $new_password = $req->input( 'new_confirm_password' );
            
            $credentials=[
                'emp_id'=>$emp_id,
                'password'=>$new_password,
                'mark_not_first_login'=>true,
            ];
            $update_query = $this->emp_task->upd_amb_pass_u_empid( $credentials );
        
            $logstatus = 'success';
            return response()->json( ['logstatus' => $logstatus, 'redirect_to_login' => true] );
        }
        else{
            return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
        }
    }
    
    public function dashboard() {

        $user = auth()->user();
        $emp_id=$user->emp_id;
        $credential=[
            'emp_id'=>$emp_id,
            'status'=>'Pending',
        ];
        $get_pending_query_cnt = $this->query_task->get_query_detail_count( $credential );

        $credential=[
            'emp_id'=>$emp_id,
            'status'=>'Approved',
        ];
        $get_approved_query_cnt = $this->query_task->get_query_detail_count( $credential );

        $credential=[
            'emp_id'=>$emp_id,
            'status'=>'Completed',
        ];
        $get_com_query_cnt = $this->query_task->get_query_detail_count( $credential );


        return view( 'dashboard' )->with( ['get_pending_query_cnt'=> $get_pending_query_cnt,'get_approved_query_cnt'=> $get_approved_query_cnt,'get_com_query_cnt'=> $get_com_query_cnt] );
    }

   


}


