<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Repositories\IAdminRepository;
use App\Repositories\IEmpRepository;
use App\Repositories\IQueryRepository;
use Illuminate\Http\Request;
use Validator;

class PageController extends Controller
{
    public function __construct(IEmpRepository $emp_task, IQueryRepository $query_task, IAdminRepository $admin_task)
    {
        $this->middleware('adminLog');
        $this->query_task = $query_task;
        $this->emp_task = $emp_task;
        $this->admin_task = $admin_task;
    }

    public function password_update_landing()
    {
        return view('admin_password_update_landing');
    }

    public function check_password(Request $request)
    {
        $old_password = $request->input('old_password');
        $emp_id = session()->get('emp_id');

        $credentials = [
            'emp_id' => $emp_id,
            'password' => base64_encode($request->input('old_password')),
            'status' => 'Active',
        ];

        $get = $this->admin_task->admin_login_check($credentials);
        $account_row = count($get);
        if ($account_row == "1") {
            return response()->json(['logstatus' => 'Matching']);
        } else {
            return response()->json(['logstatus' => 'Not Matching']);
        }

    }

    public function update_pass(Request $request)
    {
        $mes = ['new_password.required' => "The New Password field is required.", 'new_confirm_password.required' => "The Confirm Password field is required.", 'old_password.required' => "The Old Password field is required."];
        $validator = Validator::make($request->all(), [
            'new_password' => 'required', 'new_confirm_password' => 'required', 'old_password' => 'required',
        ], $mes);
        if ($validator->passes()) {
            $new_password = $request->input('new_confirm_password');
            $emp_id = session()->get('emp_id');

            $credentials = [
                'emp_id' => $emp_id,
                'password' => $request->input('new_confirm_password'),
            ];
            $update_query = $this->admin_task->upd_adm_pass_u_empid($credentials);

            $logstatus = 'success';
            return response()->json(['logstatus' => $logstatus]);
        } else {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

    }

    public function dashboard()
    {
        if (session()->get('user_type') != 'Payroll_QC') {
            $get_tlt_emp_cnt_resettlement = '';
            $get_pen_query_cnt_resttlement = '';
            $get_com_query_cnt_resettlement = '';
            $get_verified_tlt_emp_cnt_resttlement = '';
            $get_tlt_emp_cnt = $this->emp_task->total_emp_count();

            // if(session()->get('user_type')=="F_F_HR" ){
            //     $lvl="1";
            // }

            $credential = [
                'status' => 'Active',
            ];
            $get_verified_tlt_emp_cnt = $this->emp_task->total_emp_count_w_s($credential);

            $credential = [
                'status' => 'Pending',
                // 'lvl'=>$lvl,
            ];
            $get_pen_query_cnt = $this->query_task->adm_get_query_detail_count($credential);

            if (session()->get('user_type') == "F_F_HR") {

                $credential = [
                    'status' => 'Completed',
                    // 'lvl'=>$lvl,
                    'emp_id' => session()->get('emp_id'),
                ];
                $get_com_query_cnt = $this->query_task->adm_get_query_com_count($credential);

            } else {
                $credential = [
                    'status' => 'Completed',
                    // 'lvl'=>$lvl,
                ];
                $get_com_query_cnt = $this->query_task->adm_get_query_detail_count($credential);
            }

        }
        if (session()->get('user_type') == 'Payroll_QC') {
            $user = auth()->user();
            $credential = [
                'f_f_c_s_g' => [3],
                'type' => 'fresh',
            ];
            $get_pen_query_cnt = $this->query_task->get_qc_detail_count($credential);

            $credential = [
                'f_f_c_s_g' => 3,
                'type' => 'revert',
            ];
            $get_verified_tlt_emp_cnt = $this->query_task->get_qc_detail_count($credential);

            $credential = [
                'f_f_c_s_g' => 4,
                'type' => 'greater',
            ];
            $get_com_query_cnt = $this->query_task->get_qc_detail_count($credential);

            $credential = [
                'f_f_c_s_g' => [3],
                'type' => 'fresh_r',
            ];
            $get_pen_query_cnt_resttlement= $this->query_task->get_qc_detail_count($credential);

            $credential = [
                'f_f_c_s_g' => 3,
                'type' => 'revert_r',
            ];
            $get_verified_tlt_emp_cnt_resttlement = $this->query_task->get_qc_detail_count($credential);

            $credential = [
                'f_f_c_s_g' => 4,
                'type' => 'greater_r',
            ];
            $get_com_query_cnt_resettlement = $this->query_task->get_qc_detail_count($credential);

            // $get_tlt_emp_cnt = $get_pen_query_cnt + $get_verified_tlt_emp_cnt + $get_com_query_cnt;
            $get_tlt_emp_cnt = $get_pen_query_cnt + $get_com_query_cnt;
            $get_tlt_emp_cnt_resettlement = $get_pen_query_cnt_resttlement+ $get_com_query_cnt_resettlement;
        }
        return view('Admin.dashboard')->with(['get_tlt_emp_cnt' => $get_tlt_emp_cnt, 'get_verified_tlt_emp_cnt' => $get_verified_tlt_emp_cnt, 'get_pen_query_cnt' => $get_pen_query_cnt, 'get_com_query_cnt' => $get_com_query_cnt,
                                            'get_tlt_emp_cnt_resettlement' => $get_tlt_emp_cnt_resettlement, 'get_verified_tlt_emp_cnt_resttlement' => $get_verified_tlt_emp_cnt_resttlement, 'get_pen_query_cnt_resttlement' => $get_pen_query_cnt_resttlement, 'get_com_query_cnt_resettlement' => $get_com_query_cnt_resettlement]);
    }
}
