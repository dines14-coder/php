<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();

Route::get('/', 'PageController@dashboard')->name('dashboard');

Route::get('login', 'RegisterController@login')->name('login');
Route::get('register', 'RegisterController@register')->name('register');
Route::post('register_process', 'RegisterController@register_process');
Route::post('otp_submit', 'RegisterController@otp_submit');

Route::post('f_p_submit', 'RegisterController@f_p_submit');
Route::get('forgot_pass', 'RegisterController@forgot_pass')->name('forgot_pass');
Route::post('password_change_submit', 'RegisterController@password_change_submit');

Route::get('password_update_landing', 'PageController@password_update_landing')->name('password_update_landing');
Route::Post('check_password', 'PageController@check_password')->name('check_password');
Route::post('update_pass', 'PageController@update_pass')->name('update_pass');

Route::get('admin_password_update_landing', 'AdminController\PageController@password_update_landing')->name('admin_password_update_landing');
Route::Post('admin_check_password', 'AdminController\PageController@check_password');
Route::Post('admin_update_pass', 'AdminController\PageController@update_pass');

Route::post('send_reg_mail', 'RegisterController@send_reg_mail');

Route::post('login_check', 'RegisterController@login_check');
Route::post('theme_change', 'RegisterController@theme_change');
Route::post('theme_sidebar_change', 'RegisterController@theme_sidebar_change');
Route::post('check_theme_clr', 'RegisterController@check_theme_clr');
Route::post('check_theme_sidebar_clr', 'RegisterController@check_theme_sidebar_clr');

Route::get('create_query_landing', 'QueryController@create_query_landing')->name('create_query_landing');
Route::get('query_status_landing', 'QueryController@query_status_landing')->name('query_status_landing');

Route::get('updated_query', 'QueryController@updated_query')->name('updated_query');

Route::post('query_form_submit', 'QueryController@query_form_submit');
Route::post('check_valid_doc_upl', 'QueryController@check_valid_doc_upl');
Route::post('get_all_emp_query', 'QueryController@get_all_emp_query');
Route::post('doc_updated_detail_emp', 'QueryController@doc_updated_detail_emp');

Route::get('my_document_landing', 'DocumentController@my_document_landing')->name('my_document_landing');
Route::post('get_emp_all_doc_data', 'DocumentController@get_emp_all_doc_data');
Route::post('hr_updated_detail_emp_view', 'DocumentController@hr_updated_detail_emp_view');

Route::get('logout', 'RegisterController@logout')->name('logout');
Route::get('p_s_query_manage_landing', 'S_AdminController\QueryController@p_s_query_manage_landing')->name('p_s_query_manage_landing');
Route::post('get_p_s_admin_query_datatable', 'S_AdminController\QueryController@get_p_s_admin_query_datatable');
Route::get('p_s_document_manage_landing', 'S_AdminController\DocumentController@p_s_document_manage_landing')->name('p_s_document_manage_landing');
Route::post('get_p_s_admin_alumni_datatable', 'S_AdminController\DocumentController@get_p_s_admin_alumni_datatable');

// s admin controll
Route::get('s_admin/dashboard', 'S_AdminController\PageController@dashboard')->name('s_admin.dashboard');
Route::get('s_query_manage_landing', 'S_AdminController\QueryController@query_manage_landing')->name('s_query_manage_landing');
Route::post('get_s_admin_query_datatable', 'S_AdminController\QueryController@get_admin_query_datatable');
Route::post('adm_get_emp_sel_box', 'S_AdminController\PageController@adm_get_emp_sel_box');
Route::get('s_document_manage_landing', 'S_AdminController\DocumentController@document_manage_landing')->name('s_document_manage_landing');

Route::post('get_s_admin_alumni_datatable', 'S_AdminController\DocumentController@get_admin_alumni_datatable');

// daily report
Route::get('s_daily_report', 'S_AdminController\QueryController@s_daily_report')->name('s_daily_report');
Route::post('get_s_admin_daily_report', 'S_AdminController\QueryController@get_admin_d_report_datatable');

// daily report
Route::get('p_s_daily_report', 'S_AdminController\QueryController@p_s_daily_report')->name('p_s_daily_report');
Route::post('get_p_s_admin_daily_report', 'S_AdminController\QueryController@get_p_s_admin_d_report_datatable');

// primary super admin
Route::get('p_s_admin/dashboard', 'S_AdminController\PageController@p_s_dashboard')->name('p_s_admin.dashboard');

// end primary super admin

// HR controoler
Route::get('login/admin', 'RegisterController@adminlogin')->name('Admin.login');
Route::post('admin_login_check', 'RegisterController@admin_login_check');
Route::get('admin/dashboard', 'AdminController\PageController@dashboard')->name('admin.dashboard');

Route::get('F_and_F_document/form', 'F_and_F_document\F_and_F_document@index')->name('F_and_F_document.form');
Route::post('get_c_p_datatable', 'F_and_F_document\F_and_F_document@get_c_p_datatable');
Route::post('add_f_and_f_document', 'F_and_F_document\F_and_F_document@add_f_and_f_document');
Route::post('f_and_f_document_popup', 'AdminController\AmbassadorController@f_and_f_document_popup');
Route::post('update_amb_form', 'AdminController\AmbassadorController@update_amb_form'); //i_put
Route::post('reset_password', 'AdminController\AmbassadorController@reset_password'); //i_put

Route::get('query_manage_landing', 'AdminController\QueryController@query_manage_landing')->name('query_manage_landing');
Route::post('get_admin_query_datatable', 'AdminController\QueryController@get_admin_query_datatable');

Route::post('employee_detail', 'AdminController\QueryController@get_employee_detail');
Route::post('update_query_status', 'AdminController\QueryController@update_query_status');
//reassign
Route::post('reassign_query_status', 'AdminController\QueryController@reassign_query_status');
Route::post('update_reassign_form', 'AdminController\QueryController@update_reassign_form');
Route::post('doc_upload_admin_submit', 'AdminController\QueryController@doc_upload_admin_submit');
Route::post('doc_updated_detail', 'AdminController\QueryController@doc_updated_detail');
Route::post('send_qry_stats_mail', 'AdminController\QueryController@send_qry_stats_mail');

Route::post('downloadPDF', 'AdminController\AmbassadorController@downloadPDF');

Route::get('alumni_manage_landing', 'AdminController\AmbassadorController@alumni_manage_landing')->name('alumni_manage_landing');
Route::post('add_alumni_submit', 'AdminController\AmbassadorController@add_alumni_submit')->middleware('employee.cleanup');
Route::post('amb_bulk_upl_submit', 'AdminController\AmbassadorController@amb_bulk_upl_submit');
Route::get('view_alumni_landing', 'AdminController\AmbassadorController@view_alumni_landing')->name('view_alumni_landing');
Route::post('get_all_alumni_datatable', 'AdminController\AmbassadorController@get_all_alumni_datatable');
Route::post('form16_bulk_upload', 'AdminController\AmbassadorController@form16_bulk_upload');
Route::get('test_pan_extraction', 'AdminController\AmbassadorController@testPanExtraction');

Route::get('f_f_tracker_landing', 'AdminController\F_F_Tracker_Controller@f_f_tracker_landing')->name('f_f_tracker_landing');
Route::post('get_f_f_tracker_data', 'AdminController\F_F_Tracker_Controller@get_f_f_tracker_data');
Route::post('save_f_f_tracker_inp', 'AdminController\F_F_Tracker_Controller@save_f_f_tracker_inp');
Route::post('fetch_tracker_details', 'AdminController\F_F_Tracker_Controller@fetch_tracker_details');
Route::post('save_revert', 'AdminController\F_F_Tracker_Controller@save_revert');
Route::get('get_revert_remarks', 'AdminController\F_F_Tracker_Controller@get_revert_remarks');
Route::post('get_f_f_tracker_files', 'AdminController\F_F_Tracker_Controller@get_f_f_tracker_files');
Route::post('get_check_points', 'AdminController\F_F_Tracker_Controller@get_check_points');
Route::post('get_recovery_val', 'AdminController\F_F_Tracker_Controller@get_recovery_val');
Route::post('delete_recoveries', 'AdminController\F_F_Tracker_Controller@delete_recoveries');
Route::post('get_hold_salary', 'AdminController\F_F_Tracker_Controller@get_hold_salary');
Route::post('get_notification', 'AdminController\F_F_Tracker_Controller@get_notification');
Route::post('notify_viewed_update', 'AdminController\F_F_Tracker_Controller@notify_viewed_update');
Route::post('check_already_exist_netpay', 'AdminController\F_F_Tracker_Controller@check_already_exist_netpay');
Route::get('view_reg_alumni_landing', 'AdminController\AmbassadorController@view_reg_alumni_landing')->name('view_reg_alumni_landing');
Route::post('get_all_reg_alumni_datatable', 'AdminController\AmbassadorController@get_all_reg_alumni_datatable');
Route::post('get_all_declined_alumni_datatable', 'AdminController\AmbassadorController@get_all_declined_alumni_datatable');
Route::post('update_reg_alumni', 'AdminController\AmbassadorController@update_reg_alumni');
Route::post('send_emp_status_mail', 'AdminController\AmbassadorController@send_emp_status_mail');
Route::get('document_manage_landing', 'AdminController\DocumentController@document_manage_landing')->name('document_manage_landing');
Route::post('get_admin_alumni_datatable', 'AdminController\DocumentController@get_admin_alumni_datatable');
Route::post('alumni_doc_upload_admin_submit', 'AdminController\DocumentController@alumni_doc_upload_admin_submit');
Route::post('alumni_doc_updated_detail', 'AdminController\DocumentController@alumni_doc_updated_detail');
Route::post('send_doc_mail', 'AdminController\DocumentController@send_doc_mail');

Route::get('query_doc_update', 'AlumniFunctionalityController@query_doc_update')->name('query_doc_update');
Route::post('ck_alumni_with_lwd', 'AlumniFunctionalityController@ck_alumni_with_lwd');
Route::get('upload_form16_doc', 'AlumniFunctionalityController@upload_form16_doc');
Route::get('bulk_epm_upload', 'AlumniFunctionalityController@bulk_epm_upload');

Route::get("f_f_reports", "AdminController\F_F_Tracker_Controller@f_f_reports")->name('f_f_reports');
Route::post("get_pending_f_f_data", "AdminController\F_F_Tracker_Controller@get_pending_f_f_data");
Route::post("get_query_report", "AdminController\F_F_Tracker_Controller@get_query_report");
Route::post("f_f_transaction_report", "AdminController\F_F_Tracker_Controller@f_f_transaction_report");

// Stage 1 View
Route::post("getRevertRemark", "AdminController\F_F_Tracker_Controller@getRevertRemark");
// Stage 2 View
Route::post("getCTCMasterData", "AdminController\F_F_Tracker_Controller@getCTCMasterData");
Route::post("getHoldSalaryData", "AdminController\F_F_Tracker_Controller@getHoldSalaryData");
Route::post("getTrackerRegisterRunFiles", "AdminController\F_F_Tracker_Controller@getTrackerRegisterRunFiles");
Route::post("getHoldSalaryDataEdit", "AdminController\F_F_Tracker_Controller@getHoldSalaryDataEdit");
Route::post("deleteHoldSalary", "AdminController\F_F_Tracker_Controller@deleteHoldSalary");
// Stage 3 View
Route::post("getFinanceDetails", "AdminController\F_F_Tracker_Controller@getFinanceDetails");
Route::post("getLeaveBalanceHistory", "AdminController\F_F_Tracker_Controller@getLeaveBalanceHistory");
Route::post("checkChecklistStatus", "AdminController\F_F_Tracker_Controller@checkChecklistStatus");
Route::post("getTrackerRegisterRunFilesFinance", "AdminController\F_F_Tracker_Controller@getTrackerRegisterRunFilesFinance");
Route::get("revertdetails", "AdminController\F_F_Tracker_Controller@getRevertdetails")->name("revert");
Route::post("getRevertdetailsData", "AdminController\F_F_Tracker_Controller@getRevertdetailsData");
Route::get("qc_mis", "AdminController\F_F_Tracker_Controller@getQc_mis")->name("qc_mis");
Route::post("getQcMisReport", "AdminController\F_F_Tracker_Controller@getQcMisReport");

Route::get("report_page","ReportController@report_page")->name('report_page');
Route::post('view_report', 'ReportController@view_report');
Route::get("report_page1","ReportController@report_page1")->name('report_page1');

//bank details
Route::get("account","ReportController@account")->name('account');
Route::get("accountdetails","ReportController@accountdetails")->name('accountdetails');
Route::post("approve","ReportController@update_status")->name('update_status');
Route::post("reject","ReportController@updated_status")->name('updated_status');
Route::post('bank_account_form', 'QueryController@bank_account_form');
Route::get('bank_status', 'QueryController@bank_status');
Route::get('bank_account_change', 'QueryController@bank_account_change')->name('bank_account_change');
Route::get("get_fftrack_details", "AdminController\F_F_Tracker_Controller@get_fftrack_details");

// Get existing employees for alumni dropdown filtering

