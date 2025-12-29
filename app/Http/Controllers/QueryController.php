<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IQueryRepository;
// use App\Models\bank_account_form;
use DB;
use Auth;


class QueryController extends Controller
{
    //
    public function __construct( IQueryRepository  $query_task) {
        $this->middleware( 'auth' );
        $this->middleware( 'check.first.login' );
        $this->query_task = $query_task;
    }

    public function create_query_landing()
    {
    $user = auth()->user();
    $emp_id = $user->emp_id;
    $bank_status = $this->query_task->bank_status();
    
    return view('create_query_landing', compact('bank_status'));
    }
    public function check_valid_doc_upl()
    {
        $doc_str='';
        $hide_div=array();
        $user = auth()->user();
        $emp_id=$user->emp_id;
        $credential=[
            'emp_id'=>$emp_id,
            'status'=>'Pending', 
        ];
        $get_query = $this->query_task->get_val_doc_status_emp_query( $credential ); 
        foreach ($get_query as $key => $get_query) {
            
            $doc_str.=rtrim($get_query->document,",");
            $doc_str.=",";
        }
        $doc_array=explode(",",$doc_str);
        $pre_doc_query_1=array_unique($doc_array);

        $pre_doc_query=array_values($pre_doc_query_1);


        $marks = array("Pay Slips","F&F Statement","Form 16","Relieving Letter","Service Letter","Bonus","Performance Incentive","Sales Travel claim","Parental medical reimbursement","PF","Gratuity","Others"); 

        foreach($pre_doc_query as $key=>$pre_doc)
        {
          if($pre_doc!==""){
            if (in_array($pre_doc, $marks)==true) 
            {
                $hide_div[]=$pre_doc;
            }
          }
        }
        $hide_div_str=implode(",",$hide_div);

        return response()->json( ['hide_div' => $hide_div_str] ); 
    }
    public function query_status_landing() 
    {
        return view('query_status_landing');
    }
    public function bank_account_change(){
        return view('Bank_account');
    }
    // public function bank_status(){
        
    //     $bank_status = $this->query_task->bank_status();
    //     return view('create_query_landing',$bank_status);

    // }

    

    public function get_all_emp_query()
    {
        $output_load ='';

        $user = auth()->user();
        $emp_id=$user->emp_id;
        $get_query = $this->query_task->get_all_emp_query( $emp_id );

        $count=1;
        foreach ($get_query as $key => $get_query) {

            $doc_arr=explode(",",$get_query->document);
            $d_i=0;
            $doc_div='';
            while($d_i<count($doc_arr)){

                $cred = [
                    'doc' => $doc_arr[$d_i],
                    't_id' => $get_query->ticket_id,
                    'status' => "Completed"
                ];
                $doc_status = $this->query_task->get_doc_status( $cred );

                if(isset($doc_status[0])){
                    $doc_div.='<div class="badge badge-success doc_name">'.$doc_arr[$d_i].'</div><br>';
                }else{
                    $doc_div.='<div class="badge badge-primary doc_name">'.$doc_arr[$d_i].'</div><br>';
                }
                $d_i++;
            }
            $doc_view="";
            if($get_query->status=="Pending"){
                $sts_clr='warning';
                $doc_view='<a style="cursor:pointer;" title="View Documents" class="btn disabled  btn-outline-primary">Detail</a>';
            }
            else if($get_query->status=="Approved"){
                $sts_clr='primary';
                // $doc_view='<a class="btn disabled  btn-outline-primary">Detail</a>';

                $doc_view='<a onclick="view_doc_emp('."'".$get_query->ticket_id."'".','."'".$get_query->emp_id."'".');" title="View Documents" style="cursor:pointer;" class="btn btn-outline-primary">Detail</a>';
            }
            else if($get_query->status=="Declined"){
                $sts_clr='danger';
                $doc_view='<a class="btn disabled  btn-outline-primary">Detail</a>';
            }
            else if($get_query->status=="Completed"){
                $sts_clr='success';
                $doc_view='<a onclick="view_doc_emp('."'".$get_query->ticket_id."'".','."'".$get_query->emp_id."'".');" title="View Documents" style="cursor:pointer;" class="btn btn-outline-primary">Detail</a>';
            }

            if($get_query->status=="Declined"){
                $remark = '<b>Remark</b> : '.$get_query->remark.'<br><b>Decline Remarks : </b><br>';
                $get_decline_remark = $this->query_task->get_decline_remark( $get_query->ticket_id );
                $i=1;
                if(isset($get_decline_remark[0])){
                    foreach($get_decline_remark as $row){
                        if($row->dec_remark !=""){
                            $remark .= $i.'. '.$row->dec_remark.'<br>';
                            $i++;
                        }
                    }
                }
            }
            else{
                $remark = $get_query->remark;
            }
            

            $output_load .= '<tr>
            <td>'.$count.'</td>
            <td>'.$get_query->ticket_id.'</td>
            <td class="text-truncate">
               '.$doc_div.'
            </td>
            <td>'.$remark.'</td>
            <td>'.$get_query->created_at.'</td>
            <td>
              <div class="badge badge-'.$sts_clr.'">'.$get_query->status.'</div>
            </td>
            <td>
              '.$doc_view.'
            </td>
            
          </tr>';
          $count++;


        }
        
        return response()->json( ['listing_querys' => $output_load
        ] ); 
    }


    public function doc_updated_detail_emp(Request $request)
    { 
        $credentials=[
            'ticket_id'=>$request->input('ticket_id'),
            'emp_id'=>$request->input('emp_id'),
        ];
        $update_query_doc = $this->query_task->get_updated_doc_detail( $credentials );

        $show_div="";
        $remark="";
        
        foreach ($update_query_doc as $key => $get_query) {
            if($get_query->document=="Pay Slips"){
                $path="pay_slip";
            }
            if($get_query->document=="F&F Statement"){
                $path="ff_statement";
            }
            if($get_query->document=="Form 16"){
                $path="form16";
            }
            if($get_query->document=="Form 16 Part A"){
                $path="form16";
            }
            if($get_query->document=="Form 16 Part B"){
                $path="form16";
            }
            if($get_query->document=="Relieving Letter"){
                $path="rel_letter";
            }
            if($get_query->document=="Service Letter"){
                $path="ser_letter";
            }

            if($get_query->document=="Bonus"){
                $path="bonus";
            }
            if($get_query->document=="Performance Incentive"){
                $path="performance_incentive";
            }
            if($get_query->document=="Sales Travel claim"){
                $path="sales_travel_claim";
            }
            if($get_query->document=="Parental medical reimbursement"){
                $path="parental_medical_reimbursement";
            }
            
            // type2
            if($get_query->document=="PF"){
                $path="pf";
            }
            if($get_query->document=="Gratuity"){
                $path="gratuity";
            }
            // end type 2

            if($get_query->document=="Others"){
                $path="others_doc";
            }
            $file_name=$get_query->file_name;  
            if($get_query->remark!=""){
                $remark='<div class="form-group col-md-8" '.$get_query->document.'" > 
                <label>Remark</label><br>
                <span >'.$get_query->remark.'</span>
              </div>';
            }else{
                $remark='<div class="form-group col-md-8" '.$get_query->document.'" > 
                <label>Remark</label><br>
                <span  >---</span>
              </div>';
            }

            if($file_name==""){
                // file empty
                $file_check="cursor: not-allowed; pointer-events: none";
                $btn_style="cursor: not-allowed;    pointer-events: none;";
                $msg="<p>Document Not Uploaded.!</p>";
            }
            else{
                $file_check="";
                $btn_style="";
                $msg="";
            }

            if($get_query->document == "Pay Slips" || $get_query->document == "Form 16" || $get_query->document == "Form 16 Part A" || $get_query->document == "Form 16 Part B"){
                if($get_query->document == "Pay Slips" || $get_query->document == "Form 16"){
                    $files = explode(',',$file_name);
                    $show_div.='';
                    foreach($files as $file){
                        $show_div.='<div class="row">
                        <div class="col-md-4">
                        <label class="text-dark" style="font-size:12px;">Document</label><br>
                            <a style="'.$file_check.'" href="../query/'.$credentials['emp_id'].'/'.$path.'/'.$file.'" target="_blank">
                                <button style="'.$btn_style.'" class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" data-toggle="tooltip" data-placement="bottom" type="button" title="PDF"><span><i class="fa fa-file-pdf"></i> '.$get_query->document.'</span></button>'.$msg.'
                            </a>
                            </div>'.$remark.'
                            </div><hr>';
                    }
                } else {
                    // Handle Form 16 Part A and Part B individually
                    $show_div.='<div class="row">
                    <div class="col-md-4">
                    <label class="text-dark" style="font-size:12px;">Document</label><br>
                        <a style="'.$file_check.'" href="../query/'.$credentials['emp_id'].'/'.$path.'/'.$file_name.'" target="_blank">
                            <button style="'.$btn_style.'" class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" data-toggle="tooltip" data-placement="bottom" type="button" title="PDF"><span><i class="fa fa-file-pdf"></i> '.$get_query->document.'</span></button>'.$msg.'
                        </a>
                        </div>'.$remark.'
                        </div><hr>';
                }
            }else{
                $show_div.='
                <div class="row">
                <div class="col-md-4">
                <label class="text-dark" style="font-size:12px;">Document</label><br>
                    <a style="'.$file_check.'" href="../query/'.$credentials['emp_id'].'/'.$path.'/'.$file_name.'" target="_blank">
                        <button style="'.$btn_style.'" class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><span><i class="fa fa-file-pdf"></i> '.$get_query->document.'</span></button>'.$msg.'
                    </a>
                </div>'.$remark.'
                </div><hr>';
            }
        }

        $response= "success";
        return response()->json( ['response' => $response,'show_div'=>$show_div] );
    }
    public function bank_account_form(Request $request){
        $user = auth()->user();
        $emp_id=$user->emp_id;

        $cheque = $request->file('cheque');
        $passbook = $request->file('passbook');
        // dd($cheque);
        
            if ($request->hasfile('cheque') && $request->hasfile('passbook')) {
                    $upload_cheque = 'cheque' . time() . '_' .  '.' . $cheque->extension();
                    $cheque->move(public_path() . '/Bank_details/' . $emp_id . '/cheque', $upload_cheque);
                    $upload_passbook = 'passbook' . time() . '_' .  '.' . $passbook->extension();
                    $passbook->move(public_path() . '/Bank_details/' . $emp_id . '/passbook', $upload_passbook);
                    // print_r($cheque);
                // print_r($upload_passbook);die;

                    $doc_bank = [
                        'emp_id'=> $emp_id,
                        'cheque' => $upload_cheque,
                        'passbook' => $upload_passbook,
                        'action' => "Pending",
                    ];
                    //  print_r($doc_bank);die;
                $saved_bank_query = $this->query_task->bank_account($doc_bank);
                $response = "success";
                
            }else{
                $response = "failed";

            }
            return response()->json( ['response' => $response]);
       
    }

    public function query_form_submit(Request $request)
    {
        $user = auth()->user();
        $emp_id=$user->emp_id;

        $document=$request->input('document');
        $remark=$request->input('remark');
        if(!isset($document[0])){
            // no document choosed
            $response= "no document choosed";
            return response()->json( ['response' => $response] );
            die();
        }
        else{
            if (in_array('Others', $document)==true)
            {
                // choosed others - so check remarks
                if($remark==""){
                    // save
                    $response= "need remark";
                    return response()->json( ['response' => $response] );
                    die();
                }
            }

            // $approved_lvl="1";

            // if (in_array('PF', $document)==true)
            // {
            //     $approved_lvl="2";
            // }
            // else if(in_array('Gratuity', $document)==true){
            //     $approved_lvl="2";
            // }

           
            // save query
            $document_string=implode(",",$document);
            $credentials=[
                'emp_id'=>$emp_id, 
                'document'=>$document_string.',',  
                'remark'=>$request->input('remark'),
                // 'approved_lvl'=>$approved_lvl,
                'updated_by'=>"",
                'status'=>'Pending',
            ];

            $saved_query_ticket_id = $this->query_task->QueryEntry( $credentials );

            foreach($document as $row){
                $credentials2['status'] ='Pending';
                $credentials2['document']=$row;
                $credentials2['ticket_id']=$saved_query_ticket_id;
                $doc = $this->query_task->QueryDocEntry($credentials2);
            }

            $response= "success";
            return response()->json( ['response' => $response,'ticket_id'=>$saved_query_ticket_id] );

        }
    }

    // public function updated_query(){
    //     $query_tbl = DB::table('query_tbls')->select('*')->get();
    //     $t_id = array();
    //     $doc = array();
    //     $query_doc2 = array();
    //     foreach($query_tbl as $row){
    //         $t_id[] = $row->ticket_id;
    //         $doc[] = explode(',',$row->document);
    //         $query_doc = DB::table('query_document_tbls')->whereIn('ticket_id',$t_id)->get();
    //         $i=0;
    //         foreach($query_doc as $r){
    //             echo $doc[$i];
    //             exit;
    //             $query_doc2[] = in_array($doc[$i],$r->document);
    //             $i++;
    //         }
            
           
           
    //     }


        // foreach($doc as $r){
        //     $query_doc = DB::table('query_document_tbls')->whereIn('ticket_id',$t_id,'or')->whereIn('ticket_id',$r)->get();
        // }


    // }


    

}
