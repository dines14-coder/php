<?php

namespace App\Repositories;

use DB;
use App\Models\emp_profile_tbl;
use App\Models\amb_document_tbl;

 
class DocRepository implements IDocRepository
{

    public function get_all_ambassador_default($filter_data) {

        $mdlpointstbl = new emp_profile_tbl();
        $mdlpointstbl = $mdlpointstbl->where( 'status', '!=', 'Hold' );
        $mdlpointstbl = $mdlpointstbl->where( 'status', '!=', 'Declined' );
        if($filter_data['user_type'] == "Payroll_Finance" ){
            $mdlpointstbl = $mdlpointstbl->where( 'f_f_document','Yes' );
        }
        return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }

    public function get_all_reg_ambassador_default($filter_data) {

        $mdlpointstbl = new emp_profile_tbl();
        $mdlpointstbl = $mdlpointstbl->where( 'status', '=', $filter_data['status'] );
        return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }

    public function get_all_ambassador($filter_data) {

        $mdlpointstbl = new emp_profile_tbl();
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

        return $mdlpointstbl
        ->orderBy( 'created_at', 'desc' );
    }

    public function get_ambassador_default($filter_data) {

        $mdlpointstbl = new emp_profile_tbl();
        if($filter_data['doc_status_col']=="HR-LEAD"){
            if($filter_data['doc_status']=="Fresh" || $filter_data['doc_status']=="Completed"){
                $mdlpointstbl = $mdlpointstbl->where( "doc_status", $filter_data['doc_status']);
            }
            else if($filter_data['doc_status']=="Pending"){
                $mdlpointstbl = $mdlpointstbl->whereNotIn( "doc_status", ['Fresh','Completed']);
            }
            
        }
        else{
            $mdlpointstbl = $mdlpointstbl->where( $filter_data['doc_status_col'], $filter_data['doc_status']);

        }
        $mdlpointstbl = $mdlpointstbl->where( 'status', "Active");
        return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }

    public function get_ambassador_default_ps($filter_data) {

        $mdlpointstbl = new emp_profile_tbl(); 
        
        if($filter_data['doc_status']=="Pending"){
            $mdlpointstbl = $mdlpointstbl->whereNotIn( $filter_data['doc_status_col_1'],  ['Fresh','Completed'] );
        }
        else{
            $mdlpointstbl = $mdlpointstbl->where( $filter_data['doc_status_col_1'], $filter_data['doc_status']);
        }
       
        $mdlpointstbl = $mdlpointstbl->where( 'status', "Active");
        return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }
    

    public function get_ambassador_default_2($filter_data) { 

        $mdlpointstbl = new emp_profile_tbl();

        if($filter_data['doc_status_col']=="Payroll_QC"){
            $mdlpointstbl = $mdlpointstbl->whereIn( "doc_status", ['Pending','Completed']);
        }
        else {
            $mdlpointstbl = $mdlpointstbl->where( $filter_data['doc_status_col'], $filter_data['doc_status']);
        }

        if($filter_data['updated_by'] !=''  && $filter_data['doc_status'] !='Fresh'){
            $mdlpointstbl = $mdlpointstbl->where( $filter_data['check_col'], $filter_data['updated_by']);
        }
        return $mdlpointstbl->orderBy( 'created_at', 'desc' );

    }


    public function get_ambassador($filter_data) {

        $mdlpointstbl = new emp_profile_tbl();
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
        $mdlpointstbl = $mdlpointstbl->where( $filter_data['doc_status_col'], $filter_data['doc_status']);

        return $mdlpointstbl
        ->orderBy( 'created_at', 'desc' );
    }
   
    public function DocumentEntry( $credentials ) {
        $querytbl = new amb_document_tbl();
        $querytbl->emp_id = $credentials['emp_id'];
        $querytbl->document = $credentials['document'];
        $querytbl->file_name = $credentials['file_name'];
        $querytbl->status = $credentials['status'];
        $querytbl->save();

    }

    public function get_doc_entry($row,$emp_id)
    {
        $querytbl = new amb_document_tbl(); 
        $querytbl = $querytbl->where( $row,'=', $emp_id );
        return $querytbl = $querytbl->get();
    }
    
    public function get_updated_doc_detail($credentials)
    {
        $querytbl = new amb_document_tbl(); 
        return $querytbl::where( 'emp_id', $credentials['emp_id'] )
        ->get();

    }

    public function get_count($table,$column,$value)
    {
        $record = DB::table($table)->where($column,$value)->count();
        return $record;
    }

    public function get_entry_count($table,$column,$value,$column2,$value2,$column3,$value3,$con)
    {
        $record = DB::table($table)
        ->where($column,$value)
        ->where($column2,$value2)
        ->where($column3,$con,$value3)
        ->count();
        return $record;
    }

    public function get_admin_tbl($table,$column,$value,$emp_id)
    {
        $record = DB::table($table)->whereIn($column,$value)->groupBy($emp_id)->get();
        return $record;
    }

    public function get_data_with_where2($table,$column,$value,$column2,$value2)
    {
        $record = DB::table($table)
        ->where($column,$value)
        ->where($column2,$value2)
        ->get();
        return $record;
    }



}