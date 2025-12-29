<style>
    

  /* #f_f_tracker_inp_form {
    text-align: center;
  } */

  
</style>
<button type="button" class="btn btn-primary" id="show_f_f_inp_pop_trigger" data-toggle="modal"
    data-target="#f_f_inp_show_pop" style="display:none;">F&F</button>
<div class="modal fade bd-example-modal-lg" id="f_f_inp_show_pop" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title heading" id="myLargeModalLabel">
                    F&F Computation&nbsp;-<b class="pop_f_f_track_emp_name" id="pop_f_f_track_emp_name"></b> (&nbsp;<b
                        class="pop_f_f_track_emp_id" id="pop_f_f_track_emp_id"></b>&nbsp;)
                </h5>
                <button type="button" style="font-size:20px" class="close text-white btn btn-sm btn-outline-info"
                    data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="scrolly2 card-body ">

                <ul class="nav nav-tabs" id="myTab1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home1-tab" data-toggle="tab" href="#tab3" role="tab"
                            aria-controls="home1" aria-selected="true">F&F Tracker</a>
                    </li>
                    @if (session('user_type') == 'Payroll_Finance' || session('user_type') == 'Payroll_HR')
                        <li class="nav-item">
                            <a class="nav-link check_tab" id="profile1-tab" data-toggle="tab" href="#tab4"
                                role="tab" aria-controls="profile1" aria-selected="false">F&F Check Point</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link sg1" id="profiles-tab" data-toggle="tab" href="#tabs4" role="tab"
                            aria-controls="profiles" value="stage1" aria-selected="false">Details & Recovery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sg2" id="profiles1-tab" data-toggle="tab" href="#tabs5" role="tab"
                            aria-controls="profiles1" value="stage2" aria-selected="false">F&F Statement</a>
                    </li>
                    @if (session('user_type') == 'F_F_HR' || session('user_type') == 'Payroll_Finance'|| session('user_type') == 'Payroll_QC')
                        <li class="nav-item">
                            <a class="nav-link sg7" id="qualityCheckTab" data-toggle="tab" href="#tabs11" role="tab"
                                aria-controls="profiles7" aria-selected="false">Quality Check</a>
                        </li>
                    @endif
                    @if (session('user_type') == 'F_F_HR' || session('user_type') == 'Payroll_Finance')
                        <li class="nav-item">
                            <a class="nav-link financeFirstTab" id="stage4Finance" data-toggle="tab"
                                href="#stage4FinanceTab" role="tab" aria-controls="stage4Finance"
                                aria-selected="false">F&F Accounting</a>
                        </li>
                    @endif
                    @if (session('user_type') == 'F_F_HR')
                        <li class="nav-item">
                            <a class="nav-link" id="stage5Finance" data-toggle="tab" href="#stage5FinanceTab"
                                role="tab" aria-controls="stage5Finance" aria-selected="false">Payout Complete</a>
                        </li>
                    @endif
                    <li class="nav-item" id="history_tab" hidden="true">
                        <a class="nav-link" id="histories" data-toggle="tab" href="#history_tab_div" role="tab"
                            aria-controls="histories" value="histories" aria-selected="false">History</a>
                    </li>
                    <!-- <li
                        @if (session('user_type') == 'F_F_HR' || session('user_type') == 'Payroll_HR')  class="nav-item revert" @else class="nav-item revert d-none" @endif>
                        <a class="nav-link" onclick="swap()" data-toggle="tab" href="#revert_tab" role="tab"
                            aria-controls="contact_" aria-selected="false">Revert</a>
                    </li> -->

                </ul>
                <div class="tab-content" id="myTabContent11">
                    <div class="tab-pane fade show active" id="tab3" role="tabpanel"
                        aria-labelledby="home1-tab">
                        <div class="card-body" style="padding:0px">
                            <div class="modal-body">
                                <input type="hidden" id="tab_type" name="tab_type">
                                <input type="hidden" id="tab_type2" name="tab_type2">
                                <form action="javascript:void(0)" method="POST" id="f_f_tracker_inp_form"
                                    style="margin-top:-25px">
                                    <div class="spinner-border  d-none" role="status" style="position: absolute; margin-top: 15rem;text-align: center;margin-left: 35rem; padding: 20px; z-index: 100">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <input type="hidden" id="f_f_pop_emp_id" name="emp_id">
                                    <input type="hidden" id="f_f_process_s_g" name="process_s_g">
                                    <input type="hidden" id="reopenstatus" name="reopenstatus">
                                    <input type="hidden" id="re_opened_by" name="re_opened_by" value="<?php echo session('emp_id')?>" disabled>
                                    <input type="hidden" id="submit_type" name="submit_type">
                                    <input type="hidden" id="c_c_s_g_" name="c_c_s_g_">
                                    <br>
                                    <div id="s_g_2_field" class="pop_f_f_inp_div_set">
                                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show_click" id="home-tab1"
                                                    data-toggle="tab" href="#tab1" role="tab"
                                                    aria-controls="home" aria-selected="true">&nbsp;Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="profile2-tab" data-toggle="tab"
                                                    href="#tab_check" role="tab" aria-controls="profile1"
                                                    aria-selected="false">F&F Check Point</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link show_click" id="profile-tab1" data-toggle="tab"
                                                    href="#tab2" role="tab" aria-controls="profile"
                                                    aria-selected="false">&nbsp;Recovery</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link show_click" id="f_f_computation" data-toggle="tab"
                                                    href="#f_f_tracker" role="tab" aria-controls="profile3"
                                                    aria-selected="false">&nbsp;No Dues</a>
                                            </li>
                                            <li class="nav-item" id="history_tab1">
                                                <a class="nav-link" id="ffhrhistory" data-toggle="tab" href="#revert_remark_tab_view_sg1" role="tab"
                                                    aria-controls="ffhrhistory" value="histories" aria-selected="false">History</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent22"
                                            style="margin-bottom:-90px;margin-top:-20px">
                                            <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                                aria-labelledby="home-tab1">
                                                <div class="card-body">
                                                    <label>Clearance Date </label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Supervisor Clearance<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" name="supervisor_clearance"
                                                                    id="supervisor_clearance"
                                                                    class="form-control red_border">
                                                                <span class="text-danger error-text"
                                                                    id="supervisor_clearance_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Commercial / Admin Clearance<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date"
                                                                    name="commercial_admin_clearance"
                                                                    id="commercial_admin_clearance"
                                                                    class="form-control red_border">
                                                                <span class="text-danger error-text"
                                                                    id="commercial_admin_clearance_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Finance Clearance<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" name="finanace_clearance"
                                                                    id="finanace_clearance"
                                                                    class="form-control red_border">
                                                                <span class="text-danger error-text"
                                                                    id="finanace_clearance_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>IT Clearance<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" name="it_clearance"
                                                                    id="it_clearance" class="form-control red_border">
                                                                <span class="text-danger error-text"
                                                                    id="it_clearance_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label>Details</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Grade Set</label>
                                                                <input type="text" name="grade_set" id="grade_set"
                                                                    class="form-control avoid_special_char"
                                                                    placeholder="ex: Band 4/Band 5/Band 6">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Grade</label>
                                                                <input type="text" name="grade" id="grade"
                                                                    class="form-control avoid_special_char"
                                                                    placeholder="ex: 4B/5B/6B">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Department</label>
                                                                <input type="text" name="department"
                                                                    id="department" class="form-control"
                                                                    placeholder="ex: IT"
                                                                    oninput="this.value = this.value.replace(/[0-9]/g, '')">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Work Location</label>
                                                                <input type="text" name="work_location"
                                                                    id="work_location"
                                                                    class="form-control avoid_special_char"
                                                                    placeholder="ex: Chennai"
                                                                    onkeypress="return /^[ A-Za-z]*$/i.test(event.key)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Supervisor Name</label>
                                                                <input type="text" name="supervisor_name"
                                                                    id="supervisor_name"
                                                                    class="form-control avoid_special_char"
                                                                    onkeypress="return /^[ A-Za-z]*$/i.test(event.key)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Reviewer Name</label>
                                                                <input type="text" name="reviewer_name"
                                                                    id="reviewer_name"
                                                                    class="form-control avoid_special_char"
                                                                    onkeypress="return /^[ A-Za-z]*$/i.test(event.key)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Headquarters</label>
                                                                <input type="text" name="headquarters"
                                                                    id="headquarters"
                                                                    class="form-control avoid_special_char"
                                                                    onkeypress="return /^[ A-Za-z]*$/i.test(event.key)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>HRBP Name</label>
                                                                <input type="text" name="hrbp_name" id="hrbp_name"
                                                                    class="form-control avoid_special_char"
                                                                    onkeypress="return /^[ A-Za-z]*$/i.test(event.key)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Last Working Date</label>
                                                                <input type="date" readonly
                                                                    name="last_working_date" id="last_working_date"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Seperation Date</label>
                                                                <input type="date" readonly name="seperation_date"
                                                                    id="seperation_date" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Date Of Joining</label>
                                                                <input type="date" name="date_of_joining"
                                                                    id="date_of_joining" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Date Of Resignation</label>
                                                                <input type="date" name="date_of_resignation"
                                                                    id="date_of_resignation" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade " id="tab2" role="tabpanel" aria-labelledby="profile-tab1">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="input-group mt-2">
                                                                <select class="form-control " id="rec_val">
                                                                    <option selected disabled value="">Select Recovery</option>
                                                                    @foreach ($recoveries as $rec)
                                                                        <option value="{{ $rec->r_id }}">{{ $rec->recovery }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-primary"id="add_rec"><i class="fa fa-plus"></i>&nbsp;Add</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-5">
                                                            <span class="text-danger" id="rec_validation"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row label_div mt-2" hidden="true">
                                                        <div class="col-md-4">
                                                            <b>Recovery</b>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Value</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <b>Remark</b>
                                                        </div>
                                                    </div>
                                                    <div class="hide_all "></div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="f_f_tracker" role="tabpanel"
                                                aria-labelledby="profile-tab3">
                                                <label class="mt-3">No dues<span class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Document</label>
                                                            <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv" onchange="checkextension(this)" name="no_dues_file[]" id="no_dues_file" class="form-control red-border" multiple>
                                                            <span class="text-danger error-text" id="no_dues_file_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <label>Remark</label>
                                                            <textarea class="form-control" autocomplete="off" name="no_dues_remark" id="no_dues_remark"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade " id="tab_check" role="tabpanel"
                                                aria-labelledby="profile2-tab">
                                                <div class="card-body">
                                                    <ul class="nav nav-tabs ml-4" id="myTab_check" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" onclick="fresh_tab_click();" id="check_tab1" data-toggle="tab" href="#fresh_c_p_tab_check" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-exclamation-triangle"></i>&nbsp;Fresh</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" onclick="inprogress_tab_click2();" id="check_tab2" data-toggle="tab"
                                                                href="#inprogress_c_p_tab_check" role="tab" aria-controls="profile" aria-selected="false"><i
                                                                    class="far fa-edit"></i>&nbsp;In progress</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" onclick="completed_tab_click2();" id="check_tab3" data-toggle="tab"
                                                                href="#completed_c_p_tab_check" role="tab" aria-controls="contact" aria-selected="false"><i
                                                                    class="fas fa-check"></i>&nbsp;Completed</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="myTabContent_check">
                                                        <div class="tab-pane fade show active"
                                                            id="fresh_c_p_tab_check" role="tabpanel"
                                                            aria-labelledby="check_tab1">
                                                            <div class="card-body">
                                                                <div class="">
                                                                    <table class="table table-striped data-table"
                                                                        id="fresh_c_p_tbl_check">
                                                                        <thead>
                                                                            <th>Check Points</th>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer ">
                                                                <button
                                                                    class="btn btn-outline-success save float-right save_f_and_f_doc"
                                                                    id="save_f_and_f_doc2" type="button"><span><i
                                                                            class="fa fa-save"></i></span>&nbsp;Save</button>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="inprogress_c_p_tab_check"
                                                            role="tabpanel" aria-labelledby="check_tab2">
                                                            <div class="card-body ">
                                                                <div class="">
                                                                    <table class="table table-striped data-table" id="inprogress_c_p_tbl_check">
                                                                        <thead>
                                                                            <th>Check Points</th>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer ">
                                                                <button class="btn btn-outline-success save float-right save_f_and_f_doc"
                                                                    id="save_f_and_f_doc" type="button"><span><i class="fa fa-save"></i></span>&nbsp;Update</button>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="completed_c_p_tab_check"
                                                            role="tabpanel" aria-labelledby="check_tab3">
                                                            <div class="card-body ">
                                                                <div class="">
                                                                    <table class="table table-striped data-table" id="completed_c_p_tbl_check">
                                                                        <thead>
                                                                            <th>Check Points</th>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade " id="revert_remark_tab_view_sg1" role="tabpanel"
                                                aria-labelledby="revert_remark_tab_view_sg1">
                                                <div class="card-body">
                                                    <table id="revert_remark_table" class="table table-bordered" cellspacing="0"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Revert From SG</th>
                                                                <th>Revert To SG</th>
                                                                <th>Revert Remark</th>
                                                                <th>Revert By</th>
                                                                <th>Reverted At</th>
                                                            </tr>
                                                        </thead>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    {{-- @if (session('user_type') == 'Payroll_QC' || session('user_type') == 'Payroll_Finance') --}}
                                    <ul class="nav nav-tabs" id="mytab"
                                        style="margin-top:-10px;margin-left:-26px" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home_tab" data-toggle="tab" href="#forward_tab" role="tab" aria-controls="home_" aria-selected="true">&nbsp;Forward</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact_tab" data-toggle="tab" href="#revert_tab" role="tab" aria-controls="contact_" aria-selected="false">Revert </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent0">
                                        <div class="tab-pane fade show active" id="forward_tab" role="tabpanel" aria-labelledby="home_tab">
                                            <div class="card-body for_hide">
                                                <div id="s_g_4_field" class="pop_f_f_inp_div_set">
                                                    <label>Quality Check</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Document</label>
                                                                <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv" onchange="checkextension(this)"
                                                                    name="quality_check_file[]" id="quality_check_file" class="form-control red_border" multiple>
                                                                <span class="text-danger error-text" id="quality_check_file_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Remark</label>
                                                                <textarea class="form-control" autocomplete="off" name="quality_check_remark" id="quality_check_remark"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="s_g_5_field" class="pop_f_f_inp_div_set">
                                                    <label>F&F Accounting</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Employee ID</label>
                                                                <input type="text" readonly name="ff_emp_id" id="ff_emp_id" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Sap Doc Number<span class="text-danger">*</span></label>
                                                                <input type="text" name="sap_doc_number" id="sap_doc_number" class="form-control red_border">
                                                                <span class="text-danger error-text" id="sap_doc_number_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Posting Date<span class="text-danger">*</span></label>
                                                                <input type="date" name="posting_date" id="posting_date" class="form-control red_border">
                                                                <span class="text-danger error-text" id="posting_date_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>F&F Payable / Recoverable<span class="text-danger">*</span></label>
                                                                <select name="f_and_f_payable_recoverable" id="f_and_f_payable_recoverable" class="form-control red_border">
                                                                    <option value="">Choose</option>
                                                                    <option value="Payable">Payable</option>
                                                                    <option value="Recoverable">Recoverable
                                                                    </option>
                                                                </select>
                                                                <span class="text-danger error-text" id="f_and_f_payable_recoverable_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>F&F Amount<span class="text-danger">*</span></label>
                                                                <input type="text" name="f_and_f_amount" id="f_and_f_amount" class="form-control red_border">
                                                                <span class="text-danger error-text" id="f_and_f_amount_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>F&F Accounting<span class="text-danger">*</span></label>
                                                                <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv" onchange="checkextension(this)"
                                                                    name="f_f_accounting_file[]" id="f_f_accounting_file" class="form-control red_border" multiple>
                                                                <span class="text-danger error-text" id="f_f_accounting_file_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Remark</label>
                                                                <textarea class="form-control " autocomplete="off" name="f_f_accounting_remark" id="f_f_accounting_remark"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="show_pending_dep" class="mt-2">
                                                        <b id="heading_dep"></b><br>
                                                        <h6 id="q_dep_0" class="checkpointPending badge badge-danger">
                                                        </h6>
                                                        <h6 id="q_dep_1" class="checkpointPending badge badge-danger">
                                                        </h6>
                                                        <h6 id="q_dep_2" class="checkpointPending badge badge-danger">
                                                        </h6>
                                                        <!-- <h6 id="q_dep_3" class="checkpointPending badge badge-danger">
                                                        </h6> -->
                                                        {{-- <h6 id="q_dep_4"
                                                                class="checkpointPending badge badge-danger">
                                                            </h6> --}}
                                                    </div>
                                                </div>
                                                <div id="s_g_6_field" class="pop_f_f_inp_div_set">
                                                    <label>Payout Complete</label>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Document<span class="text-danger">*</span></label>
                                                                <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv" onchange="checkextension(this)" name="payout_complete_file[]"
                                                                    id="payout_complete_file" class="form-control red_border" multiple>
                                                                <span class="text-danger error-text" id="payout_complete_file_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Amount<span class="text-danger">*</span></label>
                                                                <input type="text" name="amount" id="amount" class="form-control red_border">
                                                                <span class="text-danger error-text" id="amount_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Remark</label>
                                                                <textarea class="form-control" autocomplete="off" name="payout_complete_remark" id="payout_complete_remark"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="s_g_3_field" class="pop_f_f_inp_div_set" style="margin-left:-2rem">
                                                    <ul class="nav nav-tabs" id="myTab3" role="tablist">

                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="ctc_tab" data-toggle="tab" href="#ctc_master" role="tab" aria-controls="home"
                                                                aria-selected="true">&nbsp;CTC Master</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="hold_tab" data-toggle="tab" href="#hold_salary" role="tab" aria-controls="contact"
                                                                aria-selected="false">&nbsp;Hold Salary</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="profile-tab2" data-toggle="tab"
                                                                href="#leave_bal" role="tab" aria-controls="profile2"
                                                                aria-selected="false">&nbsp;Leave Balance</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="exit_tab" data-toggle="tab" href="#exit_reg_run" role="tab" aria-controls="profile"
                                                                aria-selected="false">&nbsp;F&F Statement</a>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content" id="myTabContent6">
                                                        <div class="tab-pane fade show active" id="ctc_master" role="tabpanel"
                                                            aria-labelledby="ctc_tab">
                                                            <div class="card-body">
                                                                <label>CTC Master</label>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Basic</label><span class="text-danger">*</span>
                                                                            <input type="text" name="basic" id="basic" class="form-control red_border avoid_special_char">
                                                                            <span class="text-danger error-text" id="basic_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>DA</label>
                                                                            <input type="text" name="da" id="da" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Other Allowance</label>
                                                                            <input type="text" name="other_allowance" id="other_allowance" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>HRA</label>
                                                                            <input type="text" name="hra" id="hra" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>ADDL HRA</label>
                                                                            <input type="text" name="addl_hra" id="addl_hra" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Conveyance</label>
                                                                            <input type="text" name="conveyance" id="conveyance" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>LTA</label>
                                                                            <input type="text" name="lta" id="lta" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Medical</label>
                                                                            <input type="text" name="medical" id="medical" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>SPL Allowance</label>
                                                                            <input type="text" name="spl_allowance" id="spl_allowance" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>NPS</label>
                                                                            <input type="text" name="nps" id="nps" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Super Annuation</label>
                                                                            <input type="text" name="super_annuation" id="super_annuation" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Sales Incentive</label>
                                                                            <input type="text" name="sales_incentive" id="sales_incentive" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Fixed Vehicle Allowance</label>
                                                                            <input type="text" name="fva" id="fva" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Gross</label>
                                                                            <input type="text" name="gross" id="gross" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Fixed Stipend</label>
                                                                            <input type="text" name="fixed_stipend" id="fixed_stipend" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="hold_salary" role="tabpanel"
                                                            aria-labelledby="hold_tab">
                                                            <div class="card-body">
                                                                <div class="row" style="margin-bottom:-25px">
                                                                    <div class="col-md-5">
                                                                        <label>Hold Salary</label>

                                                                        <div class="form-group input-group netpay_date" style="background: #fff; cursor: pointer;">
                                                                            <input type="text" class="form-control" id="month_year" name="month_year"
                                                                                value="{{ \Carbon\Carbon::now()->isoFormat('MMMM, YYYY') }}">
                                                                            <div class="input-group-append">
                                                                                <button type="button" class="btn btn-primary" id="add_hold"><i class="fa fa-plus"></i>&nbsp;Add</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3 hs_title" hidden="true">
                                                                    <div class="col-md-5">Net Pay</div>
                                                                    <div class="col-md-5">Amount ₹</div>
                                                                </div>
                                                                <div class="show_hold"></div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="leave_bal" role="tabpanel"
                                                            aria-labelledby="profile-tab2">
                                                            <div class="card-body">
                                                                <label>Leave Balance</label>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Leave Balance CL<span class="text-danger">*</span></label>
                                                                            <input type="text" name="leave_balance_cl" id="leave_balance_cl" class="form-control red_border">
                                                                            <span class="text-danger error-text" id="leave_balance_cl_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Leave Balance PL<span class="text-danger">*</span></label>
                                                                            <input type="text" name="leave_balance_pl" id="leave_balance_pl" class="form-control red_border">
                                                                            <span class="text-danger error-text" id="leave_balance_pl_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Leave Balance SL<span class="text-danger">*</span></label>
                                                                            <input type="text" name="leave_balance_sl" id="leave_balance_sl" class="form-control red_border">
                                                                            <span class="text-danger error-text" id="leave_balance_sl_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Is Probation Completed<span class="text-danger">*</span></label>
                                                                            <input type="text" name="is_probation_completed" id="is_probation_completed" class="form-control red_border">
                                                                            <span class="text-danger error-text" id="is_probation_completed_error"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="exit_reg_run" role="tabpanel"
                                                            aria-labelledby="exit_tab">
                                                            <div class="card-body">
                                                                <label>F&F Statement</label>
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label>Document<b class="text-danger">*</b></label>
                                                                            <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv" onchange="checkextension(this)"
                                                                                name="exit_register_run_file[]" id="exit_register_run_file"class="form-control red_border" multiple>
                                                                            <span class="text-danger error-text" id="exit_register_run_file_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <label>Remark</label>
                                                                            <textarea class="form-control" autocomplete="off" name="exit_register_run_remark" id="exit_register_run_remark"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label>Manual Computation</label>
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label>Document<b class="text-danger"></b></label>
                                                                            <input type="file" accept=".xls,.xlsx,.csv" onchange="checkextension(this)"
                                                                                name="manual_computation_file[]" id="manual_computation_file" class="form-control red_border" multiple>
                                                                            <span class="text-danger error-text" id="manual_computation_file_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <label>Remark</label>
                                                                            <textarea class="form-control" autocomplete="off" name="manual_computation_remark" id="manual_computation_remark"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="s_g_7_field" class="pop_f_f_inp_div_set">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>F&F Statement<span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <!-- <div id="pdf_show"></div> -->
                                                                <input type="hidden" id="file_path" name="file_path">
                                                                <div style="display:none;" id="convertedPdfShown"><a id="pdfLink" href="#" target="_blank">F&F Statement PDF</a></div>
                                                                <input type="file" style="display:none;" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv"
                                                                    onchange="checkextension(this)" name="f_f_statement_file"
                                                                    id="f_f_statement_file" class="form-control red_border">
                                                                <span class="text-danger error-text "
                                                                    id="f_f_statement_file_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Service Letters<span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv"
                                                                    onchange="checkextension(this)" name="service_letters_file"
                                                                    id="service_letters_file" class="form-control red_border">
                                                                <span class="text-danger error-text "
                                                                    id="service_letters_file_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Relieving Letters<span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <input type="file" accept="image/*,.pdf,.doc,.xls,.xlsx,.csv"
                                                                    onchange="checkextension(this)" name="relieving_letters_file"
                                                                    id="relieving_letters_file" class="form-control red_border">
                                                                <span class="text-danger error-text "
                                                                    id="relieving_letters_file_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  " id="revert_tab" role="tabpanel"
                                            aria-labelledby="contact_tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Stage Gate To<span class="text-danger">*</span></label>
                                                        <select name="sgt_revert"
                                                            id="sgt_revert"class="form-control red_border">
                                                            <option value="">Choose</option>
                                                        </select>
                                                        <span class="text-danger error-text sgt_revert_error"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Revert Remark<span class="text-danger">*</span></label>
                                                        <textarea class="form-control red_border" autocomplete="off" name="revert_remark" id="revert_remark"></textarea>
                                                        <span
                                                            class="text-danger error-text revert_remark_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                    
                                    <button class="btn btn-primary mr-1" id="real_submit" type="submit"
                                        style="display:none;">real submit</button>
                                </form>
                                <div id="form_resp" style="display:none;"></div>
                                <span id="show_er" class="text-danger"></span>
                            </div>
                            <div class="card-footer mb-4 mt-5" id="footer_id">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" style="cursor:pointer;" name="confirm_submit"
                                        class="" tabindex="3" id="confirm_submit">
                                    <label style="cursor:pointer;font-size:15px;"
                                        for="confirm_submit">Confirm...!</label>
                                </div>
                                <span class="text-danger float-right" id="ff_sts"></span>
                                <button class="btn btn-primary mr-1 float-right" id="f_f_pop_submit"
                                    type="button" style="margin-top:0.3rem" >Submit</button>
                                <button class="btn btn-primary mr-1 float-right" id="revert"
                                    type="button">Revert</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="tab4" role="tabpanel" aria-labelledby="profile1-tab">
                        <div class="card-body">
                            <ul class="nav nav-tabs ml-4" id="myTab4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" onclick="fresh_tab_click();" id="home-tab2"
                                        data-toggle="tab" href="#fresh_c_p_tab" role="tab" aria-controls="home"
                                        aria-selected="true"><i
                                            class="fas fa-exclamation-triangle"></i>&nbsp;Fresh</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="inprogress_tab_click2();" id="profile-tab2"
                                        data-toggle="tab" href="#inprogress_c_p_tab" role="tab"
                                        aria-controls="profile" aria-selected="false"><i
                                            class="far fa-edit"></i>&nbsp;In
                                        progress</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="completed_tab_click2();" id="contact-tab1"
                                        data-toggle="tab" href="#completed_c_p_tab" role="tab"
                                        aria-controls="contact" aria-selected="false"><i
                                            class="fas fa-check"></i>&nbsp;Completed</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="fresh_c_p_tab" role="tabpanel"
                                    aria-labelledby="home-tab2">
                                    <div class="card-body 
                                        ">
                                        <div class="">
                                            <table class="table table-striped data-table" id="fresh_c_p_tbl">
                                                <thead>
                                                    <th>Check Points</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer ">
                                        <button class="btn btn-outline-success save float-right save_f_and_f_doc"
                                            id="save_f_and_f_doc2" type="button"><span><i
                                                    class="fa fa-save"></i></span>&nbsp;Save</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="inprogress_c_p_tab" role="tabpanel"
                                    aria-labelledby="profile-tab2">
                                    <div class="card-body ">
                                        <div class="">
                                            <table class="table table-striped data-table" id="inprogress_c_p_tbl">
                                                <thead>
                                                    <th>Check Points</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer ">
                                        <button class="btn btn-outline-success save float-right save_f_and_f_doc"
                                            id="save_f_and_f_doc" type="button"><span><i
                                                    class="fa fa-save"></i></span>&nbsp;Update</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="completed_c_p_tab" role="tabpanel"
                                    aria-labelledby="contact-tab1">
                                    <div class="card-body ">
                                        <div class="">
                                            <table class="table table-striped data-table" id="completed_c_p_tbl">
                                                <thead>
                                                    <th>Check Points</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="tabs4" role="tabpanel" aria-labelledby="profiles-tab">
                        <h5 style="margin-bottom:-10px">
                            Submitted By - HRSS
                        </h5>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="sg1_view" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="sg_1_details" data-toggle="tab"
                                        href="#sg1_view13" role="tab" aria-controls="sg_1_details"
                                        aria-selected="true">Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sg_1_recoery" data-toggle="tab" href="#sg1_view23"
                                        role="tab" aria-controls="sg_1_recoery"
                                        aria-selected="false">Recovery</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link " id="f_f_tracker_view-tab" data-toggle="tab"
                                        href="#f_f_tracker_view" role="tab" value="f_f_computation"
                                        aria-controls="profiles3" aria-selected="false">No Dues</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="sg_1_view">
                                <div class="tab-pane fade show active" id="sg1_view13" role="tabpanel"
                                    aria-labelledby="sg_1_details">
                                    <div class="card-body">
                                        <h6 class="mb-2 mt-2">Clearance Date</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Supervisor</label><br>
                                                <span class="text-dark supervisor_clearance_view"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Commercial / Admin</label><br>
                                                <span class="text-dark c_admin_clearance_view"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Finance</label><br>
                                                <span class="text-dark finanace_clearance_view"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>IT</label><br>
                                                <span class="text-dark it_clearance_view"></span>
                                            </div>
                                        </div>
                                        <h6 class="mt-4 mb-2">Details</h6>
                                        <div class="row">
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Grade Set</label><br>
                                                <span class="text-dark grade_set_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Grade</label><br>
                                                <span class="text-dark grade_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Department</label><br>
                                                <span class="text-dark department_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Work Location</label><br>
                                                <span class="text-dark work_location_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Supervisor Name</label><br>
                                                <span class="text-dark supervisor_name_view"
                                                    id="supervisor_name_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Reviewer Name</label><br>
                                                <span class="text-dark reviewer_name_view"
                                                    id="reviewer_name_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Headquarters</label><br>
                                                <span class="text-dark headquarters_view"
                                                    id="headquarters_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>HRBP Name</label><br>
                                                <span class="text-dark hrbp_name_view" id="hrbp_name_view"></span>
                                            </div>
                                             <div class="col-md-3 mt-3 mb-3">
                                                <label>Fixed Stipend</label><br>
                                                <span class="text-dark fixed_stipend" id="fixed_stipend"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Last Working Date</label><br>
                                                <span class="text-dark last_working_date_view"
                                                    id="last_working_date_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Seperation Date</label><br>
                                                <span class="text-dark seperation_date_view"
                                                    id="seperation_date_view"></span>
                                            </div>

                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Date Of Joining</label><br>
                                                <span class="text-dark date_of_joining_view"
                                                    id="date_of_joining_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Date Of Resignation</label><br>
                                                <span class="text-dark date_of_resignation_view"
                                                    id="date_of_resignation_view"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade sg1_view2" id="sg1_view23" role="tabpanel"
                                    aria-labelledby="sg_1_recoery">
                                    <div class="row label_div" hidden="true">
                                        <div class="col-md-4">
                                            <b>Recovery</b>
                                        </div>
                                        <div class="col-md-4">
                                            <b>Value</b>
                                        </div>
                                        <div class="col-md-4">
                                            <b>Remark</b>
                                        </div>
                                    </div>
                                    <div class="hide_all "></div>
                                </div>

                                <div class="tab-pane fade" id="f_f_tracker_view" role="tabpanel"
                                    aria-labelledby="profiles3-tab">
                                    <div class="card-body">
                                        <div class="card-body">
                                            <h6 class="mt-2 mb-2">No Dues</h6>
                                            <div class="table-responsive">
                                                <table id="f&fcomputation1" class="table table-bordered"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Document</th>
                                                            <th>Remark</th>
                                                            <th>Created At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="sg_doc_view">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="tabs5" role="tabpanel" aria-labelledby="profiles1-tab">
                        <h5 style="margin-bottom:-10px">
                            Submitted By - Payroll HR
                        </h5>
                        <div class="card-body">
                            <ul class="nav nav-tabs  mt-3" id="mytabss" role="tablist">
                                @if (session('user_type') != 'F_F_HR')
                                    <li class="nav-item">
                                        <a class="nav-link" id="ctc_master_tab" data-toggle="tab"
                                            href="#ctc_mas" role="tab" aria-controls="ctcmas"
                                            aria-selected="true">CTC Master</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link hold_salary_tab" data-toggle="tab" href="#hold_sal"
                                            role="tab" aria-controls="holdsal" aria-selected="true">Hold
                                            Salary</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a @if (session('user_type') != 'F_F_HR') class="nav-link"
                                    @else
                                    class="nav-link active" @endif
                                        id="profiles2_tab" data-toggle="tab" href="#tabs6" role="tab"
                                        aria-controls="profiles2" aria-selected="false">Leave
                                        Balance</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="exitRegViewTab" data-toggle="tab"
                                        href="#exit_reg_view" role="tab" aria-controls="exit_reg_view"
                                        value="exit_reg_view" aria-selected="true">F&F Statement</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="sg-tabs2">
                                <div @if (session('user_type') != 'F_F_HR') class="tab-pane fade show active "
                                @else
                                class="tab-pane fade" @endif
                                    id="ctc_mas" role="tabpanel" aria-labelledby="ctcmas">
                                    <div class="card-body">
                                        <h6 class=" mb-2">CTC Master</h6>
                                        <div class="row">
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Basic</label><br>
                                                <span class="text-dark basic_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>DA</label><br>
                                                <span class="text-dark da_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Other Allowance</label><br>
                                                <span class="text-dark other_allowance_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>HRA</label><br>
                                                <span class="text-dark hra_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>ADDL HRA</label><br>
                                                <span class="text-dark addl_hra_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Conveyance</label><br>
                                                <span class="text-dark conveyance_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>LTA</label><br>
                                                <span class="text-dark lta_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Medical</label><br>
                                                <span class="text-dark medical_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>SPL Allowance</label><br>
                                                <span class="text-dark spl_allowance_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>NPS</label><br>
                                                <span class="text-dark nps_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Super Annuation</label><br>
                                                <span class="text-dark super_annuation_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Sales Incentive</label><br>
                                                <span class="text-dark sales_incentive_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Fixed Vehicle Allowance</label><br>
                                                <span class="text-dark fva_view"></span>
                                            </div>
                                            <div class="col-md-3 mt-3 mb-3">
                                                <label>Gross</label><br>
                                                <span class="text-dark gross_view"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="hold_sal" role="tabpanel"
                                    aria-labelledby="holdsal">
                                    <div class="card-body">
                                        <div class="row show_hold_salary">

                                        </div>
                                    </div>
                                </div>

                                <div @if (session('user_type') != 'F_F_HR') class="tab-pane fade"
                                @else
                                class="tab-pane fade  show active" @endif
                                    id="tabs6" role="tabpanel" aria-labelledby="profiles2-tab">
                                    <div class="card-body">
                                        <h6 class="mb-2">Leave Balance</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Leave Balance CL</label><br>
                                                <span class="text-dark leave_balance_cl_view"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Leave Balance PL</label><br>
                                                <span class="text-dark leave_balance_pl_view"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Leave Balance SL</label><br>
                                                <span class="text-dark leave_balance_sl_view"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Is Probation Completed</label><br>
                                                <span class="text-dark is_probation_completed_view"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="exit_reg_view" role="tabpanel"
                                    aria-labelledby="exit_reg_view">
                                    <div class="card-body">
                                        <h6 class="mt-2 mb-2">F&F Statement</h6>
                                        <div class="table-responsive">
                                            <table id="exitRegisterView" class="table " cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Document</th>
                                                        <th>Remark</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs11" role="tabpanel" aria-labelledby="profile7">
                        <h5 style="margin-bottom:-10px" id="qcheader">
                            Submitted By - Payroll QC
                        </h5>
                        <div class="card-body">
                            <h6 class="mt-2 mb-2">Quality Check</h6>
                            <div class="table-responsive">
                                <table id="qualityCheckView" class="table table-bordered" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Document</th>
                                            <th>Remark</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="stage4FinanceTab" role="tabpanel"
                        aria-labelledby="stage4Finance">
                        <h5 style="margin-bottom:-10px">
                            Submitted By - Payroll Finance
                        </h5>
                        <div class="card-body">
                            {{-- <h6 class="mt-2 mb-2">Finance</h6> --}}
                            <div class="table-responsive">
                                <table id="financeView" class="table table-bordered" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Sap Doc No</th>
                                            <th>Posting Date</th>
                                            <th>F&F Payable / Recoverable</th>
                                            <th>F&F Amount</th>
                                            <th>F&F Accounting Doc</th>
                                            <th>Remark</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="stage5FinanceTab" role="tabpanel"
                        aria-labelledby="stage5Finance">
                        <h5 style="margin-bottom:-10px">
                            Submitted By - Payroll Finance
                        </h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="financeView2" class="table table-bordered" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Document</th>
                                            <th>Amount</th>
                                            <th>Remark</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="history_tab_div" role="tabpanel" aria-labelledby="histories">
                        <ul class="nav nav-tabs" id="historyTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="revert_remark_tab" data-toggle="tab"
                                    href="#revert_remark_tab_view" role="tab"
                                    aria-controls="revert_remark_tab" aria-selected="true">&nbsp;Revert
                                    Remark</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " id="stage_2_prhr" data-toggle="tab"
                                    href="#stage_2_prhr_div" role="tab" aria-controls="stage_2_prhr"
                                    aria-selected="true">Stage 2 - Payroll
                                    HR</a>
                            </li>
                            @if (session('user_type') == 'Payroll_Finance' ||
                                    session('user_type') == 'Payroll_QC' ||
                                    session('user_type') == 'F_F_HR')
                                <li class="nav-item">
                                    <a class="nav-link" id="stage_3_qc" data-toggle="tab" href="#stage_3_qc_div"
                                        role="tab" aria-controls="stage_3_qc" aria-selected="true">Stage 3 -
                                        Payroll QC</a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="historyTabContent">
                            <div class="tab-pane fade show active" id="revert_remark_tab_view" role="tabpanel"
                                aria-labelledby="revert_remark_tab_view">
                                <div class="card-body">
                                    <table id="revert_remark_table1" class="table table-bordered" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Revert From SG</th>
                                                <th>Revert To SG</th>
                                                <th>Revert Remark</th>
                                                <th>Revert By</th>
                                                <th>Reverted At</th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="stage_2_prhr_div" role="tabpanel"
                                aria-labelledby="stage_2_prhr">
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="historyTabViewstage2" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="ctcMasterTabViewStage2"
                                                data-toggle="tab" data-toggle="tab" href="#ctcMasterTabView"
                                                role="tab" aria-controls="ctcMasterTabView"
                                                aria-selected="true">&nbsp;CTC
                                                Master</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="holdSalaryTabViewStage2" data-toggle="tab"
                                                data-toggle="tab" href="#holdSalaryTabView" role="tab"
                                                aria-controls="holdSalaryTabView" aria-selected="true">&nbsp;Hold
                                                Salary</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="leave_balance_history_tab" data-toggle="tab"
                                                href="#leave_balance_history" role="tab"
                                                aria-controls="leave_balance_history" aria-selected="false">Leave
                                                Balance</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="exitRegisterRunTabViewStage2"
                                                data-toggle="tab" data-toggle="tab" href="#exitRegisterRunTabView"
                                                role="tab" aria-controls="exitRegisterRunTabView"
                                                aria-selected="true">&nbsp;F&F Statement</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="historyTabContentViewStage2">
                                        <div class="tab-pane fade show active" id="ctcMasterTabView"
                                            role="tabpanel" aria-labelledby="ctcMasterTabView">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="ctcMasterTable" class="table table-bordered"
                                                        cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Basic</th>
                                                                <th>DA</th>
                                                                <th>Other Allowance</th>
                                                                <th>HRA</th>
                                                                <th>ADDL HRA</th>
                                                                <th>Conveyance</th>
                                                                <th>LTA</th>
                                                                <th>MEdical</th>
                                                                <th>Special Allowance</th>
                                                                <th>NPS</th>
                                                                <th>Super Annuation</th>
                                                                <th>Sales Incentive</th>
                                                                <th>Fixed Vehicle Allowance</th>
                                                                <th>Gross</th>
                                                                <th>Created At</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="holdSalaryTabView" role="tabpanel"
                                            aria-labelledby="holdSalaryTabView">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="holdSalaryTable" class="table table-bordered"
                                                        cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Net Pay</th>
                                                                <th>Amount</th>
                                                                <th>Created At</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="leave_balance_history" role="tabpanel"
                                            aria-labelledby="leave_balance_history_view">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="leaveBalanceHistory" class="table table-bordered"
                                                        cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Leave Balance CL</th>
                                                                <th>Leave Balance SL</th>
                                                                <th>Leave Balance PL</th>
                                                                <th>Is Probation Completed</th>
                                                                <th>Created At</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="exitRegisterRunTabView" role="tabpanel"
                                            aria-labelledby="exitRegisterRunTabView">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="exitRegisterHistory" class="table table-bordered"
                                                        cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Document</th>
                                                                <th>Remark</th>
                                                                <th>Created At</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="stage_3_qc_div" role="tabpanel"
                                aria-labelledby="stage_3_qc">
                                <div class="card-body">
                                    <table id="qualityCheckHistory" class="table " cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Document</th>
                                                <th>Remark</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
