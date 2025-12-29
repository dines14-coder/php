<?php

namespace App\Repositories;

use DB;
use App\Models\query_tbl;
use App\Models\query_document_tbl;
 
class QueryRepository implements IQueryRepository
{
    
    public function QueryEntry( $credentials ) {
        $querytbl = new query_tbl();
        $querytbl->ticket_id = 'T_'.str_pad( ( $querytbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->document = $credentials['document']; 
        $querytbl->remark = $credentials['remark'];
        $querytbl->approved_lvl = $credentials['approved_lvl'];
        $querytbl->updated_by = $credentials['updated_by'];
        $querytbl->status = $credentials['status'];
        $querytbl->save();

        return $querytbl->ticket_id;
    }

    public function QueryDocumentEntry( $credentials ) {
        $querytbl = new query_document_tbl();
        $querytbl->ticket_id = $credentials['ticket_id'];
        $querytbl->document = $credentials['document'];
        $querytbl->file_name = $credentials['file_name'];
        $querytbl->remark = $credentials['remark'];
        $querytbl->status = $credentials['status'];
        $querytbl->save();

    }

    public function get_query_u_tic_id($ticket_id)
    {
        $querytbl = new query_tbl(); 
        return $querytbl::where( 'ticket_id', $ticket_id )
        ->get();
    }

    public function get_all_emp_query($emp_id)
    {
        $querytbl = new query_tbl(); 
        return $querytbl::where( 'emp_id', $emp_id )
        ->get();
    }

    public function get_val_doc_status_emp_query($credential)
    {
        $querytbl = new query_tbl(); 
        $querytbl = $querytbl->where( 'emp_id','=', $credential['emp_id'] );
        $querytbl = $querytbl->where( 'status','=', $credential['status'] );
        return $querytbl = $querytbl->get();
    }

    public function get_admin_query_default($filter_data) {


        $record=  DB::table('query_tbls as qt');
        $record=$record->join('emp_profile_tbls as qry', 'qt.emp_id','=','qry.emp_id');
        $record=$record->select('qt.*','qry.type_of_leaving');
        if($filter_data['approved_lvl']!="HR_LEAD"){
            $record=$record->where('qt.approved_lvl', '=',$filter_data['approved_lvl']);
        }
        $record=$record->where('qt.status', '=',$filter_data['status']);
        $record=$record->orderBy( 'qt.created_at', 'desc' );
        // $mdlpointstbl = new query_tbls();
        // $mdlpointstbl = $mdlpointstbl->where( 'approved_lvl', $filter_data['approved_lvl']);
        // $mdlpointstbl = $mdlpointstbl->where( 'status', $filter_data['status']);
        // return $mdlpointstbl->orderBy( 'created_at', 'desc' );

        return $record;

    } 
    
    public function get_admin_daily_report_query_filter($filter_data) {

        $record = DB::table('query_document_tbls as qt')
        ->join('query_tbls as qry', 'qt.ticket_id','=','qry.ticket_id')
        ->select('qt.*','qry.updated_by','qry.emp_id')
        ->whereDate('qt.created_at', '=', $filter_data['filter_date'])
        ->where('qry.updated_by', '=', $filter_data['updated_by'])
        ->where('qry.approved_lvl', '=', $filter_data['approved_lvl'])
        ->orderBy( 'qt.created_at', 'desc' )
        // ->groupBy( 'qt.ticket_id' )
        ->get();
        return $record;
    } 

    public function get_p_s_admin_daily_report_query_filter($filter_data) {

        $record = DB::table('query_document_tbls as qt')
        ->join('query_tbls as qry', 'qt.ticket_id','=','qry.ticket_id')
        ->select('qt.*','qry.updated_by','qry.emp_id')
        ->whereDate('qt.created_at', '=', $filter_data['filter_date'])
        ->orderBy( 'qt.created_at', 'desc' )
        // ->groupBy( 'qt.ticket_id' )
        ->get();
        return $record;
    } 
    
    
    public function get_admin_daily_report_query_filter_All($filter_data) {

        $record = DB::table('query_document_tbls as qt')
        ->join('query_tbls as qry', 'qt.ticket_id','=','qry.ticket_id')
        ->select('qt.*','qry.updated_by','qry.emp_id')
        ->whereDate('qt.created_at', '=', $filter_data['filter_date'])
        // ->where('qry.updated_by', '=', $filter_data['updated_by'])
        ->where('qry.approved_lvl', '=', $filter_data['approved_lvl'])
        ->orderBy( 'qt.created_at', 'desc' )
        // ->groupBy( 'qt.ticket_id' )
        ->get();
        return $record;
    } 

    public function get_admin_query_default_ps($filter_data) {

        $record = DB::table('query_tbls as qt')
        ->join('emp_profile_tbls as emp', 'qt.emp_id','=','emp.emp_id')
        ->select('qt.*','emp.emp_name','emp.type_of_leaving')
        ->where('qt.status', '=', $filter_data['status'])
        ->orderBy( 'qt.created_at', 'desc' )
        ->get();
        return $record;
    }

    public function get_admin_query_default_2($filter_data) {

  
        $data = DB::table('query_tbls as qt')
        ->join('emp_profile_tbls as emp', 'qt.emp_id','=','emp.emp_id')
        ->select('qt.*','emp.type_of_leaving')
        ->where( 'qt.approved_lvl', $filter_data['approved_lvl'])
       
        ->where( 'qt.status', $filter_data['status']);
        if($filter_data['updated_by'] !=''  && $filter_data['status'] !='Pending'){
            $data->where( 'qt.updated_by', $filter_data['updated_by']);
        }
        // $mdlpointstbl->orderBy( 'qt.created_at', 'desc' )
        return $data->orderBy( 'qt.created_at', 'desc' );
       
        // return $mdlpointstbl;
        // $mdlpointstbl = new query_tbl();
        // $mdlpointstbl = $mdlpointstbl->where( 'approved_lvl', $filter_data['approved_lvl']);
        // $mdlpointstbl = $mdlpointstbl->where( 'status', $filter_data['status']);
        // if($filter_data['updated_by'] !=''  && $filter_data['status'] !='Pending'){
        //     $mdlpointstbl = $mdlpointstbl->where( 'updated_by', $filter_data['updated_by']);
        // }
        // return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }

    public function get_admin_query($filter_data) {

        $mdlpointstbl = new query_tbl();
        if($filter_data['start_date'] !=''  && $filter_data['end_date'] !=''){
            $start_date = date('Y-m-d', strtotime($filter_data['start_date']));
            $end_date = date('Y-m-d', strtotime($filter_data['end_date']));

            $mdlpointstbl = $mdlpointstbl->whereDate( 'created_at', '>=', $start_date );
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '<=', $end_date);
        }
        
        if($filter_data['start_date'] !=''  && $filter_data['end_date'] ==''){
            $start_date = date('Y-m-d', strtotime($filter_data['start_date']));
            $end_date = date('Y-m-d');
            $mdlpointstbl = $mdlpointstbl->whereDate( 'created_at', '>=', $start_date );
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '<=', $end_date);
        }
        if($filter_data['start_date'] ==''  && $filter_data['end_date'] !=''){
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime($filter_data['end_date']));
            $mdlpointstbl = $mdlpointstbl->whereDate( 'created_at', '>=', $start_date );
            $mdlpointstbl = $mdlpointstbl->whereDate('created_at', '<=', $end_date);
        }

        if($filter_data['updated_by'] !=''  && $filter_data['status'] !='Pending'){
            $mdlpointstbl = $mdlpointstbl->where( 'updated_by', $filter_data['updated_by']);
        }

        $mdlpointstbl = $mdlpointstbl->where( 'status', $filter_data['status']);

        return $mdlpointstbl
        ->orderBy( 'created_at', 'desc' );
    }

    public function update_query_status($credentials)
    {
        $update_row = new query_tbl();
        $update_row = $update_row->where('ticket_id', '=', $credentials['ticket_id']);
        $update_row->update(['updated_by' => $credentials['updated_by'],'status' => $credentials['status']]);
         
    }  

    public function update_query_status_and_rem($credentials)
    {
        $update_row = new query_tbl();
        $update_row = $update_row->where('ticket_id', '=', $credentials['ticket_id']);
        $update_row->update(['updated_by' => $credentials['updated_by'],'admin_remark' => $credentials['admin_remark'],'status' => $credentials['status']]);
         
    }
    public function get_updated_doc_detail($credentials)
    {
        $querytbl = new query_document_tbl(); 
        return $querytbl::where( 'ticket_id', $credentials['ticket_id'] )
        ->get();
    }
    public function get_updated_doc_detail_b_doc($credentials)
    {
        $querytbl = new query_document_tbl();
        $querytbl =  $querytbl-> where( 'ticket_id', $credentials['ticket_id'] );
        $querytbl =  $querytbl-> where( 'document', $credentials['doc'] );
        $querytbl =  $querytbl->get();

        return $querytbl;

    }

    public function get_query_detail_count($credentials)
    {
        $get_result = DB::table('query_tbls')
        ->select('query_tbls.*')
        ->where('emp_id','=',$credentials['emp_id'])
        ->where('status','=',$credentials['status'])
        ->count(); 
        return $get_result;
    }

    public function adm_get_query_detail_count($credentials)
    {
        $get_result = DB::table('query_tbls') 
        ->select('query_tbls.*')
        ->where('approved_lvl',$credentials['lvl'])
        ->where('status','=',$credentials['status'])
        ->count(); 
        return $get_result;
    }

    public function adm_get_query_detail_count_1($credentials)
    {
        $get_result = DB::table('query_tbls') 
        ->select('query_tbls.*')
        ->where('status','=',$credentials['status'])
        ->count(); 
        return $get_result;
    }

    public function adm_get_query_com_count($credentials)
    {
        $get_result = DB::table('query_tbls') 
        ->select('query_tbls.*')
        ->where('approved_lvl',$credentials['lvl'])
        ->where('updated_by','=',$credentials['emp_id'])
        ->where('status','=',$credentials['status'])
        ->count(); 
        return $get_result;
    }
    
    
    public function adm_get_query_com_count_s_a($credentials)
    {
        $get_result = DB::table('query_tbls') 
        ->select('query_tbls.*')
        ->where('approved_lvl',$credentials['lvl'])
        ->where('status','=',$credentials['status'])
        ->count(); 
        return $get_result;
    }


}