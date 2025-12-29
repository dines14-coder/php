<!-- pdf.blade.php -->

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>

    <style>
#ff_checkpoint_tbl {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#ff_checkpoint_tbl td, #ff_checkpoint_tbl th {
  border: 1px solid #ddd;
  padding: 8px;
}

#ff_checkpoint_tbl tr:nth-child(even){background-color: #f2f2f2;}

#ff_checkpoint_tbl tr:hover {background-color: #ddd;}

#ff_checkpoint_tbl th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>


  </head>
  <body>
      <!-- document show pop -->
            <div id="data">
            <table class="table table-bordered" id="ff_checkpoint_tbl">
              <tr style="width:2px;"><td colspan=""><b>EmpLoyee ID:{{$get_emp[0]->emp_id}}</b></td><td colspan="4"><b>Employee Name:{{$get_emp[0]->emp_name}}</b></td></tr>
              <tr><td class="text-dark" width="5%"><b>S.No</b></td><td class="text-dark" width="75%"><b>Questions</b></td><td class="text-dark" width="5%"><b>Ratings</b></td><td class="text-dark" width="10%"><b>Remarks</b></td><td class="text-dark" width="5%"><b>Checked By</b></td></tr>';
              <tr>
              @php
              $i=1;
              @endphp
              @foreach($get_questions as $questions)
                  <tr>
                      <td>{{$i}}</td>
                      <td>{{ $questions->questions }}</td>

                      @php 
                      $get_emp_q_rec = \App\Models\f_f_check_point::where(['question_id' => $questions->question_id])
                      ->where(['emp_id' => $get_emp[0]->emp_id])->get()
                      @endphp

                      @if(isset($get_emp_q_rec[0]))
                        {
                            @if($get_emp_q_rec[0]->rating==""){
                                <td style="color:red;">-</td>
                                <td style="color:red;">-</td>
                                <td style="color:red;">-</td>
                            }
                            @elseif($get_emp_q_rec[0]->rating=="Yes"){
                                <td>{{$get_emp_q_rec[0]->rating}}</td>
                                <td>{{$get_emp_q_rec[0]->remarks}}</td>
                                <td>{{$get_emp_q_rec[0]->created_by}}</td>
                            }
                            @elseif($get_emp_q_rec[0]->rating!=="Yes"){
                                <td style="background-color:#f305052b;">{{$get_emp_q_rec[0]->rating}}</td>
                                <td>{{$get_emp_q_rec[0]->remarks}}</td>
                                <td>{{$get_emp_q_rec[0]->created_by}}</td>
                            }
                        @endif
                        }
                        @else{
                            <td style="color:red;">-</td>
                            <td style="color:red;">-</td>
                            <td style="color:red;">-</td>
                        }
                        @endif
                        
                      
                  </tr>

                @php
                $i++;
                @endphp
                
              @endforeach
              
              </tr>
            </table>

            </div>
        
<!-- document show pop end -->
 
 
  </body>
</html>