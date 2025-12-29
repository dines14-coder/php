<?php

namespace App\Http\Controllers\F_and_F_document;

use App\Http\Controllers\Controller;
// use App\Mail\NotifyMail;
use App\Repositories\ICheck_point_Repository;
use App\Repositories\IF_F_tracker_Repository;
use DataTables;
use Illuminate\Http\Request;
use App\Models\emp_profile_tbl;

// use Mail;

class F_and_F_document extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(ICheck_point_Repository $check_point_task, IF_F_tracker_Repository $f_f_tracker_task)
    {
        $this->middleware('adminLog');
        $this->check_point_task = $check_point_task;
        $this->f_f_tracker_task = $f_f_tracker_task;
    }

    public function index()
    {
        return view('F_and_F_document/form');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_c_p_datatable(Request $request)
    {

        $user_type = session()->get('user_type');
        $emp_id = $request->emp_id;

        if ($request->ajax()) {
            $start_date = (!empty($_POST["start_date"])) ? ($_POST["start_date"]) : ('');
            $end_date = (!empty($_POST["end_date"])) ? ($_POST["end_date"]) : ('');
            $type = (!empty($_POST["type"])) ? ($_POST["type"]) : ('');
            $hr_id = (!empty($_POST["hr_id"])) ? ($_POST["hr_id"]) : ('');

            if ($start_date || $end_date) {
                $filter_data = [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => $type,
                    'updated_by' => $hr_id,
                ];
                // $getquerydetails = $this->query_task->get_admin_query($filter_data);
            } else {
                if (session()->get('user_type') == 'F_F_HR') {
                    $filter_data = [
                        'user_type' => 'HR-LEAD',
                        'status' => $type,
                    ];
                } else {
                    $filter_data = [
                        'user_type' => $user_type,
                        'status' => $type,
                    ];
                }

                $getquerydetails = $this->check_point_task->get_c_p_data_to_fill($filter_data);
            }
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

            $emp_filter_data = [
                'status' => $type,
                'emp_id' => $emp_id,
            ];
            $get_f_f_emp_details = $this->check_point_task->get_c_p_emp_data($emp_filter_data);
            return Datatables::of($get_f_f_emp_details)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($type) {

                    if ($type == "Fresh") {
                        $btn_txt = "Save";
                    } else {
                        $btn_txt = "Update";
                    }
                    $action = '<button class="btn btn-outline-success save" title="' . $btn_txt . ' Check Points"  onclick="save_ratings(' . "'" . $row->emp_id . "'" . ');" id="save_f_and_f_doc" style="margin-top:-30px;" type="button"><span><i class="fa fa-save"></i></span>&nbsp;' . $btn_txt . '</button>';
                    return $action;
                })
                ->addColumn('emp_id', function ($row) {
                    $emp_id = $row->emp_id;
                    return $emp_id;
                })
                ->addColumn('questions', function ($row) use ($getquerydetails, $type) {
                    $tbl_event = "";
                    if ($type == "Completed") {
                        $tbl_event = 'pointer-events: none;';
                        $color = "background-color:#BEBEBE";
                        $place = "---";
                    } else {
                        $color = "background-color:white";
                        $place = "Enter Remarks";
                    }

                    foreach ($getquerydetails as $ques) {
                        $questions[] = $ques->questions;
                        $question_id[] = $ques->question_id;
                    }

                    $questions1 = "";
                    $questions1 .= '<table class="table table-bordered" style="' . $tbl_event . '">';

                    $option_val = array("Yes", "No", "NA");

                    $remark_dy = "";
                    for ($i = 0; $i < count($questions); $i++) {

                        $get_val = [
                            "emp_id" => $row->emp_id,
                            "question_id" => $question_id[$i],
                        ];

                        $select = "";
                        $select .= '<div>';
                        $select .= '<select style="' . $color . '" class="rating_' . $row->emp_id . '  " name="rating[]">';
                        $select .= '<option value="">Select</option>';

                        $getquerydetails = $this->check_point_task->check_q_availablity($get_val);

                        if (isset($getquerydetails[0])) {
                            for ($j = 0; $j < count($option_val); $j++) {
                                if ($getquerydetails[0]->rating == $option_val[$j]) {
                                    $select .= '<option value="' . $option_val[$j] . '" selected>' . $option_val[$j] . '</option>';
                                } else {
                                    $select .= '<option value="' . $option_val[$j] . '">' . $option_val[$j] . '</option>';
                                }
                            }
                            $remark_dy = '<Input type="text" name="remarks[]" placeholder="' . $place . '" class="form-control remarks_' . $row->emp_id . '" value="' . $getquerydetails[0]->remarks . '"/>';
                        } else {
                            for ($j = 0; $j < count($option_val); $j++) {
                                $select .= '<option value="' . $option_val[$j] . '">' . $option_val[$j] . '</option>';
                            }
                            $remark_dy = '<Input type="text" name="remarks[]" placeholder="' . $place . '" class="form-control remarks_' . $row->emp_id . '" value=""/>';
                        }

                        $select .= '</select>';
                        $select .= '</div>';

                        $Remark = "";
                        $Remark .= '<div>';
                        $Remark .= $remark_dy;
                        $Remark .= '</div>';

                        $questions1 .= '<tr style="width:20%"><span><td>' . $questions[$i] . '</td><td class="" id="">' . $select . '</td><td class="" id="">' . $Remark . '</td></span></tr>';
                        $questions1 .= '<input  type="hidden" Class="Question_id_' . $row->emp_id . '" name="Question_id[]"  value="' . $question_id[$i] . '">';

                    }
                    $questions1 .= '</table><br>';
                    
                    return $questions1;
                })

                ->rawColumns(['action', 'emp_id', 'questions', 'Remark'])
                ->make(true);
        }
        return view('F_and_F_document/f_and_f_document');
        // return view('Admin/view_f_f_tracker_landing');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_f_and_f_document(Request $request)
    {
        for ($i = 0; $i < count($request->Question_id); $i++) {

            $inputfield = [
                'emp_id' => $request->emp_id,
                'question_id' => $request->Question_id[$i],
                'rating' => $request->rating[$i],
                'remarks' => $request->remarks[$i],
                'created_by' => session()->get('emp_id'),
            ];

            // check q already exist or not
            $get = $this->check_point_task->check_q_availablity($inputfield);

            if (isset($get[0])) {
                // have row - update
                $updated = $this->check_point_task->update_q_rating($inputfield);
            } else {
                // dont have - insert
                $updated = $this->check_point_task->inset_q_rating($inputfield);
            }

            // get all ques based role
            $all_c_p = $this->check_point_task->get_all_check_point_b_role();
            $this->update_pending_status($request->emp_id);
            foreach ($all_c_p as $row) {
                $q_id[] = $row->question_id;
            }

            // get all completed ques based role
            $inputfield = [
                'emp_id' => $request->emp_id,
                'created_by' => session()->get('emp_id'),
            ];
            // dd($inputfield);

            $all_com_ques = $this->check_point_task->get_all_completed_check_point($inputfield);
            // $com_q_id = [];
            foreach ($all_com_ques as $row) {
                $com_q_id[] = $row->question_id;
            }

            // $com_q_id=["Q0001","Q0002","Q0003","Q0004","Q0005","Q0006","Q0007","Q0008","Q0009","Q0010","Q0011","Q0012","Q0013","Q0014","Q0015","Q0016","Q0017","Q0018","Q0019","Q0020","Q0021","Q0022","Q0023","Q0024","Q0025","Q0026","Q0027","Q0028"];

            $check_diff_array = array_diff($q_id, $com_q_id);
            $check_diff = array();
            foreach ($check_diff_array as $a) {
                $check_diff[] = $a;
            }

            if (!isset($check_diff[0])) {
                // echo "completed";
                $inputfield = [
                    'emp_id' => $request->emp_id,
                    'status' => "Completed",
                ];
                $update_status = $this->check_point_task->update_f_f_status($inputfield);
            } else {
                $inputfield = [
                    'emp_id' => $request->emp_id,
                    'status' => "InProgress",
                ];
                $update_status = $this->check_point_task->update_f_f_status($inputfield);
            }
        }

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

        $emp_id = $request->emp_id;
        $result2 = $this->f_f_tracker_task->get_status($emp_id, $check_col);
        if (($result2[0]->cl_c_p == "Comepleted") && ($check_col == "cl_c_p")) {
            $details = [
                'emp_id' => $emp_id,
            ];
            $u_type = "IT-INFRA";
            $toemail = $this->f_f_tracker_task->get_email($u_type);
            if (isset($toemail)) {

                $body_content1 = "Dear " . $toemail->emp_name . ',';
                $body_content2 = "Form 16 for the financial year 21-22 is available in the Alumni Portal.";
                $body_content3 = 'Pls download your form 16 from the Portal.';
                $body_content4 = "https://citpl_alumni.cavinkare.in/index.php/login";
                $body_content5 = "Cheers";
                $body_content6 = "Team HR";

                $details = [
                    'subject' => 'CITPL',
                    'title' => 'Check Points in Alumni - CITPL',
                    'body_content1' => $body_content1,
                    'body_content2' => $body_content2,
                    'body_content3' => $body_content3,
                    'body_content4' => $body_content4,
                    'body_content5' => $body_content5,
                    'body_content6' => $body_content6,
                ];
                // Mail::to($toemail->email)->send(new NotifyMail($details));
            }
        }
        return response()->json(['response' => 'Success', 'res2' => $result2, 'col' => $check_col]);
    }
    public function sendNotifyMail($team,$tomail,$toname,$emp_id){
        $raiseDeti = [
            'body_content1' => 'Hello '.$toname.'! ',
            'body_content2' => 'Hi, the '.$team.' team has raised a new F&F request '.$emp_id.' that requires your attention.',
            'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
            'body_content5' => 'Cheers',
            'body_content6' => 'Team '.$team,
        ];
        \Mail::to($tomail)->send(new \App\Mail\NewCaseEmailNotification($raiseDeti));
    }

    public function update_pending_status($emp_id){
        if(session('user_type') == "Claims"){
            $querytbl = new emp_profile_tbl();
            $querytbl = $querytbl->where('emp_id', '=', $emp_id);
            $querytbl = $querytbl->where('f_f_c_s_g', '=', '3.5');
            $querytbl = $querytbl->update(['f_f_c_s_g' => '4']);
            // dd($querytbl);
                if($querytbl){
    
                    $history = [
                        'emp_id' => $emp_id,
                        'from_sg' => "3",
                        'to_sg' => "4",
                        'created_by' => session('emp_id'),
                        'sender_to' => "PRHR001",
                    ];
                    $result_history = $this->f_f_tracker_task->save_history($history);
                    $date_credential = [
                        'emp_id' => $emp_id,
                        's_g' => "3.5",
                        'created_by' => session('emp_id'),
                    ];
                    $res2 = $this->f_f_tracker_task->get_reverts($emp_id);
                    if (isset($res2[0])) {
                        $flow = $res2[0]->flow + 1;
                    } else {
                        $flow = 1;
                    }
                    $cred = [
                        'emp_id' => $emp_id,
                        's_g_id' => "3",
                        'verification' => "",
                        'remark' => "",
                        'created_by' => session('emp_id'),
                        'flow' => $flow,
                        'from_sg' => "3",
                        'to_sg' => "4",
                        'alert_to' => "PRFN001",
                        'v_status' => "0",
                        'sts' => "Active",
                        'd_sts' => "Deactive",
                    ];
                    $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
                    $result4 = $this->f_f_tracker_task->notify_deactive($cred);
                    $result3 = $this->f_f_tracker_task->save_notification($cred);
                    $toname = 'Payroll Finance';
                    $team = 'QC';
                    $tomail = 'payroll02@cavinkare.com';//payroll02@cavinkare.com // payroll finance
                    // $this->sendNotifyMail($team,$tomail,$toname,$emp_id);
                }
            }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
