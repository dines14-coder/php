<?php

namespace App\Http\Controllers\AdminController;

// use Barryvdh\DomPDF\Facade as PDF;
use \PDF;
use App;
use App\Http\Controllers\Controller;
use App\Models\amb_document_tbl;
use App\Models\emp_profile_tbl;
use App\Models\F_F_tracker_alumni_data;
use App\Models\F_F_tracker_files;
use App\Models\HoldSalaryHistory;
use App\Models\hold_salary;
use App\Models\Notifications;
use App\Models\query_document_tbl;
use App\Models\revert_table;
use App\Models\TrackerDataHistory;
use App\Models\TrackerFilesHistory;
use App\Repositories\IDocRepository;
use App\Repositories\IEmpRepository;
use App\Repositories\IF_F_tracker_Repository;
use App\Repositories\Check_point_Repository;
use App\Models\f_f_check_point;
use Carbon\Carbon;
use DataTables;
use DateTime;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\Models\ReopenHistory;


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class F_F_Tracker_Controller extends Controller
{
    public function __construct(IEmpRepository $emp_task, IDocRepository $doc_task, IF_F_tracker_Repository $f_f_tracker_task, Check_point_Repository $cp_repository)
    {
        $this->middleware('adminLog');
        $this->f_f_tracker_task = $f_f_tracker_task;
        $this->doc_task = $doc_task;
        $this->emp_task = $emp_task;
        $this->cp_repository = $cp_repository;

    }
    public function f_f_tracker_landing()
    {
        $data['recoveries'] = $this->f_f_tracker_task->get_all_data('recovery_tables');
        return view('Admin.view_f_f_tracker_landing', $data);
    }

    public function get_f_f_tracker_data(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->type;
            if (session('user_type') == "F_F_HR") {
                
                if ($type == 'Pending') {
                    $filterData = ['f_f_c_s_g' => [1], 'type' => 'fresh'];
                } elseif ($type == 'InProgress') {
                    $filterData = ['f_f_c_s_g' => [1.5, 2, 3, 4, 5, 6,3.5], 'type' => 'fresh'];
                } else {
                    $filterData = ['f_f_c_s_g' => 7, 'type' => 'equal'];
                }
            } elseif (session('user_type') == "Payroll_HR") {
                if ($type == 'Pending') {
                    $filterData = ['f_f_c_s_g' => [2], 'type' => 'fresh'];
                } else {
                    $filterData = ['f_f_c_s_g' => 3, 'type' => 'greater'];
                }
            } elseif (session('user_type') == "Payroll_QC") {
                if ($type == 'Pending') {
                    $filterData = ['f_f_c_s_g' => [3], 'type' => 'fresh'];
                } else {
                    $filterData = ['f_f_c_s_g' => 3.5, 'type' => 'greater'];
                }
            } elseif (session('user_type') == "Payroll_Finance") {
                if ($type == 'Pending') {
                    $filterData = ['f_f_c_s_g' => [4], 'type' => 'fresh'];
                } elseif ($type == 'InProgress') {
                    $filterData = ['f_f_c_s_g' => [5], 'type' => 'fresh'];
                } else {
                    $filterData = ['f_f_c_s_g' => 6, 'type' => 'greater'];
                }
            }
            $getQueryDetails = $this->f_f_tracker_task->getEmployeeList($filterData);

            // echo "<pre>";print_r($getQueryDetails);die;
            return Datatables::of($getQueryDetails)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($type) {
                    $reopen_status = 0;
                    if (session('user_type') == "F_F_HR") {
                        if ($type == 'Pending') {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "";
                        } else if ($type == 'InProgress' && $row->f_f_c_s_g == "1.5") {
                            $current_p_s_g = 2;
                            $btn_style = "";
                        } else if ($type == 'InProgress' && $row->f_f_c_s_g == "6") {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "";
                        } else {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "cursor: not-allowed;    pointer-events: none;background-color: #e05956;";
                        }
                    } elseif (session('user_type') == "Payroll_HR") {
                        if ($type == 'Pending' && $row->f_f_c_s_g == "2") {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "";
                        } else {
                           
                            if($row->f_f_c_s_g == "7"){
                                $reopencount = F_F_tracker_alumni_data::where('emp_id', $row->emp_id)->first();
                                $current_p_s_g = 3;
                                $btn_style = "";
                                // if($reopencount->re_open_ct == null){
                                //     $reopen_status = 1;
                                // }else{
                                //     $reopen_status = $reopencount->re_open_ct + 1;
                                // }
                                if ($reopencount) {
                                    $reopen_status = $reopencount->re_open_ct !== null ? $reopencount->re_open_ct + 1 : 1;
                                } else {
                                    $reopen_status = 1;
                                }
                            }else{
                                $current_p_s_g = $row->f_f_c_s_g + 1;
                                $btn_style = "cursor: not-allowed;    pointer-events: none;background-color: #e05956;";
                            }
                           
 

                        }
                    } elseif (session('user_type') == "Payroll_Finance") {
                        if ($type == 'Pending' && $row->f_f_c_s_g == "4") {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "";
                        } elseif ($type == 'InProgress' && $row->f_f_c_s_g == "5") {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "";
                        } else {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "cursor: not-allowed;    pointer-events: none;background-color: #e05956;";
                            // if($row->f_f_c_s_g == "7"){

                            //     $reopencount = F_F_tracker_alumni_data::where('emp_id', $row->emp_id)->first();
                            //     $current_p_s_g = 5;
                            //     $btn_style = "";
                            //     if($reopencount->re_open_ct == null){
                            //         $reopen_status = 1;
                            //     }else{
                            //         $reopen_status = $reopencount->re_open_ct + 1;
                            //     }
                            // }else{
                            //     $current_p_s_g = $row->f_f_c_s_g + 1;
                            //     $btn_style = "cursor: not-allowed;    pointer-events: none;background-color: #e05956;";
                            // }
                        }
                    } else {
                        if ($type == 'Pending' && $row->f_f_c_s_g == "3") {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "";
                        } else {
                            $current_p_s_g = $row->f_f_c_s_g + 1;
                            $btn_style = "cursor: not-allowed;    pointer-events: none;background-color: #e05956;";
                        }
                    }
                    $creds = [
                        'emp_id' => $row->emp_id,
                    ];
                    $qc_sts_count = $this->f_f_tracker_task->get_qc_status_count($creds);
                    $ques_tot_count = $this->f_f_tracker_task->get_count();
                    $carbonDate = Carbon::createFromFormat('Y-m-d', $row->last_working_date);
                    $seperationDate = $carbonDate->addDays(1);
                    $seperationDate = $seperationDate->format('Y-m-d');
                    if($reopen_status){
                        $view = "not_view";
                        $action = '<a href="#" style="' . $btn_style . '" class="btn btn-icon btn-success ac_btn" title="Stage Gate Form" onclick="f_f_action_pop(' . "'" . $row->emp_id . "'" . ',' . "'" . $row->emp_name . "'" . ',' . "'" . $current_p_s_g . "'" . ',' . "'" . $row->last_working_date . "'" . ',' . "'" . $seperationDate . "'" . ',' . "'" . $view . "'" . '), reopen('."'".$reopen_status."'".');"><i class="fas fa-pen"></i>&nbsp;</a>';    
                    }else{
                        $view = "not_view";
                        $action = '<a href="#" style="' . $btn_style . '" class="btn btn-icon btn-success ac_btn" title="Stage Gate Form" onclick="f_f_action_pop(' . "'" . $row->emp_id . "'" . ',' . "'" . $row->emp_name . "'" . ',' . "'" . $current_p_s_g . "'" . ',' . "'" . $row->last_working_date . "'" . ',' . "'" . $seperationDate . "'" . ',' . "'" . $view . "'" . ');"><i class="fas fa-pen"></i>&nbsp;</a>';
                       
                    }
                     if (session('user_type') == "Payroll_Finance") {
                        $action .= '<a  href="#"  class="btn btn-primary ac_btn " data-controls-modal="f_and_f_document_popup" data-backdrop="static" data-keyboard="false" title="Check Points PDF"  onclick="f_and_f_document_popup(' . "'" . $row->emp_id . "'" . ');"><i class="fas fa-flag"></i>&nbsp;</a>';
                    }
                    if ((session('user_type') == "Payroll_QC") && ($type == 'Completed')) {
                        $view = "only_view";
                        $action .= '<a  href="#"  class="btn btn-info ac_btn " data-backdrop="static" data-keyboard="false" title="View The F & F Document"  onclick="f_f_action_pop(' . "'" . $row->emp_id . "'" . ',' . "'" . $row->emp_name . "'" . ',' . "'" . $current_p_s_g . "'" . ',' . "'" . $row->last_working_date . "'" . ',' . "'" . $seperationDate . "'" . ',' . "'" . $view . "'" . ');"><i class="fas fa-eye"></i>&nbsp;</a>';
                    }
                    if((session('user_type')== "F_F_HR") && $row->f_f_c_s_g >6){
                        // $s_g_id = 1;
                        // $get_tracker_files = $this->f_f_tracker_task->get_data_with_where_onlyfiles('f__f_tracker_files', "emp_id", $row->emp_id, "s_g_id", $s_g_id);
                        // $action .= '<a href="'.asset('/F_F_tracker/'.$row->emp_id.'/no_dues_file/'.$get_tracker_files->filename).'" class="btn btn-info ac_btn" data-backdrop="static" data-keyboard="false" title="Download No Due Document" download><i class="fas fa-download"></i>&nbsp;</a>';
                        $s_g_id = 1;
                        $get_tracker_files = $this->f_f_tracker_task->get_data_with_where_onlyfiles('f__f_tracker_files', "emp_id", $row->emp_id, "s_g_id", $s_g_id);
                        
                        if ($get_tracker_files && !empty($get_tracker_files->filename)) {
                            $action .= '<a href="'.asset('/F_F_tracker/'.$row->emp_id.'/no_dues_file/'.$get_tracker_files->filename).'" 
                                        class="btn btn-info ac_btn" data-backdrop="static" data-keyboard="false" 
                                        title="Download No Due Document" download>
                                        <i class="fas fa-download"></i>&nbsp;</a>';
                        }

                    }
                    return $action;
                })
                ->addColumn('c_stage_gate', function ($row) {
                    if (!$row->f_f_c_s_g == "" || !$row->f_f_c_s_g == null) {
                        $f_f_c_s_g = '';
                        if ($row->f_f_c_s_g == "1.5") {
                            $f_f_c_s_g .= '<div class="badge badge-primary doc_name csg">1</div><br>';
                        } elseif ($row->f_f_c_s_g == "3.5") {
                            $f_f_c_s_g .= '<div class="badge badge-primary doc_name csg">Claims Pending</div><br>';
                        }else {
                            $f_f_c_s_g .= '<div class="badge badge-primary doc_name csg">' . $row->f_f_c_s_g . '</div><br>';
                        }
                        return $f_f_c_s_g;
                    } else {
                        return "---";
                    }
                })
                ->addColumn('type_of_leaving', function ($row) {
                    if (!$row->type_of_leaving == "" || !$row->type_of_leaving == null) {
                        $type_of_leaving = '';
                        if ($row->type_of_leaving == "Abscond" || $row->type_of_leaving == "Terminated") {
                            $type_of_leaving .= '<div class="badge badge-danger doc_name">' . $row->type_of_leaving . '</div><br>';
                        } elseif ($row->type_of_leaving == "Transferred") {
                            $type_of_leaving .= '<div class="badge badge-primary doc_name">' . $row->type_of_leaving . '</div><br>';
                        } else {
                            $type_of_leaving .= '<div class="badge badge-success doc_name">' . $row->type_of_leaving . '</div><br>';
                        }
                        return $type_of_leaving;
                    } else {
                        return "---";
                    }
                })
                ->addColumn('last_working_date', function ($row) {
                    return date('d-m-Y', strtotime($row->last_working_date));
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->rawColumns(['action', 'c_stage_gate', 'type_of_leaving', 'created_at', 'last_working_date'])
                ->make(true);
        }
        return view(' Admin.view_f_f_tracker_landing');
    }

    public function save_f_f_tracker_inp(Request $request)
    {
        $emp_id = $request->emp_id;
        // dd($request->re_opened_by);

        
        if ($request->process_s_g == "2") {
            $validator = Validator::make($request->all(), [
                'supervisor_clearance' => 'required',
                'commercial_admin_clearance' => 'required',
                'finanace_clearance' => 'required',
                'it_clearance' => 'required',
                'no_dues_file' => 'required',
            ]);
            if ($validator->passes()) {
                $credential = ['emp_id' => $request->emp_id];
                $result = $this->f_f_tracker_task->check_f_f_inp($credential);
                $rec1 = $request->recovery;
                $value1 = $request->value;
                $remark1 = $request->remark;
                F_F_tracker_files::where('emp_id', $request->emp_id)->whereIn('doc_type', ["f_f_computation", "others"])->delete();
                if (isset($rec1[0])) {
                    if (in_array("", $request->value)) {
                        return response()->json(['res' => "rec_er"]);
                    }
                    for ($q = 0; $q < count($rec1); $q++) {
                        $get_rec_data = $this->f_f_tracker_task->get_data_with_where2('recovery_datas', "emp_id", $credential['emp_id'], "r_id", $rec1[$q]);
                        if (isset($get_rec_data[0])) {
                            $rec_data = [
                                'emp_id' => $credential['emp_id'],
                                'r_id' => $rec1[$q],
                                'values' => $value1[$q],
                                'remark' => $remark1[$q],
                            ];
                            $rec_update = $this->f_f_tracker_task->update_recovery($rec_data);
                        } else {
                            $rec_data = [
                                'emp_id' => $credential['emp_id'],
                                'r_id' => $rec1[$q],
                                'values' => $value1[$q],
                                'remark' => $remark1[$q],
                            ];
                            $rec_save = $this->f_f_tracker_task->save_recovery($rec_data);
                        }
                    }
                }
                $credential = [
                    'emp_id' => $request->emp_id,
                    'supervisor_clearance' => $request->supervisor_clearance,
                    'c_admin_clearance' => $request->commercial_admin_clearance,
                    'finanace_clearance' => $request->finanace_clearance,
                    'it_clearance' => $request->it_clearance,
                    'grade_set' => $request->grade_set,
                    'grade' => $request->grade,
                    'department' => $request->department,
                    'work_location' => $request->work_location,
                    'supervisor_name' => $request->supervisor_name,
                    'reviewer_name' => $request->reviewer_name,
                    'headquarters' => $request->headquarters,
                    'hrbp_name' => $request->hrbp_name,
                    'last_working_date' => $request->last_working_date,
                    'seperation_date' => $request->seperation_date,
                    'date_of_joining' => $request->date_of_joining,
                    'date_of_resignation' => $request->date_of_resignation,
                    'created_by' => session('emp_id'),
                ];
                if (isset($result[0])) {
                    // update
                    $get_com_query_cnt = $this->f_f_tracker_task->update_form_f_f_data($credential);
                } else {
                    // insert
                    $get_com_query_cnt = $this->f_f_tracker_task->insert_form_f_f_data($credential);
                }
                if ($request->submit_type == "Submit" && $request->process_s_g == "2") {
                    // update sg in emp tbl
                    $upd_credential = [
                        'emp_id' => $request->emp_id,
                        'f_f_c_s_g' => "2",
                    ];
                    // insert date of completed
                    $date_credential = [
                        'emp_id' => $request->emp_id,
                        's_g' => "1",
                        'created_by' => session('emp_id'),
                    ];
                    $result1 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
                    // auto fill
                    $date_credential = [
                        'emp_id' => $request->emp_id,
                        's_g' => "1",
                        'created_by' => session('emp_id'),
                    ];
                    $cred = [
                        'emp_id' => $request->emp_id,
                        'from_sg' => "1",
                        'to_sg' => "2",
                        'alert_to' => "AKASH001", // Changed to AKASH001 for akashraj@hepl.com
                        'v_status' => "0",
                        'sts' => "Active",
                    ];
                    $history = [
                        'emp_id' => $request->emp_id,
                        'from_sg' => "1",
                        'to_sg' => "2",
                        'created_by' => session('emp_id'),
                        'sender_to' => "AKASH001", // Changed from PRHR001 to AKASH001 for akashraj@hepl.com
                    ];
                    $result_history = $this->f_f_tracker_task->save_history($history);
                    $result3 = $this->f_f_tracker_task->save_notification($cred);
                    $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
                } else if ($request->submit_type == "Save" && $request->process_s_g == "2") {
                    $upd_credential = [
                        'emp_id' => $request->emp_id,
                        'f_f_c_s_g' => "1.5",
                    ];
                }
                $result = $this->emp_task->update_p_one_alumni_f_f_s_g($upd_credential);

                $files1 = $request->file('no_dues_file');
                $doc_row1 = [];
                if (is_array($files1)) {
                    if ($request->hasfile('no_dues_file')) {
                        $p_s_c = 0;
                        foreach ($files1 as $file) {
                            $ah_name = 'no_dues' . time() . '_' . $p_s_c . '.' . $file->extension();
                            $file->move(public_path() . '/F_F_tracker/' . $emp_id . '/no_dues_file', $ah_name);
                            $doc_row1[] = [
                                'doc_type' => 'no_dues',
                                'doc_name' => $ah_name,
                            ];
                            $p_s_c++;
                        }
                    }
                }
                $res2 = $this->f_f_tracker_task->get_reverts($emp_id);
                if (isset($res2[0])) {
                    $flow = $res2[0]->flow + 1;
                } else {
                    $flow = 1;
                }

                $count = 0;
                while ($count < count($doc_row1)) {
                    $credentials = [
                        'emp_id' => $emp_id,
                        's_g_id' => "1",
                        'flow' => $flow,
                        'doc_type' => $doc_row1[$count]['doc_type'],
                        'filename' => $doc_row1[$count]['doc_name'],
                        'remark' => $request->no_dues_remark,
                        'created_by' => session('emp_id'),
                    ];
                    // save query document row
                    $saved_query_ticket_id = $this->f_f_tracker_task->save_f_f_file_row($credentials);
                    $count++;
                }

                $toname = 'HRSS';
                $team = 'HRSS';
                $tomail = 'hrss@cavininfotech.com'; // Changed from hrss@hepl.com to akashraj@hepl.com
                $this->sendNotifyMail($team,$tomail,$toname,$request->emp_id);

                return response()->json(['response' => "success"]);
            } else {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }
        } elseif ($request->process_s_g == "3") {

            $validator = Validator::make($request->all(), [
                'basic' => 'required|numeric',
                'exit_register_run_file' => 'required',
                // 'manual_computation_file' => 'required',
                // 'manual_computation_file' => 'required',
                'leave_balance_cl' => 'required|numeric',
                'leave_balance_sl' => 'required|numeric',
                'leave_balance_pl' => 'required|numeric',
                'is_probation_completed' => 'required',
            ]);
            if ($validator->passes()) {
                if($request->reopenstatus != ""){
                    
                    DB::BeginTransaction();
                    try {

                    if (session('user_type') == "Payroll_Finance") {
                        $cratedBy = ['PRHR001', 'PRQC001'];
                    } else {
                        $cratedBy = ['PRHR001'];
                    }
                    $getTrackerFiles = F_F_tracker_files::where('emp_id', $request->emp_id)
                        ->whereIn('created_by', $cratedBy)
                        ->each(function ($getTrackerFiles) {
                            $newgetTrackerFiles = $getTrackerFiles->replicate();
                            $newgetTrackerFiles->setTable('tracker_files_histories');
                            $newgetTrackerFiles->save();
                            $getTrackerFiles->delete();
                        });
                    // Get Hold Salary Data and Add to History
                    $getHoldSalaryData = hold_salary::where('emp_id', $request->emp_id)
                        ->each(function ($getHoldSalaryData) {
                            $newgetHoldSalaryData = $getHoldSalaryData->replicate();
                            $newgetHoldSalaryData->setTable('hold_salary_histories_reopens');
                            $newgetHoldSalaryData->save();
                        });
                        // dd($newgetHoldSalaryData);
                    // Get Alumni Data and Add to History
                    // $getAlumniData = F_F_tracker_alumni_data::where('emp_id', $request->emp_id)
                    //     ->each(function ($getAlumniData) {
                    //         $newgetAlumniData = $getAlumniData->replicate();
                    //         $newgetAlumniData->setTable('reopen_data_histories');
                    //         $newgetAlumniData->save();
                    //     });
                    $getAlumniData = F_F_tracker_alumni_data::where('emp_id', $request->emp_id)->first();
 
                        if ($getAlumniData) {
                            $existingRecord = DB::table('reopen_data_histories')->where('emp_id', $request->emp_id)->first();
                           
                            if (!$existingRecord) {
                                $newgetAlumniData = $getAlumniData->replicate();
                                $newgetAlumniData->setTable('reopen_data_histories');
                                $newgetAlumniData->save();
                            }
                        }
 
                        $date_credential = [
                            'emp_id' => $request->emp_id,
                            's_g' => $request->process_s_g,
                            'created_by' => session('emp_id'),
                        ];
                        $querytbl = new ReopenHistory();
                        $querytbl->emp_id = $request->emp_id;
                        $querytbl->s_g_id = $request->process_s_g;
                        $querytbl->reopened_by = session('emp_id');
                        $querytbl->save();
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response()->json(['status' => 0, 'error' => $e]);
                    }

                }
                // echo "fixed_stipend<pre>";print_r($request->fixed_stipend);
                // die;
                $credential = [
                    'emp_id' => $request->emp_id,
                ];
                $result = $this->f_f_tracker_task->check_f_f_inp($credential);

                $credential = [
                    'emp_id' => $request->emp_id,
                    'basic' => $request->basic,
                    'da' => $request->da,
                    'other_allowance' => $request->other_allowance,
                    'hra' => $request->hra,
                    'addl_hra' => $request->addl_hra,
                    'conveyance' => $request->conveyance,
                    'lta' => $request->lta,
                    'medical' => $request->medical,
                    'spl_allowance' => $request->spl_allowance,
                    'nps' => $request->nps,
                    'super_annuation' => $request->super_annuation,
                    'fixed_stipend' => $request->fixed_stipend,
                    'sales_incentive' => $request->sales_incentive,
                    'fva' => $request->fva,
                    'gross' => $request->gross,
                    'leave_balance_cl' => $request->leave_balance_cl,
                    'leave_balance_pl' => $request->leave_balance_pl,
                    'leave_balance_sl' => $request->leave_balance_sl,
                    'is_probation_completed' => $request->is_probation_completed,
                    're_open_ct' => $request->reopenstatus,
                    're_opened_by' => $request->re_opened_by,
                ];
                // dd($credential);
                $get_com_query_cnt = $this->f_f_tracker_task->update_form_f_f_data_set_2($credential);
                if ($request->submit_type == "Submit" && $request->process_s_g == "3") {
                    // update sg in emp tbl
                    $upd_credential = [
                        'emp_id' => $request->emp_id,
                        'f_f_c_s_g' => "3",
                    ];
                    $date_credential = [
                        'emp_id' => $request->emp_id,
                        's_g' => "2",
                        'created_by' => session('emp_id'),
                    ];
                    $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
                    // Delete Previous Hold Salary
                    hold_salary::where('emp_id', $request->emp_id)->delete();
                    // Delete Previous
                    $s = 0;
                    if (isset($request->month_year_[0])) {
                        if (in_array("", $request->n_amount)) {
                            return response()->json(['res' => "hold_empty"]);
                        }
                        foreach ($request->n_amount as $amt) {
                            $cred = [
                                'emp_id' => $request->emp_id,
                                'month_year' => $request->month_year_[$s],
                                'amount' => $amt,
                            ];
                            $result3 = $this->f_f_tracker_task->save_hold_salry($cred);
                            $s++;
                        }
                    }
                }
                $result = $this->emp_task->update_p_one_alumni_f_f_s_g($upd_credential);
                $files1 = $request->file('manual_computation_file');
                $doc_row1 = [];
                if (is_array($files1)) {
                    if ($request->hasfile('manual_computation_file')) {
                        $p_s_c1 = 0;
                        foreach ($files1 as $file1) {
                            $file_type = $file1->extension();
                            $ah_name1 = 'manual_computation' . time() . '_' . $p_s_c1 . '.' . $file1->extension();
                            $file1->move(public_path() . '/F_F_tracker/' . $emp_id . '/manual_computation_file', $ah_name1);
                            $doc_row1[] = [
                                'doc_type' => 'manual_computation',
                                'doc_name' => $ah_name1,
                            ];


                            // Get the uploaded file       |mimes:xlsx,csv,xls
                            $excelFile = public_path() . '/F_F_tracker/' . $emp_id . '/manual_computation_file/'.$ah_name1;

                            if ($file_type == 'xlsx' ||$file_type == 'xls' || $file_type == 'csv') {

                            // $spreadsheet = IOFactory::load($excelFile);
                            $reader = IOFactory::createReaderForFile($excelFile);
                            $spreadsheet = $reader->load($excelFile);

                            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                                $sheet = $spreadsheet->getSheetByName($sheetName);
                                if ($sheetName === $emp_id.'PDF') {
                                    // if ($sheetName === 'ExportPDF') {
                                    $newSheet = clone $sheet;
                                    $newSheet->setTitle('Export2PDF');
                                    $spreadsheet->addSheet($newSheet);
                                    $highestRow = $newSheet->getHighestRow();
                                    $highestColumn = $newSheet->getHighestColumn();
                                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                                    for ($row = 1; $row <= $highestRow; ++$row) {
                                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                                            $cell = $newSheet->getCellByColumnAndRow($col, $row);
                                            $cellValue = $cell->getValue();
                                            // Check if the cell has a formula
                                            if (is_string($cellValue) && $cellValue[0] === '=') {
                                                $calculatedValue = $cell->getOldCalculatedValue();
                                                $cell->setValue($calculatedValue);
                                            }else{
                                                $cell->setValue($cellValue);
                                            }
                                        }
                                    }
                                }
                                $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($sheet));
                            }
                        }

                            $sheet = $spreadsheet->getSheetByName('Export2PDF');
                            if ($sheet !== null) {

                                $writer = new Html($spreadsheet);
                                ob_start();
                                $writer->save('php://output');
                                $html = ob_get_clean();
                                $pdf = PDF::loadHTML($html);
                                $pdf->setOptions([
                                    'isHtml5ParserEnabled' => true,
                                    'isPhpEnabled' => true,
                                    'isFontSubsettingEnabled' => true,
                                ]);
                                
                                // Set default font
                                $pdf->setOption('defaultFont', 'DejaVu Sans');
    
                                $directoryPath = public_path() . '/F_F_tracker/' . $emp_id . '/manual_computation_file/pdf/';
                                if (!is_dir($directoryPath)) {
                                    mkdir($directoryPath, 0777, true);  // Create the directory and its parents
                                }
                            
                                // Save the new sheet to the PDF file
                                $pdf->save(public_path() . '/F_F_tracker/' . $emp_id . '/manual_computation_file/pdf/'.$emp_id.'.pdf');
                                
                            }

                            $p_s_c1++;
                        }
                    }
                }

                $files = $request->file('exit_register_run_file');
                if (is_array($files)) {
                    if ($request->hasfile('exit_register_run_file')) {
                        $p_s_c = 0;
                        foreach ($files as $file) {
                            $ah_name = 'exit_register_run_file' . time() . '_' . $p_s_c . '.' . $file->extension();
                            $file->move(public_path() . '/F_F_tracker/' . $emp_id . '/exit_register_run_file', $ah_name);
                            $doc_row[] = [
                                'doc_type' => 'exit_register_run',
                                'doc_name' => $ah_name,
                            ];
                            $p_s_c++;
                        }
                    }
                }
                $res2 = $this->f_f_tracker_task->get_reverts($emp_id);
                if (isset($res2[0])) {
                    $flow = $res2[0]->flow + 1;
                } else {
                    $flow = 1;
                }
                $count1 = 0;
                while ($count1 < count($doc_row1)) {
                    $credentials1 = [
                        'emp_id' => $emp_id,
                        's_g_id' => "2",
                        'flow' => $flow,
                        'doc_type' => $doc_row1[$count1]['doc_type'],
                        'filename' => $doc_row1[$count1]['doc_name'],
                        'remark' => $request->manual_computation_remark,
                        'created_by' => session('emp_id'),
                    ];
                    // save query document row
                    $saved_query_ticket_id = $this->f_f_tracker_task->save_f_f_file_row($credentials1);
                    $count1++;
                }
                $count = 0;
                while ($count < count($doc_row)) {
                    $credentials = [
                        'emp_id' => $emp_id,
                        'flow' => $flow,
                        's_g_id' => "2",
                        'doc_type' => $doc_row[$count]['doc_type'],
                        'filename' => $doc_row[$count]['doc_name'],
                        'remark' => $request->exit_register_run_remark,
                        'created_by' => session('emp_id'),
                    ];
                    // save query document row
                    $saved_query_ticket_id = $this->f_f_tracker_task->save_f_f_file_row($credentials);
                    $count++;
                }
                $cred = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "2",
                    'to_sg' => "3",
                    'alert_to' => "PRQC001", // This is for payrollqc@hepl.com
                    'v_status' => "0",
                    'sts' => "Active",
                    'd_sts' => "Deactive",
                ];
                $history = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "2",
                    'to_sg' => "3",
                    'created_by' => session('emp_id'),
                    'sender_to' => "PRQC001",
                ];
                $result_history = $this->f_f_tracker_task->save_history($history);
                $result4 = $this->f_f_tracker_task->notify_deactive($cred);
                $result3 = $this->f_f_tracker_task->save_notification($cred);

                $toname = 'Quality Control Team';
                $team = 'Payroll HR';
                $tomail = 'payrollqc@hepl.com';//payrollqc@cavinkare.com
                $this->sendNotifyMail($team,$tomail,$toname,$request->emp_id);

                return response()->json(['response' => "success"]);
            } else {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }
        } elseif ($request->process_s_g == "4") {
            // $validator = Validator::make($request->all(),[
            //     'quality_check_file' =>'required',
            // ]);
            // if($validator->passes()){
            $files = $request->file('quality_check_file');
            if (is_array($files)) {
                if ($request->hasfile('quality_check_file')) {
                    $p_s_c = 0;
                    foreach ($files as $file) {
                        $ah_name = 'quality_check_file' . time() . '_' . $p_s_c . '.' . $file->extension();
                        $file->move(public_path() . '/F_F_tracker/' . $emp_id . '/quality_check_file', $ah_name);
                        $doc_row[] = [
                            'doc_type' => 'quality_check',
                            'doc_name' => $ah_name,
                        ];
                        $p_s_c++;
                    }
                } else {
                    $doc_row[] = [
                        'doc_type' => 'quality_check',
                        'doc_name' => '',
                    ];
                }
            } else {
                $doc_row[] = [
                    'doc_type' => 'quality_check',
                    'doc_name' => '',
                ];
            }
            $res2 = $this->f_f_tracker_task->get_reverts($emp_id);
            if (isset($res2[0])) {
                $flow = $res2[0]->flow + 1;
            } else {
                $flow = 1;
            }
            $count = 0;
            // echo "<pre>";print_r($request->quality_check_remark);die;
            while ($count < count($doc_row)) {
                $credentials = [
                    'emp_id' => $emp_id,
                    'flow' => $flow,
                    's_g_id' => "3",
                    'doc_type' => $doc_row[$count]['doc_type'],
                    'filename' => $doc_row[$count]['doc_name'],
                    'remark' => $request->quality_check_remark,
                    'created_by' => session('emp_id'),
                ];
                // save query document row
                $saved_query_ticket_id = $this->f_f_tracker_task->save_f_f_file_row($credentials);
                $count++;
            }
            // print_r($claims->created_at);
            // $upd_credential = [
            //             'emp_id' => $request->emp_id,
            //             'f_f_c_s_g' => "4",
            //         ];

           $claims_id = $this->cp_repository->get_claims($request->emp_id);
          

           

           if($claims_id == null) {
                $to_sg_clp = "3.5";
                $alert_to = "CL001"; // This is for employeeclaims@hepl.com
           
            }else{
                $to_sg_clp = "4";
                $alert_to = "CL001"; // Changed from PRFN001 to CL001 for employeeclaims@hepl.com
                
            }
            $upd_credential = [
                'emp_id' => $request->emp_id,
                'f_f_c_s_g' => $to_sg_clp,
            ];
            $res = $this->f_f_tracker_task->get_reverts($upd_credential);
            if (isset($res[0])) {
                if ($res[0]->created_by == "PRQC001") {
                    $upd_credential['revert_status'] = "";
                }
            }
            $result = $this->emp_task->update_p_one_alumni_f_f_s_g($upd_credential);
            // date of completed
            $date_credential = [
                'emp_id' => $request->emp_id,
                's_g' => "3",
                'created_by' => session('emp_id'),
            ];
            $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
            $cred = [
                'emp_id' => $emp_id,
                's_g_id' => "3",
                'verification' => "",
                'remark' => "",
                'created_by' => session('emp_id'),
                'flow' => $flow,
                'from_sg' => "3",
                'to_sg' => $to_sg_clp,
                'alert_to' => $alert_to,
                'v_status' => "0",
                'sts' => "Active",
                'd_sts' => "Deactive",
            ];
            $history = [
                'emp_id' => $request->emp_id,
                'from_sg' => "3",
                'to_sg' => $to_sg_clp,
                'created_by' => session('emp_id'),
                'sender_to' => "CL001", // Changed from PRFN001 to CL001 for employeeclaims@hepl.com
            ];
            $result_history = $this->f_f_tracker_task->save_history($history);
            $result4 = $this->f_f_tracker_task->notify_deactive($cred);
            $result3 = $this->f_f_tracker_task->save_notification($cred);
            if($to_sg_clp == "3.5" || $to_sg_clp == "4"){
                $toname = 'Employee Claims Team';
                $team = 'QC';
                $tomail = 'employeeclaims@hepl.com';
                $this->sendNotifyMail($team,$tomail,$toname,$request->emp_id);
            }
            

            return response()->json(['response' => "success"]);
            // }else{
            //     return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            // }
        } elseif ($request->input('process_s_g') == "5") {
            $validator = Validator::make($request->all(), [
                'sap_doc_number' => 'required',
                'posting_date' => 'required',
                'f_and_f_payable_recoverable' => 'required',
                'f_and_f_amount' => 'required',
                'f_f_accounting_file' => 'required',
            ]);
            if ($validator->passes()) {
                // if($request->reopenstatus != ""){
                //     DB::BeginTransaction();
                //     try {

                //     if (session('user_type') == "Payroll_Finance") {
                //         $cratedBy = ['PRHR001', 'PRQC001'];
                //     } else {
                //         $cratedBy = ['PRHR001'];
                //     }
                //     $getTrackerFiles = F_F_tracker_files::where('emp_id', $request->emp_id)
                //         ->whereIn('created_by', $cratedBy)
                //         ->each(function ($getTrackerFiles) {
                //             $newgetTrackerFiles = $getTrackerFiles->replicate();
                //             $newgetTrackerFiles->setTable('tracker_files_histories');
                //             $newgetTrackerFiles->save();
                //             $getTrackerFiles->delete();
                //         });
                //     // Get Hold Salary Data and Add to History
                //     $getHoldSalaryData = hold_salary::where('emp_id', $request->emp_id)
                //         ->each(function ($getHoldSalaryData) {
                //             $newgetHoldSalaryData = $getHoldSalaryData->replicate();
                //             $newgetHoldSalaryData->setTable('hold_salary_histories_reopens');
                //             $newgetHoldSalaryData->save();
                //         });
                //         // dd($newgetHoldSalaryData);
                //     // Get Alumni Data and Add to History
                //     $getAlumniData = F_F_tracker_alumni_data::where('emp_id', $request->emp_id)
                //         ->each(function ($getAlumniData) {
                //             $newgetAlumniData = $getAlumniData->replicate();
                //             $newgetAlumniData->setTable('reopen_data_histories');
                //             $newgetAlumniData->save();
                //         });
                //         // dd($getAlumniData);
                //         DB::commit();
                //     } catch (\Exception $e) {
                //         DB::rollback();
                //         return response()->json(['status' => 0, 'error' => $e]);
                //     }

                // }
                $credential = [
                    'emp_id' => $request->input('emp_id'),
                    'sap_doc_no' => $request->input('sap_doc_number'),
                    'posting_date' => $request->input('posting_date'),
                    'pay_rec' => $request->input('f_and_f_payable_recoverable'),
                    'ff_amount' => $request->input('f_and_f_amount'),
                ];
                $update_alumni_data = $this->f_f_tracker_task->update_form_f_f_data_set_3($credential);
                $files = $request->file('f_f_accounting_file');
                if (is_array($files)) {
                    $ps_count = count($files);
                    if ($request->hasfile('f_f_accounting_file')) {
                        $p_s_c = 0;
                        foreach ($files as $file) {
                            $ah_name = 'f_f_accounting_file' . time() . '_' . $p_s_c . '.' . $file->extension();
                            $file->move(public_path() . '/F_F_tracker/' . $emp_id . '/f_f_accounting_file', $ah_name);
                            $doc_row[] = [
                                'doc_type' => 'f_f_accounting',
                                'doc_name' => $ah_name,
                            ];
                            $p_s_c++;
                        }
                    }
                }
                $count = 0;
                $res2 = $this->f_f_tracker_task->get_reverts($emp_id);
                if (isset($res2[0])) {
                    $flow = $res2[0]->flow + 1;
                } else {
                    $flow = 1;
                }
                while ($count < count($doc_row)) {
                    $credentials = [
                        'emp_id' => $emp_id,
                        'flow' => $flow,
                        's_g_id' => "5",
                        'doc_type' => $doc_row[$count]['doc_type'],
                        'filename' => $doc_row[$count]['doc_name'],
                        'remark' => $request->input('f_f_accounting_remark'),
                        'created_by' => session()->get('emp_id'),
                    ];
                    // save query document row
                    $saved_query_ticket_id = $this->f_f_tracker_task->save_f_f_file_row($credentials);
                    $count++;
                }
                $upd_credential = [
                    'emp_id' => $request->input('emp_id'),
                    'f_f_c_s_g' => "6",
                ];
                $res = $this->f_f_tracker_task->get_reverts($upd_credential);
                if (isset($res[0])) {
                    if ($res[0]->created_by == "PRFN001") {
                        $upd_credential['revert_status'] = "";
                    }
                }
                $result = $this->emp_task->update_p_one_alumni_f_f_s_g($upd_credential);
                // date of completed
                $date_credential = [
                    'emp_id' => $request->input('emp_id'),
                    's_g' => "6",
                    'created_by' => session()->get('emp_id'),
                ];
                $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
                $cred = [
                    'emp_id' => $request->input('emp_id'),
                    'from_sg' => "4",
                    'to_sg' => "6",
                    'alert_to' => "ACCT001", // Changed from PRFN001 to ACCT001 for accounts@hepl.com
                    'v_status' => "0",
                    'sts' => "Active",
                    'd_sts' => "Deactive",
                ];
                // if ($stage == 5) {
                //     $cred['alert_to'] = "PRFN001";
                // } elseif ($stage == 6) {
                //     $cred['alert_to'] = "HR001";
                // }
                $history = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "4",
                    'to_sg' => "6",
                    'created_by' => session('emp_id'),
                    'sender_to' => "ACCT001", // Changed from PRFN001 to ACCT001 for accounts@hepl.com
                ];
                $result_history = $this->f_f_tracker_task->save_history($history);
                $result4 = $this->f_f_tracker_task->notify_deactive($cred);
                $result3 = $this->f_f_tracker_task->save_notification($cred);

                // \Mail::to($company_email)->send(new \App\Mail\f_f_tracker_mail($details1));
                $response = "success";

                $toname = 'Employee Claims Team';
                $team = 'Payroll Finance';
                $tomail = 'employeeclaims@hepl.com'; // Changed from accounts@hepl.com to employeeclaims@hepl.com
                $this->sendNotifyMail($team,$tomail,$toname,$request->emp_id);

                return response()->json(['response' => $response]);
            } else {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }
        } elseif ($request->input('process_s_g') == "6") {
            $validator = Validator::make($request->all(), [
                'payout_complete_file' => 'required',
                'amount' => 'required|numeric',
            ]);
            if ($validator->passes()) {
                $files = $request->file('payout_complete_file');
                if (is_array($files)) {
                    $ps_count = count($files);
                    if ($request->hasfile('payout_complete_file')) {
                        $p_s_c = 0;
                        foreach ($files as $file) {
                            $ah_name = 'payout_complete_file' . time() . '_' . $p_s_c . '.' . $file->extension();
                            $file->move(public_path() . '/F_F_tracker/' . $emp_id . '/payout_complete_file', $ah_name);
                            $doc_row[] = [
                                'doc_type' => 'payout_complete',
                                'doc_name' => $ah_name,
                            ];
                            $p_s_c++;
                        }
                    }
                }
                $res2 = $this->f_f_tracker_task->get_reverts($emp_id);
                if (isset($res2[0])) {
                    $flow = $res2[0]->flow + 1;
                } else {
                    $flow = 1;
                }
                $count = 0;
                while ($count < count($doc_row)) {
                    $credentials = [
                        'emp_id' => $emp_id,
                        'flow' => $flow,
                        's_g_id' => "6",
                        'doc_type' => $doc_row[$count]['doc_type'],
                        'filename' => $doc_row[$count]['doc_name'],
                        'remark' => $request->input('payout_complete_remark'),
                        'created_by' => session()->get('emp_id'),
                    ];
                    // save query document row
                    $saved_query_ticket_id = $this->f_f_tracker_task->save_f_f_file_row($credentials);
                    $count++;
                }

                $upd_credential = [
                    'emp_id' => $request->input('emp_id'),
                    'f_f_c_s_g' => "6",
                    'payout_amount' => $request->amount,
                ];

                $res = $this->f_f_tracker_task->get_reverts($upd_credential);

                $this->f_f_tracker_task->update_form_f_f_data_set_4($upd_credential);
                $result = $this->emp_task->update_p_one_alumni_f_f_s_g($upd_credential);

                // date of completed
                $date_credential = [
                    'emp_id' => $request->input('emp_id'),
                    's_g' => "6",
                    'created_by' => session()->get('emp_id'),
                ];
                $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);

                $cred = [
                    'emp_id' => $request->input('emp_id'),
                    'from_sg' => "5",
                    'to_sg' => "6",
                    'alert_to' => "HR001", // This is for hrss@hepl.com (final step)
                    'v_status' => "0",
                    'sts' => "Active",
                    'd_sts' => "Deactive",
                ];
                $history = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "5",
                    'to_sg' => "6",
                    'created_by' => session('emp_id'),
                    'sender_to' => "HR001",
                ];
                $result_history = $this->f_f_tracker_task->save_history($history);
                $result4 = $this->f_f_tracker_task->notify_deactive($cred);
                $result3 = $this->f_f_tracker_task->save_notification($cred);

                // \Mail::to($company_email)->send(new \App\Mail\f_f_tracker_mail($details1));

                $response = "success";

                $toname = 'Accounts Team';
                $team = 'Employee Claims';
                $tomail = 'accounts@hepl.com'; // Changed from yogesh.p@hepl.com to accounts@hepl.com
                $this->sendNotifyMail($team,$tomail,$toname,$request->emp_id);

                return response()->json(['response' => $response]);
            } else {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }
        } elseif ($request->process_s_g == "7") {
            $validator = Validator::make($request->all(), [
                // 'f_f_statement_file' => 'required',
                'relieving_letters_file' => 'required', 
                'service_letters_file' => 'required',
            ]);
            if ($validator->passes()) {
                $cred = [
                    'emp_id' => $request->emp_id,
                    'd_sts' => "Deactive",
                ];
                $result4 = $this->f_f_tracker_task->notify_deactive($cred);
                $upd_credential = [
                    'emp_id' => $request->emp_id,
                    'f_f_c_s_g' => "7",
                ];
                $result = $this->emp_task->update_p_one_alumni_f_f_s_g($upd_credential);
                // date of completed
                $date_credential = [
                    'emp_id' => $request->emp_id,
                    's_g' => "7",
                    'created_by' => session('emp_id'),
                ];
                $result2 = $this->f_f_tracker_task->insert_date_of_completed($date_credential);
                // Send Employee Mail With Attach

                $emp_id = $request->emp_id;
                $doc_arr = array("F&F Statement", "Relieving Letter", "Service Letter");
                $i = 0;

                $doc_row = [];

                while ($i < count($doc_arr)) {
                    if ($doc_arr[$i] == "F&F Statement") {
                        if ($request->hasfile('f_f_statement_file')) {
                            $request->file('f_f_statement_file');
                            $ah_name = 'ff_statement' . time() . '.' . $request->file('f_f_statement_file')->extension();
                            $request->file('f_f_statement_file')->move(public_path() . '/documents/' . $emp_id . '/ff_statement', $ah_name);
                            $doc_row[] = [
                                'doc_type' => 'F&F Statement',
                                'doc_name' => $ah_name,
                            ];
                        }
                    }
                    if ($doc_arr[$i] == "Relieving Letter") {
                        if ($request->hasfile('relieving_letters_file')) {
                            $ah_name = 'rel_letter' . time() . '.' . $request->file('relieving_letters_file')->extension();
                            $request->file('relieving_letters_file')->move(public_path() . '/documents/' . $emp_id . '/rel_letter', $ah_name);
                            $doc_row[] = [
                                'doc_type' => 'Relieving Letter',
                                'doc_name' => $ah_name,
                            ];
                        }
                    }
                    if ($doc_arr[$i] == "Service Letter") {
                        if ($request->hasfile('service_letters_file')) {
                            $ah_name = 'ser_letter' . time() . '.' . $request->file('service_letters_file')->extension();
                            $request->file('service_letters_file')->move(public_path() . '/documents/' . $emp_id . '/ser_letter', $ah_name);
                            $doc_row[] = [
                                'doc_type' => 'Service Letter',
                                'doc_name' => $ah_name,
                            ];
                        }
                    }
                    $i++;
                }
                $count = 0;
                $mail_doc_div = '';
                $ff_statement_file_url = "";
                $rel_letter_file_url = "";
                $ser_letter_file_url = "";
                $string_file_type = [];
                while ($count < count($doc_row)) {
                    $credentials = [
                        'emp_id' => $emp_id,
                        'document' => $doc_row[$count]['doc_type'],
                        'file_name' => $doc_row[$count]['doc_name'],
                        'status' => "Active",
                    ];
                    // save query document row
                    $saved_query_ticket_id = $this->doc_task->DocumentEntry($credentials);

                    // make mail doc

                    $string_file_type[] = $credentials['document'];

                    $file_name = $credentials['file_name'];

                    if ($credentials['document'] == "F&F Statement") {
                        $path = "ff_statement";
                        $ff_statement_file_name = $file_name;
                        $ff_statement_file_url = "documents/" . $emp_id . "/" . $path . "/" . $file_name . "";
                    }

                    if ($credentials['document'] == "Relieving Letter") {
                        $path = "rel_letter";
                        $rel_letter_file_name = $file_name;
                        $rel_letter_file_url = "documents/" . $emp_id . "/" . $path . "/" . $file_name . "";
                    }
                    if ($credentials['document'] == "Service Letter") {
                        $path = "ser_letter";
                        $ser_letter_file_name = $file_name;
                        $ser_letter_file_url = "documents/" . $emp_id . "/" . $path . "/" . $file_name . "";
                    }
                    $count++;
                }
                $unique_file_type = array_unique($string_file_type);
                $all_submit_doc = implode(",", $unique_file_type);
                $get_doc_rows = $this->doc_task->get_doc_entry("emp_id", $emp_id);
                if (isset($get_doc_rows[0])) {
                    $pre_doc_uploaded = [];
                    $count_i = 0;
                    while ($count_i < count($get_doc_rows)) {
                        $pre_doc_uploaded[] = $get_doc_rows[$count_i]['document'];
                        $count_i++; 
                    }
                    $unique_doc_array = array_unique($pre_doc_uploaded);
                    if (session('user_type') == "F_F_HR") {
                        $marks = array("Pay Slips", "F&F Statement", "Form 16", "Relieving Letter", "Service Letter", "Bonus", "Performance Incentive", "Sales Travel claim", "Parental medical reimbursement", "PF", "Gratuity");
                    }
                    $check_diff_array = array_diff($marks, $unique_doc_array);
                    $check_diff = [];
                    foreach ($check_diff_array as $a) {
                        $check_diff[] = $a;
                    }
                    if (isset($check_diff[0])) {
                        $doc_status = "Pending";
                    } else {
                        $doc_status = "Completed";
                    }
                } else {
                    $doc_status = "Pending";
                }
                if (session('user_type') == "F_F_HR") {
                    $credentials = [
                        'emp_id' => $emp_id,
                        'doc_status' => $doc_status,
                        'remark' => "",
                        'ff_doc_updated_by' => session('emp_id'),
                    ];
                    $update_query = $this->emp_task->update_docstatus_and_rem($credentials);
                }
                // send mail to employee
                $getempdetail = $this->emp_task->get_employee_detail($emp_id);
                // To Master Mail
                $company_email2 = $getempdetail[0]->email;
                $body_content1 = "Hello! " . $getempdetail[0]->emp_name;
                $body_content2 = "Please find attached your";
                $body_content3 = 'Pls raise a query in your login if you need further assistance,';
                $body_content4 = "https://citpl_alumni.cavinkare.in/index.php/login";
                $body_content5 = "Cheers";
                $body_content6 = "Team HR";

                $manual_directory_path = public_path() . '/F_F_tracker/' . $emp_id . '/manual_computation_file/pdf/'.$emp_id.'.pdf';
                if (file_exists($manual_directory_path)) {
                    $manual_computation_file_url = $manual_directory_path;
                }else{
                    $manual_computation_file_url = '';
                }
                $details = [
                    'subject' => 'Full & Final Settlement',
                    'title' => 'Your Documents - CITPL',
                    'body_content1' => $body_content1,
                    'body_content2' => $body_content2,
                    'body_content3' => $body_content3,
                    'body_content4' => $body_content4,
                    'body_content5' => $body_content5,
                    'body_content6' => $body_content6,
                    'pay_slip_file_url' => "",
                    'ff_statement_file_url' => $ff_statement_file_url,
                    'form16_file_url' => "",
                    'rel_letter_file_url' => $rel_letter_file_url,
                    'ser_letter_file_url' => $ser_letter_file_url,
                    'manual_computation_file_url' => $manual_computation_file_url,

                    'bonus_file_url' => "",
                    'performance_incentive_file_url' => "",
                    'sales_travel_claim_file_url' => "",
                    'parental_medical_reimbursement_file_url' => "",

                    // bonus form
                    'f_and_f_file_url' => "",

                    // t2
                    'pf_file_url' => "",
                    'gratuity_file_url' => "",
                    // t2 end

                    'others_doc_file_url' => '',
                    'all_submit_doc' => $all_submit_doc,
                ];
                $history = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => "6",
                    'to_sg' => "7",
                    'created_by' => session('emp_id'),
                    'sender_to' => "HR001",
                ];
                $result_history = $this->f_f_tracker_task->save_history($history);
                \Mail::to($company_email2)->send(new \App\Mail\QueryUpdate_doc($details));
                return response()->json(['response' => "success"]);
            } else {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }
        }

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

    public function get_f_f_tracker_files(Request $request)
    {
        $emp_id = $request->emp_id;
        $s_g_id = $request->sg_id;
        $to_sg = $request->to_sg;
        $get_tracker_files = $this->f_f_tracker_task->get_data_with_where2('f__f_tracker_files', "emp_id", $emp_id, "s_g_id", $s_g_id);
        $tbody = "";
        foreach ($get_tracker_files as $doc) {
            if ($s_g_id == "3") {
                if ($doc->remark != "") {
                    $d_rem = $doc->remark;
                } else {
                    $d_rem = "---";
                }
                $get_emp_data = $this->f_f_tracker_task->get_data_with_where($emp_id, 'emp_id', 'f__f_tracker_alumni_datas', '=');
                $tbody .= '<tr><td><a href="../F_F_tracker/' . $doc->emp_id . '/' . $doc->doc_type . '_file' . '/' . $doc->filename . '" target="_blank"">' . $doc->doc_type . '</a></td><td class="text-dark">' . $get_emp_data[0]->sap_doc_no . '</td><td class="text-dark">' . $get_emp_data[0]->posting_date . '</td><td class="text-dark">' . $get_emp_data[0]->pay_rec . '</td><td class="text-dark">' . $get_emp_data[0]->ff_amount . '</td><td class="text-dark">' . $d_rem . '</td></tr>';
            } else {
                if ($doc->remark != "") {
                    $d_rem = $doc->remark;
                } else {
                    $d_rem = "---";
                }
                $created = date('d-m-Y', strtotime($doc->created_at));
                $tbody .= '<tr><td><a href="../F_F_tracker/' . $doc->emp_id . '/' . $doc->doc_type . '_file' . '/' . $doc->filename . '" target="_blank"">' . $doc->doc_type . '</a></td><td class="text-dark">' . $d_rem . '</td><td class="text-dark">' . $created . '</td></tr>';
            }
        }
        return response()->json(['tbody' => $tbody, 'sub_by' => $get_tracker_files, 'sg' => $s_g_id]);
    }

    public function get_quality_check(Request $request)
    {
        $emp_id = $request->emp_id;
        $s_g_id = $request->sg_id;
        $result = $this->f_f_tracker_task->get_data_with_where2('f__f_tracker_files', 'emp_id', $emp_id, 's_g_id', $s_g_id);
        $file_id = [];
        $remarks = [];
        $revert_by = [];
        $created_at = [];
        $from_sg = [];
        for ($i = 0; $i < count($result) - 1; $i++) {
            $file_id[] = $result[$i]->id;
            $get_revert_remarkss = $this->f_f_tracker_task->get_data_with_where2('revert_tables', 'emp_id', $emp_id, 'flow', $result[$i]->flow);
            if (isset($get_revert_remarkss[0])) {
                $remarks[] = $get_revert_remarkss[0]->remark;
                $revert_by[] = $get_revert_remarkss[0]->created_by;
                $created_at[] = $get_revert_remarkss[0]->created_at;
                $from_sg[] = $get_revert_remarkss[0]->from_sg;
            }
        }
        return datatables()->of($result)
            ->addColumn('document', function ($row) {
                return $doc = '<a href="../F_F_tracker/' . $row->emp_id . '/' . $row->doc_type . '_file' . '/' . $row->filename . '" target="_blank"">' . $row->doc_type . '</a>';
            })
            ->addColumn('remarks', function ($row) use ($remarks, $file_id) {
                $i = 0;
                foreach ($remarks as $rem) {
                    if ($row->id == $file_id[$i]) {
                        return $rems = $rem;
                    }
                    $i++;
                }
                if (!isset($rems)) {
                    return "---";
                }
            })

            ->addColumn('from_sg', function ($row) use ($from_sg, $file_id) {
                $i = 0;
                foreach ($from_sg as $sg) {
                    if ($row->id == $file_id[$i]) {
                        if ($sg == "5") {
                            return $s_g = '<div class="badge badge-danger">5</div><i class="fa fa-arrow-right" aria-hidden="true"></i><div class="badge badge-success">4</div>';
                        } else {
                            return $s_g = '<div class="badge badge-danger">6</div><i class="fa fa-arrow-right" aria-hidden="true"></i><div class="badge badge-success">4</div>';
                        }
                    }
                    $i++;
                }
                if (!isset($s_g)) {
                    return "---";
                }
            })

            ->addColumn('revert_by', function ($row) use ($revert_by, $file_id) {
                $i = 0;
                foreach ($revert_by as $revertby) {
                    if ($row->id == $file_id[$i]) {
                        $department = $this->f_f_tracker_task->get_data_with_where($revertby, 'emp_id', 'admin_tbls', '=');
                        return $r_by = $department[0]->department;
                    }
                    $i++;
                }
                if (!isset($r_by)) {
                    return "---";
                }
            })

            ->addColumn('created_at', function ($row) use ($created_at, $file_id) {
                $i = 0;
                foreach ($created_at as $revert_at) {
                    if ($row->id == $file_id[$i]) {
                        return $revert__at = $revert_at;
                    }
                    $i++;
                }
                if (!isset($revert__at)) {
                    return "---";
                }
            })

            ->addIndexColumn()
            ->rawColumns(['document', 'remarks', 'revert_by', 'created_at', 'from_sg'])
            ->make(true);
    }

    public function fetch_tracker_details(Request $request)
    {
        if (session('user_type') == "Payroll_Finance") {
            $check_col = "fn_c_p";
        }
        if (session('user_type') == "Payroll_HR") {
            $check_col = "pr_c_p";
        }
        if (session('user_type') == "F_F_HR") {
            $check_col = "hr_ld_c_p";
        }
        $emp_id = $request->emp_id;
        $result = $this->f_f_tracker_task->get_tracker_alumni_data($emp_id);
        $get_recoveries = $this->f_f_tracker_task->get_data_with_where($emp_id, 'emp_id', 'recovery_datas', '=');
        $get_all_rec = $this->f_f_tracker_task->get_all_data('recovery_tables');
        // Get F&F Tracker Files
        $r_id = [];
        $data = [];
        $fetch_rec = "";
        foreach ($get_recoveries as $reco) {
            $r_id[] = $reco->r_id;
            $recovery = $this->f_f_tracker_task->get_data_with_where($reco->r_id, 'r_id', 'recovery_tables', '=');
            if ($recovery[0]->recovery == "Notice Period Recovery (In days)" || $recovery[0]->recovery == "Other Recovery") {
                $data[] = $reco->values;
            }
            if (session('user_type') == "F_F_HR" && $request->process_s_g != "4" && $request->process_s_g != "7") {
                $fetch_rec .= '<div class="row">
                    <div class="col-md-4">
                        <input name="rec[]" type="text" data-toggle="tooltip" data-placement="bottom" title="' . $recovery[0]->recovery . '" value="' . $recovery[0]->recovery . '" readonly class="form-control">
                        <input name="recovery[]" type="hidden" value="' . $reco->r_id . '"  class="recovery">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="value[]" value=' . $reco->values . ' class="form-control">
                    </div>
                    <div class="col-md-4">
                        <textarea type="text" id="question" name="remark[]" class="form-control mb-3" placeholder="Enter Remark">' . $reco->remark . '</textarea>
                    </div>
                    <div class="col-md-1">
                        <button value="' . $reco->id . '"  class="btn btn-outline-danger remove_field" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>';
            } else {
                $fetch_rec .= '<div class="row">
                    <div class="col-md-4">
                        <input name="rec[]" type="text" value="' . $recovery[0]->recovery . '" readonly class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="text" readonly name="value[]" value=' . $reco->values . ' class="form-control">
                    </div>
                    <div class="col-md-4">
                        <textarea readonly type="text" id="question" name="remark[]" class="form-control mb-3" placeholder="Enter Remark">' . $reco->remark . '</textarea>
                    </div>
                </div>';
            }
        }
        $rec_options = '<option value="" disabled selected>Select Recovery</option>';
        foreach ($get_all_rec as $rec) {
            if (in_array($rec->r_id, $r_id)) {
                $rec_options .= '<option disabled value="' . $rec->r_id . '" >' . $rec->recovery . '</option>';
            } else {
                $rec_options .= '<option value="' . $rec->r_id . '" >' . $rec->recovery . '</option>';
            }
        }
        if (isset($check_col)) {
            $result2 = $this->f_f_tracker_task->get_status($emp_id, $check_col);
        } else {
            $result2 = "";
            $check_col = "";
        }
        if (!isset($data[0])) {
            $data[0] = "0";
        }
        if (!isset($data[1])) {
            $data[1] = "0";
        }
        $arr1 = "nodata";
        if ($request->process_s_g == 2) {
            $arr1 = $this->getInactiveEmployeeData($emp_id);
            if ($arr1 != "nodata") {
                $arr1 = $arr1['employee_data'][0];
                // Get Reviewer Details
                $supId = $arr1['direct_manager_employee_id'];
                // Inactive Reviewer
                $revId = $this->getInactiveReviewer($supId);
                if ($revId['status'] == 1) {
                    $arr1['reviewer'] = $revId['employee_data'][0]['direct_manager_name'];
                } else {
                    // Active Reviewer
                    $activeRevId = $this->getActiveReviewer($supId);
                    if ($activeRevId['status'] == 1) {
                        $arr1['reviewer'] = $activeRevId['employee_data'][0]['direct_manager_name'];
                    } else {
                        $arr1['reviewer'] = "";
                    }
                }
            }
        }
        if ($request->process_s_g == 2 || $request->process_s_g == 3) {
            $arr3 = $this->getPaysheetData($emp_id, $request->lwd);
        } else {
            $arr3 = "nodata";
        }
        return response()->json([
            'res' => $result,
            'res2' => $result2,
            'col' => $check_col,
            'rec' => $rec_options,
            'fetch' => $fetch_rec,
            'rec_data' => $data,
            'emp_data' => $arr1,
            'sal_data' => $arr3,
        ]);
    }
    public function getInactiveEmployeeData($id)
    {
        $username = 'DB_CavinKare_Outbound';
        $password = 'IBa5uGlg62@*b!(';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cavinhub.darwinbox.in/masterapi/employee',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "api_key": "e1f52428382e702eed4d59e51afb86bf9409e078c4bbe23e13a3d39db079a5ebaa7812af23ba2ee93e9d3aac456cd77b3ca59a59390b7e41590c6e540a66b4e4",
            "datasetKey": "a8d70d08b28946c93cc24bec77d6f3e3dd39925ae233d323ac27eecb2795c6952be87a52f66aff380faa080522f1912dfd25db61861b4f37f2a8a8d627a579cf" ,
            "employee_ids": ["' . $id . '"]
        }', ));
        $response = curl_exec($curl);
        curl_close($curl);
        $arr = json_decode($response, true);
        // dd($arr['status']);
        if ($arr != null && $arr['status'] == 1) {
            return $arr;
        } else {

            return "nodata";

        }
    }
    public function getInactiveReviewer($id)
    {
        $username = 'DB_CavinKare_Outbound';
        $password = 'IBa5uGlg62@*b!(';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cavinhub.darwinbox.in/masterapi/employee',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "api_key": "9cab1bc76624a53ab19a2f4e7f689f36ee349fbdf6452e57acf871e6b377c03947013280e78e0e2aa4243afc8b38e49ad7fbbf110f958bf60d980e81e7381b51",
            "datasetKey": "bef6138287fab3a944d8792f58e2f67ba3d217c869024cfa44057e0cdaffe2c5fd5625796aabe0c4e7a56def67969e49e84b3b40b37dc3e6739207d836f86df7" ,
            "employee_ids": ["' . $id . '"]
        }', ));
        $response4 = curl_exec($curl);
        curl_close($curl);
        return json_decode($response4, true);
    }
    public function getActiveReviewer($id)
    {
        $username = 'DB_CavinKare_Outbound';
        $password = 'IBa5uGlg62@*b!(';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cavinhub.darwinbox.in/masterapi/employee',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "api_key": "e1f52428382e702eed4d59e51afb86bf9409e078c4bbe23e13a3d39db079a5ebaa7812af23ba2ee93e9d3aac456cd77b3ca59a59390b7e41590c6e540a66b4e4",
            "datasetKey": "a8d70d08b28946c93cc24bec77d6f3e3dd39925ae233d323ac27eecb2795c6952be87a52f66aff380faa080522f1912dfd25db61861b4f37f2a8a8d627a579cf" ,
            "employee_ids": ["' . $id . '"]
        }', ));
        $response4 = curl_exec($curl);
        curl_close($curl);
        return json_decode($response4, true);
    }
    public function getPaysheetData($id, $lwd)
    {
        $username1 = 'Cavincare_payroll_outbound';
        $password1 = 'V6n2YAUHsab^Xe8y';
        $curl1 = curl_init();
        $lwd = Carbon::parse($lwd);
        $lwd->subMonths(1);
        $lwd = $lwd->format('Y-m');
        curl_setopt($curl1, CURLOPT_USERPWD, "$username1:$password1");
        curl_setopt_array($curl1, array(
            CURLOPT_URL => 'https://cavinhub.darwinbox.in/Payrollapi/getsalaryregister', CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{     "api_key": "35c7dc36cf597df66f2001449e23229c09ec41ce97b9fe9ed8bef3d0b0edcbd8c384b8989c5f5e13af9befb65e1dde38d5dc3ff21a97317954b7d30e71ea2ee0","for_month": "' . $lwd . '",
        "employee_ids": ["' . $id . '"] }'));
        $response1 = curl_exec($curl1);
        curl_close($curl1);
        $arr2 = json_decode($response1, true);
        if ($arr2 != null && $arr2['status'] == 1) {
            $k = 0;
            $arr3 = [];
            foreach ($arr2['column'] as $sal) {
                if (isset($arr2['register'][$id][$k])) {
                    $arr3[Str::slug($sal, "_")] = $arr2['register'][$id][$k];
                    $k++;
                }
            }
        } else {
            $arr3 = "salnodata";
        }
        return $arr3;
    }
    public function save_revert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'revert_remark' => 'required',
            'sgt_revert' => 'required',
        ]);
        if ($validator->passes()) {
            DB::BeginTransaction();
            try {
                // session('user_type') == "Payroll_Finance" ? $from_sg = 4 : $from_sg = 3;
                // $to_sg = 2;
                // Get Tracker Files and Add to History
                if (session('user_type') == "Payroll_Finance") {
                    $cratedBy = ['PRHR001', 'PRQC001'];
                } else {
                    $cratedBy = ['PRHR001'];
                }
                $getTrackerFiles = F_F_tracker_files::where('emp_id', $request->emp_id)
                    ->whereIn('created_by', $cratedBy)
                    ->each(function ($getTrackerFiles) {
                        $newgetTrackerFiles = $getTrackerFiles->replicate();
                        $newgetTrackerFiles->setTable('tracker_files_histories');
                        $newgetTrackerFiles->save();
                        $getTrackerFiles->delete();
                    });
                // Get Hold Salary Data and Add to History
                $getHoldSalaryData = hold_salary::where('emp_id', $request->emp_id)
                    ->each(function ($getHoldSalaryData) {
                        $newgetHoldSalaryData = $getHoldSalaryData->replicate();
                        $newgetHoldSalaryData->setTable('hold_salary_histories');
                        $newgetHoldSalaryData->save();
                    });
                // Get Alumni Data and Add to History
                $getAlumniData = F_F_tracker_alumni_data::where('emp_id', $request->emp_id)
                    ->each(function ($getAlumniData) {
                        $newgetAlumniData = $getAlumniData->replicate();
                        $newgetAlumniData->setTable('tracker_data_histories');
                        $newgetAlumniData->save();
                    });

               
                // $alert_to = "PRHR001";
                $remark = $request->revert_remark;
                $csg = $request->c_c_s_g_;
                $to_sg = $request->sgt_revert;
                $from_sg = $request->process_s_g - 1;

                $update_history = DB::table('history_f_f')->where('emp_id', $request->emp_id)->where('to_sg',$from_sg)->update(['revert_status'=>'reverted']);
                // dd($update_history);

                if ($to_sg == '1') {
                    $alert_to = "HR001"; // hrss@hepl.com
                } else if ($to_sg == '2') {
                    $alert_to = "AKASH001"; // akashraj@hepl.com
                } else if ($to_sg == '3') {
                    $alert_to = "PRQC001"; // payrollqc@hepl.com
                } else if ($to_sg == '4') {
                    $alert_to = "CL001"; // employeeclaims@.com
                } else if ($to_sg == '5') {
                    $alert_to = "ACCT001"; // accounts@hepl.com
                } else if ($to_sg == '6') {
                    $alert_to = "HR001"; // hrss@hepl.com (final step)
                }
                $credentials = [
                    'emp_id' => $request->emp_id,
                    'remark' => $remark,
                    'created_by' => session('emp_id'),
                    'from_sg' => $from_sg,
                    'to_sg' => $to_sg,
                    're_open_status' => $request->reopenstatus,
                    'revert_status' => "Revert",
                    'reverted_to' => $alert_to,
                ];
                $get = $this->f_f_tracker_task->get_reverts($request->emp_id);
                if (isset($get[0])) {
                    $credentials['flow'] = $get[0]->flow + 1;
                } else {
                    $credentials['flow'] = 1;
                }
                $cred = [
                    'emp_id' => $request->emp_id,
                    'from_sg' => $from_sg,
                    'to_sg' => $to_sg,
                    'alert_to' => $alert_to,
                    'v_status' => "0",
                    'sts' => "Active",
                    'd_sts' => "Deactive",
                ];
                $result = $this->f_f_tracker_task->update_revert($credentials);
                $result2 = $this->f_f_tracker_task->save_revert($credentials);
                $result3 = $this->f_f_tracker_task->notify_deactive($cred);
                $result4 = $this->f_f_tracker_task->save_notification($cred);
                DB::commit();
                return response()->json(['res' => 'success']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => 0, 'error' => $e]);
            }
        } else {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }
    }

    public function get_revert_remarks(Request $request)
    {
        $user_type = session('user_type');
        $sg_id = [5, 6];
        $result = $this->f_f_tracker_task->get_f__f_tracker_files($request->emp_id, $sg_id);
        $flow_id = [];
        $file_id = [];
        $remarks = [];
        $revert_by = [];
        $flow_id = [];
        $created_at = [];
        foreach ($result as $result1) {
            $get_revert_docs = $this->f_f_tracker_task->get_revert_docs($result1->flow, $request->emp_id);
            if (isset($get_revert_docs[0])) {
                $file_id[] = $get_revert_docs[0]->id;
                $get_revert_remarkss = $this->f_f_tracker_task->get_revert_remarks($request->emp_id, $get_revert_docs[0]->s_g_id, $result1->flow);
            }

            if (isset($get_revert_remarkss[0])) {
                $remarks[] = $get_revert_remarkss[0]->remark;
                $revert_by[] = $get_revert_remarkss[0]->created_by;
                $flow_id[] = $get_revert_remarkss[0]->flow;
                $created_at[] = $get_revert_remarkss[0]->created_at;
            }

        }
        $results = $this->f_f_tracker_task->get_f__f_reverted_tracker_files($request->emp_id, $file_id);
        return datatables()->of($results)
            ->addColumn('document', function ($row) {
                return '<button type="button" class="btn btn-primary" title="View Files" onclick="show_file(' . "'" . $row->emp_id . "'" . ',' . "'" . $row->flow . "'" . ')"><i class="fa fa-eye"></i></button>';
            })
            ->addColumn('remarks', function ($row) use ($remarks, $file_id) {
                $i = 0;
                foreach ($remarks as $rem) {
                    if ($row->id == $file_id[$i]) {
                        return $rems = $rem;
                    }
                    $i++;
                }
                if (!isset($rems)) {
                    return "---";
                }
            })
            ->addColumn('sg', function ($row) {
                if ($row->s_g_id == "6") {
                    return '<div class="badge badge-danger">6</div><i class="fa fa-arrow-right" aria-hidden="true"></i><div class="badge badge-success">4</div>';
                } else {
                    return '<div class="badge badge-danger">5</div><i class="fa fa-arrow-right" aria-hidden="true"></i><div class="badge badge-success">4</div>';
                }
            })
            ->addColumn('revert_by', function ($row) use ($revert_by, $file_id) {
                $i = 0;
                foreach ($revert_by as $revertby) {
                    if ($row->id == $file_id[$i]) {
                        $department = $this->f_f_tracker_task->get_data_with_where($revertby, 'emp_id', 'admin_tbls', '=');
                        return $r_by = $department[0]->department;
                    }
                    $i++;
                }
                if (!isset($r_by)) {
                    return "---";
                }
            })

            ->addColumn('created_at', function ($row) use ($created_at, $file_id) {
                $i = 0;
                foreach ($created_at as $revert_at) {
                    if ($row->id == $file_id[$i]) {
                        return $revert__at = $revert_at;
                    }
                    $i++;
                }
                if (!isset($revert__at)) {
                    return "---";
                }
            })

            ->addIndexColumn()
            ->rawColumns(['document', 'remarks', 'sg', 'revert_by', 'created_at'])
            ->make(true);
    }

    public function get_check_points(Request $request)
    {
        $emp_id = $request->emp_id;
        $get_questions = $this->f_f_tracker_task->get_all_data('questions_table');
        $check_points = "";
        $i = 1;
        $option_val = array("Verified", "Not Verified", "Rejected");
        for ($k = 0; $k < count($get_questions); $k++) {
            $check_points .= '<tr class="pointer_event" >
            <td>' . $i . '</td>
            <td>
                <p>' . $get_questions[$k]->questions . '</p>
            </td>';
            $results = $this->f_f_tracker_task->get_check_points($emp_id, $get_questions[$k]->question_id);
            if (isset($results[0])) {
                $check_points .= '<td>' . $results[0]->rating . '</td>
                <td>' . $results[0]->remarks . '</td>
                <td>';
                $dep = $this->f_f_tracker_task->get_data_with_where($results[0]->created_by, 'emp_id', 'admin_tbls', '=');
                $check_points .= $dep[0]->department . '
                </td> <td><input type="hidden"  name="qc_status_id[]" value="' . $results[0]->id . '">';
                if ($results[0]->qc_status != "") {
                    $check_points .= '<select class="dis_color" id="qc_status_' . $results[0]->id . '" name="qc_status[]">';
                    for ($j = 0; $j < count($option_val); $j++) {
                        if ($results[0]->qc_status == $option_val[$j]) {
                            $check_points .= '<option value="' . $option_val[$j] . '" selected>' . $option_val[$j] . '</option>';
                        } else {
                            $check_points .= '<option value="' . $option_val[$j] . '" >' . $option_val[$j] . '</option>';
                        }
                    }
                    $check_points .= '</select>';
                } else {
                    $check_points .= '<select class="dis_color"  id="qc_status_' . $results[0]->id . '" name="qc_status[]">
                        <option value="">Status</option>
                        <option value="Verified">Verified</option>
                        <option value="Not Verified">Not Verified</option>
                        <option value="Rejected">Rejected</option>
                    </select>';
                }
                $check_points .= '</td>';
            } else {
                $check_points .= '
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                ';
            }
            $check_points .= '</tr>';
            $i++;
        }
        return response()->json(['check_points' => $check_points]);
    }

    public function update_qc_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        for ($i = 0; $i < count($id); $i++) {
            $results = $this->f_f_tracker_task->update_qc_status($id[$i], $status[$i]);
        }

        $creds = [
            'emp_id' => $request->emp_id,
        ];
        $qc_sts_count = $this->f_f_tracker_task->get_qc_status_count($creds);
        $ques_tot_count = $this->f_f_tracker_task->get_count();
        return response()->json(['res' => 'success', 'q_count' => $qc_sts_count, 'tot_count' => $ques_tot_count]);
    }

    public function get_reverted_docs(Request $request)
    {
        $emp_id = $request->emp_id;
        $flow_id = $request->flow_id;
        $sg_id = array(5, 6);
        $get_reverted_docs = $this->f_f_tracker_task->get_data_with_where_in('f__f_tracker_files', "emp_id", $emp_id, "flow", $flow_id, 's_g_id', $sg_id);
        $get_revert_remarks = $this->f_f_tracker_task->get_data_with_where2('revert_tables', "emp_id", $emp_id, "flow", $flow_id);

        return datatables()->of($get_reverted_docs)
            ->addColumn('document', function ($row) {
                return $doc = '<a href="../F_F_tracker/' . $row->emp_id . '/' . $row->doc_type . '_file' . '/' . $row->filename . '" target="_blank"">' . $row->doc_type . '</a>';
            })
            ->addColumn('r_remarks', function ($row) use ($get_revert_remarks) {
                foreach ($get_revert_remarks as $rem) {
                    return $r_rem = $rem->remark;
                }
            })
            ->addColumn('s_g_id', function ($row) {
                return '<div class="badge badge-primary ">' . ($row->s_g_id - 1) . '</div><br>';
            })
            ->addIndexColumn()
            ->rawColumns(['document', 'r_remarks', 's_g_id'])
            ->make(true);
    }

    public function get_recovery_val(Request $request)
    {
        $r_id = $request->r_id;
        $get_reveries = $this->f_f_tracker_task->get_data_with_where($r_id, 'r_id', 'recovery_tables', '=');
        $rec = "<input name='rec[]' type='text'  title='" . $get_reveries[0]->recovery . "' value='" . $get_reveries[0]->recovery . "' readonly class='form-control show_t'><input name='recovery[]' type='hidden' value=" . $get_reveries[0]->r_id . "  class='recovery'>";
        return response()->json(['res' => $rec]);
    }

    public function delete_recoveries(Request $request)
    {
        $id = $request->del_id;
        $emp_id = $request->emp_id;
        $get_r_id = $this->f_f_tracker_task->get_data_with_where($id, 'id', 'recovery_datas', '=');
        $delete_rec = $this->f_f_tracker_task->delete_recovery($id);
        return response()->json(['res' => "success", 'r_id' => $get_r_id[0]->r_id]);
    }

    public function get_hold_salary(Request $request)
    {
        $emp_id = $request->emp_id;
        $get_hold_salary = $this->f_f_tracker_task->get_data_with_where($emp_id, 'emp_id', 'hold_salaries', '=');
        if (isset($get_hold_salary[0])) {
            $hold_sal = "<div class='col-md-4'><b>Net Pay</b></div>
        <div class='col-md-4'><b>Amount ₹</b></div>
        <div class='col-md-4'><b></b></div>";
            foreach ($get_hold_salary as $row) {
                $hold_sal .= '<div class="col-md-4"><span>' . $row->month_year . ' Net Pay</span></div><div class="col-md-4"><span>' . number_format($row->amount) . '</span></div><div class="col-md-4"><span></span></div>';
            }
        } else {
            $hold_sal = "<div class='col-md-12'><b>No Data Available.</b></div>";
        }

        return response()->json(['hold_sal' => $hold_sal]);
    }

    public function get_notification(Request $request)
    {
        $cred = [
            'alert_to' => session('emp_id'),
            'v_status' => 0,
            'sts' => "Active",
        ];
        $get_notify_count = $this->f_f_tracker_task->get_notify_count($cred);
        $get_notify_users = $this->f_f_tracker_task->get_notify_users($cred);
        $notify_users = "";
        foreach ($get_notify_users as $user) {
            $now = new DateTime;
            $ago = new DateTime($user->created_at);
            $diff = $now->diff($ago);
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
            $string = array_slice($string, 0, 1);
            $time = $string ? implode(', ', $string) . ' ago' : 'just now';
            $get_emp = $this->f_f_tracker_task->get_data_with_where($user->emp_id, 'emp_id', 'emp_profile_tbls', '=');
            if ($user->from_sg > $user->to_sg) {
                $clr = "bg-danger";
            } else {
                $clr = "bg-success";
            }
            
            if (!empty($get_emp) && isset($get_emp[0])) {
                $emp_name = $get_emp[0]->emp_name;
                $emp_id = $get_emp[0]->emp_id;
            } else {
                $emp_name = 'Unknown';
                $emp_id = $user->emp_id;
            }
            
            $notify_users .= '<a href="' . route('f_f_tracker_landing') . '" target="blonk" class="dropdown-item dropdown-item-unread">
                <span class="dropdown-item-icon ' . $clr . ' text-white"> ' . $user->from_sg . ' <i class="fa fa-arrow-right" aria-hidden="true"></i>' . $user->to_sg . '</span>
                <span class="dropdown-item-desc">' . $emp_name . ' ( ' . $emp_id . ' )' . '<span class="time">' . $time . '</span></span>
            </a>';
        }
        return response()->json(['res' => $get_notify_count, 'users' => $notify_users]);
    }

    public function notify_viewed_update(Request $request)
    {
        $cred = [
            'alert_to' => session('emp_id'),
            'v_status' => 1,
        ];
        $notify_viwed_update = $this->f_f_tracker_task->update_notify($cred);
        return response()->json(['res' => "success"]);
    }

    public function get_check_points_rejected(Request $request)
    {
        $emp_id = $request->emp_id;
        $get_check_points_rejected = $this->f_f_tracker_task->get_data_with_where2('f_f_check_points', "emp_id", $emp_id, "qc_status", "Rejected");
        $cpr = '';
        $i = 1;
        foreach ($get_check_points_rejected as $row) {
            $cpr .= '<tr><td>' . $i . '</td>';
            $get_question = $this->f_f_tracker_task->get_data_with_where($row->question_id, 'question_id', 'questions_table', '=');
            if (isset($get_question[0])) {
                $cpr .= '<td>' . $get_question[0]->questions . '</td>';
            }
            $cpr .= '<td>' . $row->rating . '</td>';
            $cpr .= '<td>' . $row->remarks . '</td>';
            $get_sub_by = $this->f_f_tracker_task->get_data_with_where($row->created_by, 'emp_id', 'admin_tbls', '=');
            if (isset($get_sub_by[0])) {
                $cpr .= '<td>' . $get_sub_by[0]->department . '</td>';
            }
            $cpr .= '<td>' . $row->qc_status . '</td></tr>';
            $i++;
        }

        return response()->json(['check_points' => $cpr]);
    }

    public function check_already_exist_netpay(Request $request)
    {
        $my_array = $request->month_year_;
        $my = $request->month_year;
        if (!empty($my_array)) {
            if (in_array($my, $my_array)) {
                return response()->json(['res' => "error", 'my' => $my]);
            } else {
                return response()->json(['res' => "success"]);
            }
        } else {
            return response()->json(['res' => "success"]);
        }
    }

    public function f_f_reports(Request $request)
    {
        return view('Admin.f_f_reports');
    }


   public function get_fftrack_details(Request $req){
 
        if($req->start_date && $req->end_date){
            $get_ffdetails = F_F_tracker_alumni_data::whereBetween('created_at', [$req->start_date, $req->end_date])
            ->select('*')
            ->get();

                    return DataTables::of($get_ffdetails)
                    ->addIndexColumn() 
                    ->make(true);

            } else{
                    $get_ffdetails = F_F_tracker_alumni_data::
                    select('*')
                    ->get();  
                    return DataTables::of($get_ffdetails)
                    ->addIndexColumn() // This will add DT_RowIndex automatically
                    ->make(true);
            }
   }


    // Get F&F Pending Reports
    public function get_pending_f_f_data(Request $request)
    {

        $get_stage_gate = $this->getStageGateAndColumn([
            "type" => $request->type,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
        ]);
        $get_result = emp_profile_tbl::whereIn($get_stage_gate['col'], $get_stage_gate['stage_gate']);
        // dd($get_stage_gate);
        if (!empty($request->start_date) && $request->type == 'pending') {
            $get_result = $get_result->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date) && $request->type == 'pending') {
            $get_result = $get_result->whereDate('created_at', '<=', $request->end_date);
        }
        $get_result = $get_result->latest('created_at');
        return datatables()->of($get_result)->addColumn('emp_name', function ($row) {
            return $row->emp_name;
        })->addColumn('created_at', function ($row) use ($request) {
            if ($request->type == 'pending') {
                return date('d-m-Y', strtotime($row->created_at));
            } else {
                if ($request->type == 'f_f_completed') {
                    $get_provided_date = amb_document_tbl::where('emp_id', $row->emp_id)->where('document', 'F&F Statement')->first();
                    return date('d-m-Y', strtotime($get_provided_date->created_at));
                } else {
                    $get_provided_date_ = amb_document_tbl::where('emp_id', $row->emp_id)->where('document', 'Service Letter')->first();
                    if (isset($get_provided_date_->created_at)) {
                        $ser_letter = date('d-m-Y', strtotime($get_provided_date_->created_at));
                    } else {
                        $ser_letter = '---';
                    }
                    return $ser_letter;
                }
            }
        })->addColumn('created_at1', function ($row) use ($request) {
            if ($request->type == 'pending') {
                return date('d-m-Y', strtotime($row->created_at));
            } else {
                if ($request->type == 'f_f_completed') {
                    $get_provided_date = amb_document_tbl::where('emp_id', $row->emp_id)->where('document', 'F&F Statement')->first();
                    return date('d-m-Y', strtotime($get_provided_date->created_at));
                } else {
                    $get_provided_date = amb_document_tbl::where('emp_id', $row->emp_id)->where('document', 'Relieving Letter')->first();
                    if (isset($get_provided_date->created_at)) {
                        $rel_letter = date('d-m-Y', strtotime($get_provided_date->created_at));
                    } else {
                        $rel_letter = '---';
                    }

                    return $rel_letter;
                }
            }
        })->addColumn('last_working_date', function ($row) {
            return date('d-m-Y', strtotime($row->last_working_date));
        })->addColumn('stagegate', function ($row) {
            if ($row->f_f_c_s_g == 1.5) {
                return '<div class="badge badge-primary">1</div>';
            } else {
                return '<div class="badge badge-primary">' . $row->f_f_c_s_g . '</div>';
            }
        })->addColumn('type_of_leaving', function ($row) {
            if (!$row->type_of_leaving == "" || !$row->type_of_leaving == null) {
                $type_of_leaving = '';
                if ($row->type_of_leaving == "Abscond" || $row->type_of_leaving == "Terminated") {
                    $type_of_leaving .= '<div class="badge badge-danger doc_name">' . $row->type_of_leaving . '</div><br>';
                } elseif ($row->type_of_leaving == "Transferred") {
                    $type_of_leaving .= '<div class="badge badge-primary doc_name">' . $row->type_of_leaving . '</div><br>';
                } else {
                    $type_of_leaving .= '<div class="badge badge-success doc_name">' . $row->type_of_leaving . '</div><br>';
                }
                return $type_of_leaving;
            } else {
                return "---";
            }
        })
            ->addIndexColumn()->rawColumns([
            'emp_name', 'created_at', 'last_working_date', 'stagegate', 'type_of_leaving', 'created_at1',
        ])->make(true);
    }

    // Get F&F Reports bases on condition
    public function getStageGateAndColumn($type)
    {
        if ($type['type'] == 'pending') {
            $stage_gate = [1, 1.5];
            $col = 'f_f_c_s_g';
        } elseif ($type['type'] == 'f_f_completed') {
            $col = 'emp_id';
            $input = [
                'stage_gate' => [7],
                'document' => ['F&F Statement'],
                'filter' => 1,
                'type' => $type['type'],
                'start_date' => $type['start_date'],
                'end_date' => $type['end_date'],
            ];
            $stage_gate = $this->f_f_tracker_task->get_provided_doc_emp_id($input);
        } elseif ($type['type'] == 'relieving_service') {
            $col = 'emp_id';
            if (!empty($type['start_date']) && !empty($type['end_date'])) {
                $filter = 1;
            } else {
                $filter = 0;
            }
            $input = [
                'stage_gate' => [7],
                'document' => ['Relieving Letter', 'Service Letter'],
                'filter' => $filter,
                'type' => $type['type'],
                'start_date' => $type['start_date'],
                'end_date' => $type['end_date'],
            ];
            $stage_gate = $this->f_f_tracker_task->get_provided_doc_emp_id($input);
        }
        $data = [
            'stage_gate' => $stage_gate,
            'col' => $col,
        ];
        return $data;
    }

    // Get Payroll and HRSS Query Completed
    public function get_query_report(Request $request)
    {
        if ($request->type == 'payroll') {
            $document = ["Pay Slips", "Performance Incentive", "Bonus", "Parental medical reimbursement", "Gratuity"];
        } else {
            $document = ["F&F Statement", "Relieving Letter", "Service Letter", "PF", "Others"];
        }

        if ($request->type == "unresolved") {
            $status = ['Approved', 'Pending'];
            $column = 'updated_at';
        } else {
            $status = ["Completed"];
            $column = 'created_at';
        }

        $input = [
            'document' => $document,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $status,
            'filter_date_col' => $column,
        ];

        $get_result = $this->f_f_tracker_task->get_query_data($input);

        return datatables()->of($get_result)->addColumn('emp_name', function ($row) use ($status) {
            return emp_profile_tbl::where('emp_id', $row->emp_id)->first()->emp_name;
        })->addColumn('updated_at', function ($row) {
            return date('d-m-Y', strtotime($row->updated_at));
        })->addColumn('last_working_date', function ($row) {
            $lwd = emp_profile_tbl::where('emp_id', $row->emp_id)->first()->last_working_date;
            return date('d-m-Y', strtotime($lwd));
        })->addColumn('type_of_leaving', function ($row) {
            return emp_profile_tbl::where('emp_id', $row->emp_id)->first()->type_of_leaving;
        })->addColumn('qry_created_at', function ($row) {
            return date('d-m-Y', strtotime($row->qry_created_at));
        })
            ->addColumn('document', function ($row) use ($status) {
                $get_doc = query_document_tbl::where('ticket_id', $row->ticket_id)
                    ->whereIn('status', $status)->pluck('document')->toArray();
                $doc = implode(", ", $get_doc);
                return $doc;
            })
            ->addIndexColumn()->rawColumns([
            'emp_name', 'updated_at', 'last_working_date', 'type_of_leaving', 'qry_created_at', 'document',
        ])->make(true);
    }

    // Get F&F Transaction Report Stage Wise
    public function f_f_transaction_report(Request $request)
    {
        $get_result = new Notifications;
        if (!empty($request->start_date)) {
            $get_result = $get_result->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $get_result = $get_result->whereDate('created_at', '<=', $request->end_date);
        }

        $get_result = $get_result->latest('created_at')->groupBy('emp_id');
        return datatables()->of($get_result)->addColumn('emp_name', function ($row) {
            return emp_profile_tbl::where('emp_id', $row->emp_id)->first()->emp_name;
        })->addColumn('sg1', function ($row) {
            $received_date = emp_profile_tbl::where('emp_id', $row->emp_id)->first();
            if (isset($received_date->created_at)) {
                $received_date = date('d-m-Y', strtotime($received_date->created_at));
            } else {
                $received_date = '---';
            }
            return $received_date;
        })->addColumn('sg1_', function ($row) {
            $completed_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 1)->latest('created_at')->first();
            if (isset($completed_date->created_at)) {
                $completed_date = date('d-m-Y', strtotime($completed_date->created_at));
            } else {
                $completed_date = '---';
            }
            return $completed_date;
        })->addColumn('sg2', function ($row) {
            $received_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 1)->latest('created_at')->first();
            if (isset($received_date->created_at)) {
                $received_date = date('d-m-Y', strtotime($received_date->created_at));
            } else {
                $received_date = '---';
            }
            return $received_date;
        })->addColumn('sg2_', function ($row) {
            $completed_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 2)->latest('created_at')->first();
            if (isset($completed_date->created_at)) {
                $completed_date = date('d-m-Y', strtotime($completed_date->created_at));
            } else {
                $completed_date = '---';
            }
            return $completed_date;
        })->addColumn('sg3', function ($row) {
            $received_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 2)->latest('created_at')->first();
            if (isset($received_date->created_at)) {
                $received_date = date('d-m-Y', strtotime($received_date->created_at));
            } else {
                $received_date = '---';
            }
            return $received_date;
        })->addColumn('sg3_', function ($row) {
            $completed_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 3)->latest('created_at')->first();
            if (isset($completed_date->created_at)) {
                $completed_date = date('d-m-Y', strtotime($completed_date->created_at));
            } else {
                $completed_date = '---';
            }
            return $completed_date;
        })->addColumn('sg4', function ($row) {
            $received_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 3)->latest('created_at')->first();
            if (isset($received_date->created_at)) {
                $received_date = date('d-m-Y', strtotime($received_date->created_at));
            } else {
                $received_date = '---';
            }
            $sg = emp_profile_tbl::where('emp_id', $row->emp_id)->first();
            if ($sg->f_f_c_s_g == 4) {
                $received_date = $received_date;
            } else if ($sg->f_f_c_s_g >= 5) {
                $received_date = $received_date;
            } else {
                $received_date = '---';
            }
            return $received_date;
        })->addColumn('sg4_', function ($row) {
            $completed_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 4)->latest('created_at')->first();
            if (isset($completed_date->created_at)) {
                $completed_date = date('d-m-Y', strtotime($completed_date->created_at));
            } else {
                $completed_date = '---';
            }
            $sg = emp_profile_tbl::where('emp_id', $row->emp_id)->first();
            if ($sg->f_f_c_s_g == 4) {
                $completed_date = '---';
            } else if ($sg->f_f_c_s_g >= 5) {
                $completed_date = $completed_date;
            } else {
                $completed_date = '---';
            }
            return $completed_date;
        })->addColumn('sg5', function ($row) {
            $received_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 4)->latest('created_at')->first();
            if (isset($received_date->created_at)) {
                $received_date = date('d-m-Y', strtotime($received_date->created_at));
            } else {
                $received_date = '---';
            }
            $sg = emp_profile_tbl::where('emp_id', $row->emp_id)->first();
            if ($sg->f_f_c_s_g == 5) {
                $received_date = $received_date;
            } else if ($sg->f_f_c_s_g >= 6) {
                $received_date = $received_date;
            } else {
                $received_date = '---';
            }
            return $received_date;
        })->addColumn('sg5_', function ($row) {
            $completed_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 5)->latest('created_at')->first();
            if (isset($completed_date->created_at)) {
                $completed_date = date('d-m-Y', strtotime($completed_date->created_at));
            } else {
                $completed_date = '---';
            }
            $sg = emp_profile_tbl::where('emp_id', $row->emp_id)->first();
            if ($sg->f_f_c_s_g == 5) {
                $completed_date = '---';
            } else if ($sg->f_f_c_s_g >= 6) {
                $completed_date = $completed_date;
            } else {
                $completed_date = '---';
            }
            return $completed_date;
        })->addColumn('sg6', function ($row) {
            $received_date = Notifications::where('emp_id', $row->emp_id)->where('from_sg', 5)->latest('created_at')->first();
            if (isset($received_date->created_at)) {
                $received_date = date('d-m-Y', strtotime($received_date->created_at));
            } else {
                $received_date = '---';
            }
            $sg = emp_profile_tbl::where('emp_id', $row->emp_id)->first();
            if ($sg->f_f_c_s_g == 6) {
                $received_date = $received_date;
            } else if ($sg->f_f_c_s_g >= 7) {
                $received_date = $received_date;
            } else {
                $received_date = '---';
            }
            return $received_date;
        })->addColumn('sg6_', function ($row) {
            $completed_date = amb_document_tbl::where('emp_id', $row->emp_id)->where('document', "F&F Statement")->latest('created_at')->first();
            if (isset($completed_date->created_at)) {
                $completed_date = date('d-m-Y', strtotime($completed_date->created_at));
            } else {
                $completed_date = '---';
            }
            return $completed_date;
        })
            ->addIndexColumn()->rawColumns([
            'emp_name', 'sg1', 'sg2', 'sg3', 'sg4', 'sg5', 'sg6', 'sg7', 'sg1_', 'sg2_', 'sg3_', 'sg4_', 'sg5_', 'sg6_',
        ])->make(true);
    }

    public function getRevertRemark(Request $request)
    {
        $empId = $request->emp_id;
        $getRemark = revert_table::select("revert_tables.*", "admin_tbls.department")
            ->join('admin_tbls', 'admin_tbls.emp_id', '=', 'revert_tables.created_by')
            ->where('revert_tables.emp_id', $empId)->get();
        return Datatables::of($getRemark)
            ->addColumn('from_sg', function ($row) {
                return '<span class="badge badge-danger">' . $row->from_sg . '</span>';
            })
            ->addColumn('to_sg', function ($row) {
                return '<span class="badge badge-danger">' . $row->to_sg . '</span>';
            })
            ->addColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })->addIndexColumn()->rawColumns(['from_sg', 'to_sg', 'created_at'])->make(true);
    }

    public function getCTCMasterData(Request $request)
    {
        $getCTCMasterData = TrackerDataHistory::where('emp_id', $request->emp_id)->where('basic','!=','')
            ->get([
                'basic',
                'da',
                'other_allowance',
                'hra',
                'addl_hra',
                'lta',
                'medical',
                'conveyance',
                'spl_allowance',
                'nps',
                'super_annuation',
                'sales_incentive',
                'fixed_vehicle_allowance',
                'gross',
                'created_at',
            ]);
        return Datatables::of($getCTCMasterData)
            ->addColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })->addIndexColumn()->rawColumns(['created_at'])->make(true);
    }

    public function getHoldSalaryData(Request $request)
    {
        $getHoldSalaryData = HoldSalaryHistory::where('emp_id', $request->emp_id)
            ->get([
                'month_year',
                'amount',
                'created_at',
            ]);
        return Datatables::of($getHoldSalaryData)
            ->addColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })->addIndexColumn()->rawColumns(['created_at'])->make(true);
    }

    public function getTrackerRegisterRunFiles(Request $request)
    {
        $empId = $request->emp_id;
        $docType = $request->doc_type;
        if ($request->type == 'history') {
            $getExitRegisterRunFilesData = TrackerFilesHistory::where('emp_id', $empId)
                ->whereIn('doc_type', $docType)
                ->get();
        } else {
            $getExitRegisterRunFilesData = F_F_tracker_files::where('emp_id', $empId)
                ->whereIn('doc_type', $docType)
                ->get();
        }
        return Datatables::of($getExitRegisterRunFilesData)
            ->addColumn('document', function ($row) use ($empId) {
                if ($row->filename == "" || $row->filename == null) {
                    return '---';
                }
                return '<a target="_blonk" href="../F_F_tracker/' . $empId . '/' . $row->doc_type . '_file/' . $row->filename . '">' . $row->doc_type . '</a>';
             })->addColumn('created_at', function ($row) {
            return date('d-m-Y', strtotime($row->created_at));
        })->addIndexColumn()->rawColumns(['document', 'created_at'])->make(true);
    }

    public function getHoldSalaryDataEdit(Request $request)
    {
        $getHoldSalaryData = hold_salary::where('emp_id', $request->emp_id)->get();
        if (isset(revert_table::where('emp_id', $request->emp_id)->first()->id)) {
            $checkRevertOrNot = "yes";
        } else {
            $checkRevertOrNot = "no";
        }
        $html = '';
        foreach ($getHoldSalaryData as $row) {
            $html .= '<div class="row mt-3">
            <div class="col-md-5 netpay_date"  style="background: #fff; cursor: pointer;">
                <input type="text" name="month_year_[]" class="form-control month_yr" readonly  value="' . $row->month_year . '">
            </div>
            <div class="col-md-5">
                <input type="text" name="n_amount[]" onkeypress="javascript: return isNumber(event)" value="' . $row->amount . '"  class="form-control">
             </div>
            <div class="col-md-1">
                <button class="btn btn-outline-danger remove_field1" onclick="deleteHoldSalary(' . "'" . $row->id . "'" . ')"  title="Remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div></div>';
        }
        return response()->json(['res' => 'success', 'hold_salary' => $html, 'checkRevertOrNot' => $checkRevertOrNot]);
    }

    public function deleteHoldSalary(Request $request)
    {
        $deleteHoldSalary = hold_salary::where('id', $request->id)->delete();
        return response()->json(['res' => 'success']);
    }

    public function getFinanceDetails(Request $request)
    {
        $getFinanceData = F_F_tracker_alumni_data::from('f__f_tracker_alumni_datas as data')
            ->join('f__f_tracker_files as files', 'files.emp_id', '=', 'data.emp_id')
            ->select(
                "data.sap_doc_no",
                "data.posting_date",
                "data.pay_rec",
                "data.ff_amount",
                "files.filename",
                "files.remark",
                "files.doc_type",
                "files.created_at"
            )
            ->where('data.emp_id', $request->emp_id)
            ->where('files.doc_type', "f_f_accounting")
            ->get();
        return Datatables::of($getFinanceData)
            ->addColumn('document', function ($row) use ($request) {
                return '<a target="_blonk" href="../F_F_tracker/' . $request->emp_id . '/' . $row->doc_type . '_file/' . $row->filename . '">' . $row->doc_type . '</a>';
            })->addColumn('posting_date', function ($row) {
            return date('d-m-Y', strtotime($row->posting_date));
        })->addColumn('created_at', function ($row) {
            return date('d-m-Y', strtotime($row->created_at));
        })->addIndexColumn()->rawColumns(['document', 'posting_date', 'created_at'])->make(true);
    }

    public function getLeaveBalanceHistory(Request $request)
    {
        $getLeaveBalanceHistory = TrackerDataHistory::where('emp_id', $request->emp_id)->where('basic','!=','')
            ->get([
                'leave_balance_cl',
                'leave_balance_sl',
                'leave_balance_pl',
                'is_probation_completed',
                'created_at',
            ]);
        return Datatables::of($getLeaveBalanceHistory)
            ->addColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })->addIndexColumn()->rawColumns(['created_at'])->make(true);
    }

    public function checkChecklistStatus(Request $request)
    {
        $getExitChecklistData = emp_profile_tbl::where('emp_id', $request->emp_id)
            // ->get(["cl_c_p", "fn_c_p", "hr_ld_c_p", "it_inf_c_p"]);
            ->get(["cl_c_p", "fn_c_p", "hr_ld_c_p"]);
        // ->get(["cl_c_p", "fn_c_p", "hr_ld_c_p", "it_c_p", "it_inf_c_p"]);
        return response()->json(['status' => 'success', 'getExitChecklistData' => $getExitChecklistData]);
    }

    public function getTrackerRegisterRunFilesFinance(Request $request)
    {
        $empId = $request->emp_id;
        $docType = $request->doc_type;
        $getExitRegisterRunFilesData = F_F_tracker_files::where('emp_id', $empId)
            ->whereIn('doc_type', $docType)
            ->get();
        return Datatables::of($getExitRegisterRunFilesData)
            ->addColumn('document', function ($row) use ($empId) {
                return '<a target="_blonk" href="../F_F_tracker/' . $empId . '/' . $row->doc_type . '_file/' . $row->filename . '">' . $row->doc_type . '</a>';
            })->addColumn('created_at', function ($row) {
            return date('d-m-Y', strtotime($row->created_at));
        })->addColumn('amount', function ($row) {
            return F_F_tracker_alumni_data::where('emp_id', $row->emp_id)->first()->payout_amount;
        })->addIndexColumn()->rawColumns(['document', 'created_at', 'amount'])->make(true);
    }
    public function getRevertdetails()
    {
        return view('Admin.revert_details');
    }
    public function getRevertdetailsData(Request $request)
    {
        $getRemark = revert_table::select("revert_tables.*", "admin_tbls.department", "emp_profile_tbls.emp_name")
            ->join('admin_tbls', 'admin_tbls.emp_id', '=', 'revert_tables.created_by')
            ->join('emp_profile_tbls', 'emp_profile_tbls.emp_id', '=', 'revert_tables.emp_id')->get();
        return Datatables::of($getRemark)
            ->addColumn('from_sg', function ($row) {
                return '<span class="badge badge-danger">' . $row->from_sg . '</span>';
            })
            
            ->addColumn('to_sg', function ($row) {
                return '<span class="badge badge-danger">' . $row->to_sg . '</span>';
            })
            ->addColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })->addIndexColumn()->rawColumns(['from_sg', 'to_sg', 'created_at'])->make(true);
    }

    public function getQc_mis()
    {
        return view('Admin.qc_mis_report');
    }
    // public function getQcMisReport(Request $request)
    // {
    //     if($request->from_date != $request->to_date){
    //         $query = emp_profile_tbl::select("emp_profile_tbls.*")
    //         ->leftJoin('f__f_tracker_date_infos as di', 'di.emp_id', 'emp_profile_tbls.emp_id')
    //         ->leftJoin('revert_tables as rt', 'rt.emp_id', 'emp_profile_tbls.emp_id');
    //         // ->where('di.s_g_id',2);
    //         $query->where(function ($query) {
    //             $query->where('di.s_g_id', 2)
    //                 ->orWhere('di.s_g_id', 3);
    //         });
    //         // ->orWhere('rt.from_sg', 3)
    //         // $query->where('hf.to_sg', 3);
    //         // $query->orWhere('hf.from_sg' , 'like', 3);
    //         // $query->orWhere('rt.from_sg', 'like', 3);
    //     // if ($request->from_date && $request->to_date) {
    //         // $query->where(function ($query) use ($request) {
    //             $query->whereBetween('di.created_at', [$request->from_date, $request->to_date]);
    //             $query->orWhereBetween('rt.created_at', [$request->from_date, $request->to_date]);
    //         // });
    //     // }
        
    //     $query->groupBy('emp_profile_tbls.emp_id');
    //     $getqc_mis = $query->get();
        
    //     }else{
    //         $getqc_mis = emp_profile_tbl::select("emp_profile_tbls.*")->get();
    //     }
    //     // $query = emp_profile_tbl::select("emp_profile_tbls.*")
    //     // ->leftJoin('history_f_f as hf','hf.emp_id','emp_profile_tbls.emp_id','left')
    //     // ->leftJoin('revert_tables as rt','rt.emp_id','emp_profile_tbls.emp_id');
    //     // if ($request->from_date) {
    //     //     $query->whereBetween('hf.date', [$request->from_date, $request->to_date]);
    //     //     $query->whereBetween('rt.created_at', [$request->from_date, $request->to_date]);
    //     // }
    //     // $getqc_mis = $query->get();
    //     return Datatables::of($getqc_mis)
    //         ->addColumn('received_date', function ($row) {
    //             // $table = DB::table('history_f_f')->where('to_sg', 3)->where('emp_id', $row->emp_id)->orderBy('date','desc')->first();
    //             // print_r($row->emp_id);
    //             // print_r($table);
    //             // $table = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->orderBy('id','desc')->first();

    //             // if (!$table) {
    //                 $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //                 if(!$table_date_info){
    //                     return '-';
    //                 }else{
    //                     // dd($table_date_info);
    //                     return date('d-m-Y', strtotime($table_date_info->created_at));
    //                 }
    //             // } else {
    //             //     return date('d-m-Y', strtotime($table->created_at));
    //             // }
    //         })
    //         ->addColumn('received_time', function ($row) {
    //             $table = DB::table('history_f_f')->where('to_sg', 3)->where('emp_id', $row->emp_id)->first();

    //             if (!$table) {
    //                 // return '-';
    //                 $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //                 if(!$table_date_info){
    //                     return '-';
    //                 }else{
    //                     return date('H:i:s', strtotime($table_date_info->created_at));
    //                 }
    //             } else {
        

    //                 return date('H:i:s', strtotime($table->time));
    //             }
    //         })
    //         ->addColumn('qc_remark', function ($row) {
    //             $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //             if (!$table) {
    //                 return '-';
    //             } else {
    //                 return $table->remark;
    //             }
    //         })
    //         ->addColumn('send_to_hr_date', function ($row) {
    //             $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //             if (!$table) {
    //                 return '-';
    //             } else {
    //             // dd($table);
    //                 return date('d-m-Y', strtotime($table->created_at));
    //             }
    //         })
    //         ->addColumn('send_to_hr_time', function ($row) {
    //             $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //             if (!$table) {
    //                 return '-';
    //             } else {
    //             // print_r($table);

    //                 return date('H:i:s', strtotime($table->created_at));
    //             }
    //         })
    //         ->addColumn('received_from_hr_date', function ($row) {
    //             $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //             if (!$table) {
    //                 return '-';
    //             } else {
    //                 // $table2 = DB::table('history_f_f')->where('from_sg', 2)->where('emp_id', $row->emp_id)->where('created_at','>',$table->created_at)->orderBy('id','desc')->first();
    //                 $table2 = DB::table('f__f_tracker_date_infos')
    //                     ->where('s_g_id', 2)
    //                     ->where('emp_id', $row->emp_id)
    //                     ->where('created_at', '>', $table->created_at)
    //                     ->orderBy('id', 'desc')
    //                     ->first();

    //                 // dd($table2->time);
    //                 if (!$table2) {
    //                     return '-';
    //                 } else {
                       
    //                     return date('d-m-Y', strtotime($table2->created_at));
    //                 }
    //             }
    //         })
    //         ->addColumn('received_from_hr_time', function ($row) {
    //             $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //             if (!$table) {
    //                 return '-';
    //             } else {
    //                 // $table2 = DB::table('history_f_f')->where('from_sg', 2)->where('emp_id', $row->emp_id)->where('created_at','>',$table->created_at)->orderBy('id','desc')->first();
    //                 $table2 = DB::table('f__f_tracker_date_infos')
    //                     ->where('s_g_id', 2)
    //                     ->where('emp_id', $row->emp_id)
    //                     ->where('created_at', '>', $table->created_at)
    //                     ->orderBy('id', 'desc')
    //                     ->first();
    //                 if (!$table2) {
    //                     return '-';
    //                 } else {
    //                     return date('H:i:s', strtotime($table2->created_at));
    //                 }
    //             }
    //         })
    //         ->addColumn('send_to_finance', function ($row) {
    //             if ($row->f_f_c_s_g >= 4) {
    //                 // $table = DB::table('history_f_f')->where('from_sg', 3)->where('to_sg', 4)->where('emp_id', $row->emp_id)->first();
    //                 $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();

    //                 // dd($table);
    //                 if (!$table_date_info) {
    //                     return '<span class="badge badge-danger">NO</span>';
    //                 } else {
    //                     return '<span class="badge badge-success">Yes</span></span>';
    //                 }
    //             } else {
    //                 return '<span class="badge badge-info">Pending</span></span>';
    //             }
    //         })
    //         ->addColumn('date', function ($row) {
    //             $table = DB::table('history_f_f')->where('from_sg', 3)->where('to_sg', 4)->where('emp_id', $row->emp_id)->first();
    //             if (!$table) {
    //                 // return '<span class="badge badge-danger">NO</span>';
    //                 // return '-';
    //                 $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //                 if(!$table_date_info){
    //                     return '-';
    //                 }else{
    //                     return date('d-m-Y', strtotime($table_date_info->created_at));
    //                 }

    //             } else {
    //                 // return '<span class="badge badge-success">Yes</span></span>';
    //                     return date('d-m-Y', strtotime($table->date));
    //             }
    //         })
    //         ->addColumn('time', function ($row) {
    //             $table = DB::table('history_f_f')->where('from_sg', 3)->where('to_sg', 4)->where('emp_id', $row->emp_id)->first();
    //             if (!$table) {
    //                 $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
    //                 if(!$table_date_info){
    //                     return '-';
    //                 }else{
    //                     return date('H:i:s', strtotime($table_date_info->created_at));
    //                 }
    //                 // return '<span class="badge badge-danger">NO</span>';
    //                 return '-';

    //             } else {
    //                 // return '<span class="badge badge-success">Yes</span></span>';
    //                 return date('H:i:s', strtotime($table->time));

    //             }
    //         })
    //         ->addColumn('status', function ($row) {
    //             if ($row->f_f_c_s_g > 3) {
    //                 return '<span class="badge badge-success">Completed</span>';
    //             } else {
    //                 return '<span class="badge badge-warning">Pending</span></span>';
    //             }
    //         })
    //         ->addIndexColumn()->rawColumns(['received_date', 'received_time', 'qc_remark', 'send_to_hr_date', 'send_to_hr_time', 'received_from_hr_date', 'received_from_hr_time', 'send_to_finance', 'date', 'time', 'status'])->make(true);
    // }

    public function getQcMisReport(Request $request)
    {
        if($request->from_date != $request->to_date){
            $query = emp_profile_tbl::select("emp_profile_tbls.*")
            ->leftJoin('f__f_tracker_date_infos as di', 'di.emp_id', 'emp_profile_tbls.emp_id')
            ->leftJoin('revert_tables as rt', 'rt.emp_id', 'emp_profile_tbls.emp_id');
            $query->where(function ($query) {
                $query->where('di.s_g_id', 2)
                    ->orWhere('di.s_g_id', 3);
            });
                $query->whereBetween('di.created_at', [$request->from_date, $request->to_date]);
                $query->orWhereBetween('rt.created_at', [$request->from_date, $request->to_date]);
        
        $query->groupBy('emp_profile_tbls.emp_id');
        $getqc_mis = $query->get();
        
        }else{
            $getqc_mis = emp_profile_tbl::select("emp_profile_tbls.*")->get();

        }
        return Datatables::of($getqc_mis)
            ->addColumn('received_date', function ($row) {
        // echo "123<pre>";print_r($row->remark);
                    $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->where('emp_id', $row->emp_id)->first();
                    if(!$table_date_info){
                        return '-';
                    }else{
                        return date('d-m-Y', strtotime($table_date_info->created_at));
                    }
            })
            ->addColumn('received_time', function ($row) {
                $table = DB::table('history_f_f')->where('to_sg', 3)->where('emp_id', $row->emp_id)->first();
                if (!$table) {
                    $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->where('emp_id', $row->emp_id)->first();
                    if(!$table_date_info){
                        return '-';
                    }else{
                        return date('H:i:s', strtotime($table_date_info->created_at));
                    }
                } else {
                    return date('H:i:s', strtotime($table->time));
                }
            })
            ->addColumn('qc_remark', function ($row) {
                $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                if (!$table) {
                    return '-';
                } else {
                    return $table->remark;
                }
            })
            ->addColumn('qc_remarks', function ($row) {
                $remarks = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->pluck('remark')->toArray();

                if (!empty($remarks)) {
                    $htmlList = '';
                    for( $i=0 ; $i< count($remarks) ; $i++){
                        $htmlList .= '  => '. $remarks[$i] . '<br><br>' ;
                    }
                    $data = $htmlList;
                    return $data;
                } else {
                    return '-';
                }
                
            })
            ->addColumn('send_to_hr_date', function ($row) {
                $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                if (!$table) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($table->created_at));
                }
            })
            ->addColumn('send_to_hr_time', function ($row) {
                $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                if (!$table) {
                    return '-';
                } else {
                    return date('H:i:s', strtotime($table->created_at));
                }
            })
            ->addColumn('received_from_hr_date', function ($row) {
                $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                if (!$table) {
                    return '-';
                } else {
                    $table2 = DB::table('f__f_tracker_date_infos')
                        ->where('s_g_id', 2)
                        ->where('emp_id', $row->emp_id)
                        ->where('created_at', '>', $table->created_at)
                        ->orderBy('id', 'desc')
                        ->first();

                    if (!$table2) {
                        return '-';
                    } else {
                       
                        return date('d-m-Y', strtotime($table2->created_at));
                    }
                }
            })
            ->addColumn('received_from_hr_time', function ($row) {
                $table = DB::table('revert_tables')->where('from_sg', 3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                if (!$table) {
                    return '-';
                } else {
                    $table2 = DB::table('f__f_tracker_date_infos')
                        ->where('s_g_id', 2)
                        ->where('emp_id', $row->emp_id)
                        ->where('created_at', '>', $table->created_at)
                        ->orderBy('id', 'desc')
                        ->first();
                    if (!$table2) {
                        return '-';
                    } else {
                        return date('H:i:s', strtotime($table2->created_at));
                    }
                }
            })
            ->addColumn('send_to_finance', function ($row) {
                if ($row->f_f_c_s_g >= 4) {
                    $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',2)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                    if (!$table_date_info) {
                        $btn =  '<span class="badge badge-danger">NO</span>';
                    } else {
                        $btn= '<span class="badge badge-success">Yes</span></span>';
                    }
                } else {
                    $btn = '<span class="badge badge-info">Pending</span></span>';
                }
                return $btn;
            })
            ->addColumn('date', function ($row) {
                $table = DB::table('history_f_f')->where('from_sg', 3)->where('to_sg', 4)->where('emp_id', $row->emp_id)->first();
                if (!$table) {
                    $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                    if(!$table_date_info){
                        return '-';
                    }else{
                        return date('d-m-Y', strtotime($table_date_info->created_at));
                    }

                } else {
                        return date('d-m-Y', strtotime($table->date));
                }
            })
            ->addColumn('time', function ($row) {
                $table = DB::table('history_f_f')->where('from_sg', 3)->where('to_sg', 4)->where('emp_id', $row->emp_id)->first();
                if (!$table) {
                    $table_date_info = DB::table('f__f_tracker_date_infos')->where('s_g_id',3)->where('emp_id', $row->emp_id)->orderBy('id','desc')->first();
                    if(!$table_date_info){
                        return '-';
                    }else{
                        return date('H:i:s', strtotime($table_date_info->created_at));
                    }
                    return '-';

                } else {
                    return date('H:i:s', strtotime($table->time));
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->f_f_c_s_g > 3) {

                    $btn=  '<span class="badge badge-success">Completed</span>';
                } else {
                    $btn=  '<span class="badge badge-warning">Pending</span></span>';
                }
                return $btn;
            })
            ->addColumn('amount_remarks', function ($row) {
                // DB::enableQueryLog();
                             $emp_id = session('emp_id');
                // dd($emp_id);
                $table_date_info = DB::table('f__f_tracker_files')
                    ->select('remark as amount_remarks')
                    ->where('emp_id', $row->emp_id)
                    ->where('created_by',$emp_id )
                    ->orderBy('id', 'desc')
                    ->first();
                // dd(DB::getQueryLog());

                if ($table_date_info !== null && !empty($table_date_info->amount_remarks)) {
                    $btn = $table_date_info->amount_remarks;
                } else {
                    $btn = '-';
                }

                return $btn;
            })

            ->addIndexColumn()
            ->rawColumns(['received_date', 'received_time', 'qc_remark', 'qc_remarks', 'send_to_hr_date', 'send_to_hr_time', 'received_from_hr_date', 'received_from_hr_time', 'send_to_finance', 'date', 'time', 'status','amount_remarks'])
            ->make(true);
    }

}
