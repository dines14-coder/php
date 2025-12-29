<?php

namespace App\Repositories;

use App\Models\query_document_tbl;
use App\Models\query_tbl;
use App\Models\reassign_tbls;
// use App\Models\bank_details;
// use App\Models\Bank_account;
use App\Models\Bank_detail_account;
use DB;

class QueryRepository implements IQueryRepository
{

    public function QueryEntry($credentials)
    {
        $querytbl = new query_tbl();
        $querytbl->ticket_id = 'T_' . str_pad(($querytbl->max('id') + 1), 9, '0', STR_PAD_LEFT);
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->document = $credentials['document'];
        $querytbl->remark = $credentials['remark'];
        $querytbl->updated_by = $credentials['updated_by'];
        $querytbl->status = $credentials['status'];
        $querytbl->save();
        return $querytbl->ticket_id;
    }

    // public function bank_account($doc_bank){
    //     //  echo "<pre>"; print_r($doc_bank);exit;
    // //    dd("hi");
    // //    var_dump($doc_bank);
    //     $banktbl = new bank_details();
    //     $banktbl->emp_id = $doc_bank['emp_id'];
    //     $banktbl->status = $doc_bank['status'];
    //     $banktbl->cheque = $doc_bank['cheque'];
    //     $banktbl->passbook = $doc_bank['passbook'];
    //     $banktbl->save();
    //     return $banktbl;
    // }
    public function bank_account($doc_bank){
        //  echo "<pre>"; print_r($doc_bank);exit;
    //    dd("hi");
    //    var_dump($doc_bank);
        $banktb = new Bank_detail_account();
        //  echo "<pre>"; print_r($banktb);exit;
        $banktb->emp_id = $doc_bank['emp_id'];
        $banktb->status = $doc_bank['action'];
        $banktb->cheque = $doc_bank['cheque'];
        $banktb->passbook = $doc_bank['passbook'];
        $banktb->save();
        return $banktb;
    }

    public function bank_status()
    {
        $emp_id = auth()->user()->emp_id;
        // $user = auth()->user();
        // $emp_id=$user->emp_id;
        $latest_bank_detail = Bank_detail_account::where('emp_id',$emp_id)->orderBy('id','desc')->first();
        $details = [];
    // print_r($latest_bank_detail);die;
        if ($latest_bank_detail) {
            $details = [
                'cheque' => $latest_bank_detail->cheque,
                'passbook' => $latest_bank_detail->passbook,
                'status' => $latest_bank_detail->status,
            ];
        }else{
            $details = [
                'cheque' =>'',
                'passbook' => '',
                'status' => ''
            ];
        }
    
        return $details;
    }
    

    public function QueryDocEntry($credentials)
    {
        $query_details = new query_document_tbl();
        $query_details->ticket_id = $credentials['ticket_id'];
        $query_details->status = $credentials['status'];
        $query_details->document = $credentials['document'];
        $query_details->save();
    }

    public function get_ticket_count($credentials)
    {
        $record = new query_document_tbl();
        $record = $record->where('ticket_id', '=', $credentials['ticket_id']);
        $record = $record->count();
        return $record;
    }

    public function get_status_count($credentials)
    {
        $record = new query_document_tbl();
        $record = $record->where('ticket_id', '=', $credentials['ticket_id']);
        $record = $record->where('status', '=', $credentials['sts']);
        $record = $record->count();
        return $record;
    }

    public function update_overall_status($credentials)
    {
        $query_tbl = new query_tbl();
        $query_tbl = $query_tbl->where('ticket_id', '=', $credentials['ticket_id']);
        $query_tbl->update(['status' => "Completed"]);
    }

    public function QueryDocumentEntry($credentials)
    {
        query_document_tbl::updateOrCreate(
            [
                'ticket_id' => $credentials['ticket_id'],
                'document' => $credentials['document']
            ],
            [
                'file_name' => $credentials['file_name'],
                'remark' => $credentials['remark'],
                'status' => "Completed",
                'updated_by' => session()->get('emp_id') ?? '',
            ]
        );
    }

    public function GetQueryDocument($credentials)
    {
        $update_row = new query_document_tbl();
        $update_row = $update_row->whereIn('document', $credentials['doc']);
        $update_row = $update_row->where('status', '=', "Approved");
        $update_row = $update_row->where('ticket_id', '=', $credentials['ticket_id']);
        $update_row = $update_row->get();
    }

    public function get_query_u_tic_id($ticket_id)
    {
        $querytbl = new query_tbl();
        return $querytbl::where('ticket_id', $ticket_id)
            ->get();
    }

    public function get_all_emp_query($emp_id)
    {
        $querytbl = new query_tbl();
        return $querytbl::where('emp_id', $emp_id)
            ->orderBy('id', 'desc')->get();
    }

    public function get_val_doc_status_emp_query($credential)
    {
        $querytbl = new query_tbl();
        $querytbl = $querytbl->where('emp_id', '=', $credential['emp_id']);
        $querytbl = $querytbl->where('status', '=', $credential['status']);
        return $querytbl = $querytbl->get();
    }
    public function get_ticket_id($filter_data)
    {
        // dd($filter_data);
        $record = new query_document_tbl();
        
        // Handle empty document array
        if (!empty($filter_data['document'])) {
            $record = $record->whereIn('document', $filter_data['document']);
        } else {
            // If document array is empty, return empty collection
            return collect([]);
        }
        
        if ($filter_data['user_type'] != "HR-LEAD") {
            $record = $record->where('updated_by', $filter_data['updated_by']);
        }
        // dd($record);

        $record = $record->groupBy('ticket_id');
        return $record = $record->get();
    }

    public function get_not_completed_tickets($filter_data)
    {
        $record = new query_document_tbl();
        if ($filter_data['user_type'] != "HR-LEAD") {
            $record = $record->whereIn('document', $filter_data['document']);
            $record = $record->whereIn('ticket_id', $filter_data['ticket_id']);
            $record = $record->where('updated_by', $filter_data['updated_by']);
            $record = $record->where('status', '!=', 'Completed');
        }
        if ($filter_data['user_type'] == "HR-LEAD") {
            if ($filter_data['status'] == "Completed") {
                $record = $record->where('status', '!=', 'Completed');
                $record = $record->groupBy('ticket_id');
            } else {
                $record = $record->where('status', '!=', 'Pending');
                $record = $record->groupBy('ticket_id');
            }

        }
        return $record = $record->get();
    }

    public function get_admin_query_default($filter_data)
    {
        $record = DB::table('query_document_tbls as qdt')
            ->join('query_tbls as qt', 'qt.ticket_id', '=', 'qdt.ticket_id')
            ->select('qt.*', 'qdt.id as doc_id', 'qdt.dec_remark as dec_remark', 'qdt.status as doc_status', 'qdt.updated_by as u_by');
        if ($filter_data['status'] == "Completed") {
            $record->whereIn('qdt.ticket_id', $filter_data['t_id']);
        }
        $record->whereIn('qdt.document', $filter_data['document']);
        $record->where('qdt.status', '=', $filter_data['status']);
        $record->groupBy('qt.ticket_id');
        $record->orderBy('qt.created_at', 'desc');
        return $record->get();
    }

    public function get_admin_query_default2($filter_data)
    {
        $record = DB::table('query_document_tbls as qdt')
            ->join('query_tbls as qt', 'qt.ticket_id', '=', 'qdt.ticket_id')
            ->select('qt.*', 'qdt.id as doc_id', 'qdt.dec_remark as dec_remark', 'qdt.status as doc_status', 'qdt.updated_by as u_by')
            ->where('qt.status', '=', $filter_data['status']);
        // if($filter_data['status'] == "Pending"){
        //     $record->whereIn('qdt.ticket_id', $filter_data['t_id']);
        // }
        // if($filter_data['status'] == "Approved"){
        //     $record->whereNotIn('qdt.ticket_id', $filter_data['t_id']);
        //     $record->where('qt.status', '=',$filter_data['status']);
        // }
        // if($filter_data['status'] == "Completed"){
        //     $record->whereIn('qdt.ticket_id', $filter_data['t_id']);
        // }
        // if($filter_data['status'] == "Declined"){
        //     $record->where('qdt.status', $filter_data['status']);
        // }
        $record->groupBy('qt.ticket_id');
        $record->orderBy('qt.created_at', 'desc');
        return $record;
    }

    public function get_admin_daily_report_query_filter($filter_data)
    {

        $record = DB::table('query_document_tbls as qt')
            ->join('query_tbls as qry', 'qt.ticket_id', '=', 'qry.ticket_id')
            ->select('qt.*', 'qry.updated_by', 'qry.emp_id')
            ->whereDate('qt.created_at', '=', $filter_data['filter_date'])
            ->where('qry.updated_by', '=', $filter_data['updated_by'])
        // ->where('qry.approved_lvl', '=', $filter_data['approved_lvl'])
            ->orderBy('qt.created_at', 'desc')
            ->get();
        return $record;
    }

    public function get_p_s_admin_daily_report_query_filter($filter_data)
    {

        $record = DB::table('query_document_tbls as qt')
            ->join('query_tbls as qry', 'qt.ticket_id', '=', 'qry.ticket_id')
            ->select('qt.*', 'qry.updated_by', 'qt.updated_by as up_by', 'qry.emp_id')
            ->whereDate('qt.created_at', '=', $filter_data['filter_date'])
            ->orderBy('qt.created_at', 'desc')
            ->get();
        return $record;
    }

    public function get_admin_daily_report_query_filter_All($filter_data)
    {

        $record = DB::table('query_document_tbls as qt')
            ->join('query_tbls as qry', 'qt.ticket_id', '=', 'qry.ticket_id')
            ->select('qt.*', 'qry.updated_by', 'qry.emp_id')
            ->whereDate('qt.created_at', '=', $filter_data['filter_date'])
        // ->where('qry.updated_by', '=', $filter_data['updated_by'])
        // ->where('qry.approved_lvl', '=', $filter_data['approved_lvl'])
            ->orderBy('qt.created_at', 'desc')
        // ->groupBy( 'qt.ticket_id' )
            ->get();
        return $record;
    }

    public function get_admin_query_default_ps($filter_data)
    {

        $record = DB::table('query_tbls as qt')
            ->join('emp_profile_tbls as emp', 'qt.emp_id', '=', 'emp.emp_id')
            ->select('qt.*', 'emp.emp_name', 'emp.type_of_leaving')
            ->where('qt.status', '=', $filter_data['status'])
            ->orderBy('qt.created_at', 'desc')
            ->get();
        return $record;
    }

    public function get_admin_query_default_2($filter_data)
    {

        $data = DB::table('query_tbls as qt')
            ->join('query_document_tbls as qdt', 'qt.ticket_id', '=', 'qdt.ticket_id')
            ->select('qt.*')
            ->whereIn('qdt.document', $filter_data['document'])
            ->where('qt.status', $filter_data['status']);
        if ($filter_data['updated_by'] != '' && $filter_data['status'] != 'Pending') {
            $data->where('qt.updated_by', $filter_data['updated_by']);
        }
        // $mdlpointstbl->orderBy( 'qt.created_at', 'desc' )
        $data->groupBy('qdt.ticket_id');
        return $data->orderBy('qt.created_at', 'desc');

        // return $mdlpointstbl;
        // $mdlpointstbl = new query_tbl();
        // $mdlpointstbl = $mdlpointstbl->where( 'approved_lvl', $filter_data['approved_lvl']);
        // $mdlpointstbl = $mdlpointstbl->where( 'status', $filter_data['status']);
        // if($filter_data['updated_by'] !=''  && $filter_data['status'] !='Pending'){
        //     $mdlpointstbl = $mdlpointstbl->where( 'updated_by', $filter_data['updated_by']);
        // }
        // return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }

    public function get_admin_query($filter_data)
    {

        $mdlpointstbl = new query_tbl();
        if ($filter_data['start_date'] != '' && $filter_data['end_date'] != '') {
            $start_date = date('Y-m-d', strtotime($filter_data['start_date']));
            $end_date = date('Y-m-d', strtotime($filter_data['end_date']));

            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '>=', $start_date);
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '<=', $end_date);
        }

        if ($filter_data['start_date'] != '' && $filter_data['end_date'] == '') {
            $start_date = date('Y-m-d', strtotime($filter_data['start_date']));
            $end_date = date('Y-m-d');
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '>=', $start_date);
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '<=', $end_date);
        }
        if ($filter_data['start_date'] == '' && $filter_data['end_date'] != '') {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime($filter_data['end_date']));
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '>=', $start_date);
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '<=', $end_date);
        }

        if ($filter_data['updated_by'] != '' && $filter_data['status'] != 'Pending') {
            $mdlpointstbl = $mdlpointstbl->where('updated_by', $filter_data['updated_by']);
        }

        $mdlpointstbl = $mdlpointstbl->where('status', $filter_data['status']);

        return $mdlpointstbl
            ->orderBy('created_at', 'desc');
    }

    public function update_query_status($credentials)
    {
        $update_row = new query_tbl();
        $update_row = $update_row->where('ticket_id', '=', $credentials['ticket_id']);
        $update_row->update(['updated_by' => $credentials['updated_by'], 'status' => $credentials['status']]);
    }

    public function update_query_status_doc($credentials)
    {
        $update_row = new query_document_tbl();
        $update_row = $update_row->whereIn('document', $credentials['document']);
        $update_row = $update_row->where('ticket_id', $credentials['ticket_id']);
        $update_row->update(['updated_by' => $credentials['updated_by'], 'dec_remark' => $credentials['dec_remark'], 'status' => $credentials['status']]);
    }

    public function update_query_status_and_rem($credentials)
    {
        $update_row = new query_tbl();
        $update_row = $update_row->where('ticket_id', '=', $credentials['ticket_id']);
        $update_row->update(['updated_by' => $credentials['updated_by'], 'status' => $credentials['status']]);

    }
    public function get_updated_doc_detail($credentials)
    {
        $querytbl = new query_document_tbl();
        return $querytbl::where('ticket_id', $credentials['ticket_id'])
            ->get();
    }

    public function Get_docs_Query($credentials)
    {
        $querytbl = new query_document_tbl();
        $querytbl = $querytbl->whereIn('document', $credentials['document']);
        $querytbl = $querytbl->where('ticket_id', $credentials['ticket_id']);
        $querytbl = $querytbl->get();

        return $querytbl;
    }

    public function get_updated_doc_detail_b_doc($credentials)
    {
        $querytbl = new query_document_tbl();
        $querytbl = $querytbl->where('ticket_id', $credentials['ticket_id']);
        $querytbl = $querytbl->where('updated_by', $credentials['updated_by']);
        $querytbl = $querytbl->get();

        return $querytbl;
    }

    public function get_query_detail_count($credentials)
    {
        $get_result = DB::table('query_tbls')
            ->select('query_tbls.*')
            ->where('emp_id', '=', $credentials['emp_id'])
            ->where('status', '=', $credentials['status'])
            ->count();
        return $get_result;
    }

    public function adm_get_query_detail_count($credentials)
    {
        $get_result = DB::table('query_tbls')
            ->select('query_tbls.*')
        // ->where('approved_lvl',$credentials['lvl'])
            ->where('status', '=', $credentials['status'])
            ->count();
        return $get_result;
    }

    public function adm_get_query_detail_count_1($credentials)
    {
        $get_result = DB::table('query_tbls')
            ->select('query_tbls.*')
            ->where('status', '=', $credentials['status'])
            ->count();
        return $get_result;
    }

    public function adm_get_query_com_count($credentials)
    {
        $get_result = DB::table('query_tbls')
            ->select('query_tbls.*')
            ->where('updated_by', '=', $credentials['emp_id'])
            ->where('status', '=', $credentials['status'])
            ->count();
        return $get_result;
    }

    public function adm_get_query_com_count_s_a($credentials)
    {
        $get_result = DB::table('query_tbls')
            ->select('query_tbls.*')
            ->where('status', '=', $credentials['status'])
            ->count();
        return $get_result;
    }

    public function get_doc_status($credentials)
    {
        $get_result = DB::table('query_document_tbls')
            ->where('ticket_id', '=', $credentials['t_id'])
            ->where('document', '=', $credentials['doc'])
            ->where('status', '=', $credentials['status'])
            ->get();
        return $get_result;
    }

    public function get_decline_remark($ticket_id)
    {
        $querytbl = new query_document_tbl();
        $querytbl = $querytbl->where('ticket_id', $ticket_id);
        $querytbl = $querytbl->select('dec_remark', 'updated_by');
        $querytbl = $querytbl->distinct();
        $querytbl = $querytbl->get();
        return $querytbl;
    }
//reassign
    public function reassign_query_status_doc($credentials)
    {
        $get_result = DB::table('query_tbls')
            ->where('emp_id', '=', $credentials['emp_id'])
            ->where('ticket_id', '=', $credentials['ticket_id'])
           
            ->first();
        return $get_result;
    }
    public function update_reassign_form($credentials)
    {
        //update data
        $update_row = new query_tbl();
        $update_row = $update_row->where('ticket_id', '=', $credentials['ticket_id']);
        $update_row = $update_row->where('emp_id', '=', $credentials['emp_id']);
        $update_row->update(['reassign_to' => $credentials['assign_to'], 'document' => $credentials['to_docu'],'updated_by' => $credentials['updated_by'],'status' => $credentials['status']]);
        //save data
        $reassign_details = new reassign_tbls();
        $reassign_details->ticket_id = $credentials['ticket_id'];
        $reassign_details->from_docu = $credentials['from_docu'];
        $reassign_details->to_docu = $credentials['to_docu'];
        $reassign_details->created_by = $credentials['created_by'];
        $reassign_details->assign_to = $credentials['assign_to'];
        $reassign_details->assign_from = $credentials['assign_from'];
        $reassign_details->save();
    }
    public function deleteDocEntry($credentials3)
    {
        $query_details = new query_document_tbl();
        $query_details = $query_details->where('ticket_id', '=', $credentials3['ticket_id']);
        $query_details = $query_details->where('document', '=', $credentials3['document']);
        $query_details->delete();
    }
    public function Get_reassign_Query()
    {
        $get_result = DB::table('query_tbls')
            ->select('query_tbls.*')
            ->get();
        return $get_result;
    }
    public function get_ticket_date($ticket_id)
    {
        $get_result = DB::table('reassign_tbls')
            ->select('reassign_tbls.*')
            ->where('ticket_id', '=', $ticket_id)
            ->orderBy('updated_at', 'desc')
            ->first();
        return $get_result;
    }
        public function get_qc_detail_count($credentials)
        {
            
            if (!isset($credentials['type'])) {
                return 0;
            }
            if ($credentials['type'] == "fresh") {
                
                // $get_result = DB::table('emp_profile_tbls')
                // ->join('f__f_tracker_alumni_datas as tad' , 'tad.emp_id','emp_profile_tbls.emp_id')

                //     ->select('emp_profile_tbls.*','tad.*')
                //     ->where('f_f_c_s_g', '=', $credentials['f_f_c_s_g'])
                //     ->whereIn('tad.re_open_ct', '='  ,[' ',null] )
                //     // ->orWhere('tad.re_open_ct', '='  , null)
                //     ->count();
                $get_result = DB::table('emp_profile_tbls')
                ->join('f__f_tracker_alumni_datas as tad', 'tad.emp_id', 'emp_profile_tbls.emp_id')
                ->select('emp_profile_tbls.*', 'tad.*')
                ->where('f_f_c_s_g', '=', $credentials['f_f_c_s_g'])
                ->count();

            } else if ($credentials['type'] == "greater") {

                $get_result = DB::table('emp_profile_tbls')
                    ->select('emp_profile_tbls.*')
                    ->where('f_f_c_s_g', '>=', $credentials['f_f_c_s_g'])
                    ->count();

            } else if ($credentials['type'] == "revert") {
                $get_result = DB::table('revert_tables')
                    ->select('revert_tables.*')
                    ->where('from_sg', $credentials['f_f_c_s_g'])
                    ->count();

            }else if ($credentials['type'] == "fresh_r") {

                $get_result = DB::table('emp_profile_tbls')
                ->join('f__f_tracker_alumni_datas as tad' , 'tad.emp_id','emp_profile_tbls.emp_id')
                    ->select('emp_profile_tbls.*','tad.*')
                    ->where('f_f_c_s_g', '=', $credentials['f_f_c_s_g'])
                    ->where('tad.re_open_ct', '!=' , '')
                    ->count();

            }else if ($credentials['type'] == "greater_r") {

                $get_result = DB::table('emp_profile_tbls')
                ->join('f__f_tracker_alumni_datas as tad' , 'tad.emp_id','emp_profile_tbls.emp_id')
                    ->select('emp_profile_tbls.*', 'tad.*')
                    ->where('f_f_c_s_g', '>=', $credentials['f_f_c_s_g'])
                    ->where('tad.re_open_ct', '!=' , '')
                    ->count();

            } else if ($credentials['type'] == "revert_r") {

                $get_result = DB::table('revert_tables')
                    ->select('revert_tables.*')
                    ->where('from_sg', $credentials['f_f_c_s_g'])
                    ->where('re_open_status', '!=' , '')
                    ->count();

            }else{
                return 0;
            }

            return $get_result;
        }
}
