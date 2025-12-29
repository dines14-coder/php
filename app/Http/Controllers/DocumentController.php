<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IDocRepository;


class DocumentController extends Controller
{
    //
    public function __construct( IDocRepository  $doc_task) {
        $this->middleware( 'auth' );
        $this->middleware( 'check.first.login' );
        $this->doc_task = $doc_task;
    }

    public function my_document_landing()
    {
        return view('my_document_landing');
    }

    public function get_emp_all_doc_data()
    {
        $output_load =''; 

        $user = auth()->user();
        $emp_id=$user->emp_id;
        $remark=$user->remark_2;
        $get_query = $this->doc_task->get_doc_entry( "emp_id",$emp_id );

        $count=1;
        foreach ($get_query as $key => $get_query) {
            $doc_div='<div class="badge badge-primary doc_name">'.$get_query->document.'</div><br>';
            
            $doc_view='<a style="cursor: pointer;" onclick="view_doc_emp('."'".$get_query->emp_id."'".','."'".$remark."'".','."'".$get_query->document."'".','."'".$get_query->file_name."'".');" title="View Documents" class="btn btn-outline-primary">Detail</a>';

            $output_load .= '<tr>
            <td>'.$count.'</td>
            <td class="text-truncate">
               '.$doc_div.'
            </td>
            <td>'.$get_query->created_at.'</td>
            <td>'.$doc_view.'</td>
            </tr>';
            $count++;
        }
        return response()->json( ['listing_doc' => $output_load
        ] ); 
    }

    public function hr_updated_detail_emp_view(Request $request)
    {
        $credentials=[
            'emp_id'=>$request->input('emp_id'),
            'document'=>$request->input('document'),
            'file_name'=>$request->input('file_name'),
        ];

            if($credentials['document']=="Pay Slips"){
                $path="pay_slip";
            }
            if($credentials['document']=="F&F Statement"){
                $path="ff_statement";
            }
            if($credentials['document']=="Form 16"){
                $path="form16";
            }
            if($credentials['document']=="Relieving Letter"){
                $path="rel_letter";
            }
            if($credentials['document']=="Service Letter"){
                $path="ser_letter";
            }

            if($credentials['document']=="Bonus"){
                $path="bonus";
            }
            if($credentials['document']=="Performance Incentive"){
                $path="performance_incentive";
            }
            if($credentials['document']=="Sales Travel claim"){
                $path="sales_travel_claim";
            }
            if($credentials['document']=="Parental medical reimbursement"){
                $path="parental_medical_reimbursement";
            }
            // bonus document from hr
            if($credentials['document']=="F&F Document"){
                $path="f_and_f_document";
            }
            // type2
            if($credentials['document']=="PF"){
                $path="pf";
            }
            if($credentials['document']=="Gratuity"){
                $path="gratuity";
            }
            // end type 2
           
            $file_name=$credentials['file_name'];
            $show_div='
            <a href="../documents/'.$credentials['emp_id'].'/'.$path.'/'.$file_name.'" target="_blank">
            <button class="btn btn-outline-primary" tabindex="0" aria-controls="completed_query_tbl" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><span><i class="fa fa-file-pdf"></i>  '.$credentials['document'].'</span></button>
            </a>&nbsp;&nbsp;&nbsp;';



        $response= "success";
        return response()->json( ['response' => $response,'show_div'=>$show_div] );
    }


}
