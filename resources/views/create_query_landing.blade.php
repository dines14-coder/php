<!DOCTYPE html>
<html lang="en">


<!-- basic-form.html  21 Nov 2019 03:54:41 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{$website_name}}</title>
  @include('Layouts.cmn_head_link')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      @include('Layouts.top_nav')
      @include('Layouts.left_nav')
      <!-- Main Content -->
      <div class="main-content"> 
        <section class="section">
          <div class="section-body">
            <div class="row">
              
              <div class="col-12 col-md-12 col-lg-12">
                
                <div class="card">
                  <div class="card-header">
                    <h4>Raise Your Query</h4>  
                  </div>
                  <div class="card-body">

                    <form action="javascript:void(0)" method="POST" id="query_form">
                   

                   


                    <div class="form-group" style="margin-bottom: 5px;">
                      <label class="d-block">Choose Document's</label>
                      <div class="form-check form-check-inline" id="pay_slip_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="pay_slips" value="Pay Slips">
                        <label class="form-check-label" for="pay_slips">Pay Slips</label>
                      </div>
                      <div class="form-check form-check-inline" id="ff_statement_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="f_f_statement" value="F&F Statement">
                        <label class="form-check-label" for="f_f_statement">F&F Statement</label>
                      </div>
                      <div class="form-check form-check-inline" id="form_16_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="form_16" value="Form 16">
                        <label class="form-check-label" for="form_16">Form 16</label>
                      </div>
                      <div class="form-check form-check-inline" id="rel_letter_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="relieving_letter" value="Relieving Letter">
                        <label class="form-check-label" for="relieving_letter">Relieving Letter</label>
                      </div>
                      <div class="form-check form-check-inline" id="ser_letter_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="service_letter" value="Service Letter">
                        <label class="form-check-label" for="service_letter">Service Letter</label>
                      </div>

                      <div class="form-check form-check-inline" id="bonus_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="bonus" value="Bonus">
                        <label class="form-check-label" for="bonus">Bonus</label>
                      </div>
                      <div class="form-check form-check-inline" id="performance_incentive_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="performance_incentive" value="Performance Incentive">
                        <label class="form-check-label" for="performance_incentive">Performance Incentive</label>
                      </div>
                      <div class="form-check form-check-inline" id="sales_travel_claim_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="sales_travel_claim" value="Sales Travel claim">
                        <label class="form-check-label" for="sales_travel_claim">Sales Travel claim</label>
                      </div>
                       <div class="form-check form-check-inline" id="parental_medical_reimbursement_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="parental_medical_reimbursement" value="Parental medical reimbursement">
                        <label class="form-check-label" for="parental_medical_reimbursement">Parental medical reimbursement</label>
                      </div>

                      <div class="form-check form-check-inline" id="others_check">
                        <input class="form-check-input f_type_1" name="document[]" type="checkbox" id="others" value="Others">
                        <label class="form-check-label" for="others">Others</label> 
                      </div>

                       <div class="form-check form-check-inline" id="pf_check">
                        <input class="form-check-input f_type_2" name="document[]" type="checkbox" id="pf" value="PF">
                        <label class="form-check-label" for="pf">PF</label>
                      </div>
                      <div class="form-check form-check-inline" id="gratuity_check">
                        <input class="form-check-input f_type_2" name="document[]" type="checkbox" id="gratuity" value="Gratuity">
                        <label class="form-check-label" for="gratuity">Gratuity</label>
                      </div>
                      <br>
                      <br>
                     
                    <div class="form-group">
                      <label>Remarks<span class="text-danger">*</span></label> 
                      <textarea class="form-control" name="remark" id="remark" required></textarea>
                    </div>
                    
                    <b id="query_resp" style="display:none;"></b>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" id="query_form_submit" type="submit">Submit Your Query</button>
                    </div>
                    </form>
                    <h4>Bank Account Changes</h4>
                    <br>
                    <div>
                      
                      @if($bank_status['status'] == 'Approved')
                        <h6 class = "badge badge-success">{{$bank_status['status']}}</h6>
                      
                      @elseif($bank_status['status'] == 'Rejected')
                        <h6 class = "badge badge-danger">{{$bank_status['status']}}</h6>
                      
                      @else
                        <h6 class = "badge badge-primary">{{$bank_status['status']}}</h6>
                      
                      @endif
                    </div>
                    <br>
                    <form action="javascript:void(0)" method="POST" id="bank_account_form">
                    <div class="row">
                        <label for="cheque" style="color:#616161;font-size:15px;margin-left:15px">Cheque Cancelled Copy :</label>
                        <input type="file" id="cheque" name="cheque"  class="col-3 mr-2" required = ''
                        @if($bank_status['status'] == 'Pending' || $bank_status['status'] == 'Approved')
                              disabled
                          @endif>
                        <label for="passbook" style="margin-left:50px;font-size:15px;color:#616161">Bank Passbook :</label>
                        <input type="file" id="passbook" name="passbook" class="col-3 mr-2" required=''
                        @if($bank_status['status'] == 'Pending' || $bank_status['status'] == 'Approved')
                              disabled
                          @endif>
                    </div>
                    
                    <br>
                    <!-- <b id="query_resp" style="display:none;"></b> -->
                    <div class="card-footer text-right">
                    <div id="message" style="color: blue;"></div>
                        <button class="btn btn-primary mr-1" id="bank_submit" type="submit"  @if($bank_status['status'] == 'Pending' || $bank_status['status'] == 'Approved')
                              disabled
                          @endif>Submit</button>
                    </div>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        @include('Layouts.query_pop')

        @include('Layouts.theme_setting')
      </div>
      @include('Layouts.footer')
    </div>
  </div>
  @include('Layouts.cmn_footer_link') 

  <script>
     $(document).ready(function () {
        $(".query_drop").addClass("active");
        $(".r_query_li").addClass("active");
        $(".r_query_a").addClass("toggled"); 
      })
  </script>

  <script src="{{asset('assets/new_add/js/create_query.js')}}"></script>
  <script src="{{asset('assets/new_add/js/account.js')}}"></script>

  <script>
    
      var query_form_submit="{{url('query_form_submit')}}"; 
      var check_valid_doc_upl="{{url('check_valid_doc_upl')}}"; 
      var bank_account_form="{{url('bank_account_form')}}"; 
  </script>
</body>
<!-- basic-form.html  21 Nov 2019 03:54:41 GMT -->
</html>