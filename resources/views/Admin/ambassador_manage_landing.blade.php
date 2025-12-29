<!DOCTYPE html>
<html lang="en">


<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->  
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{$website_name}}</title>
  @include('Layouts.cmn_head_link') 
  <link rel="stylesheet" href="{{asset('assets/bundles/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
<!-- Template CSS -->
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}"> 
<link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <style>
    .ac_btn{
      margin: 5px 5px 5px 5px;
    }
    td.details-control {
    background: url('../assets/img/icon/details_open.png') no-repeat center center;
    cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../assets/img/icon/details_close.png') no-repeat center center;
    }
    .btn-group button{
      padding: 0.2rem 0.2rem;
    }
    [disabled]{
      pointer-events:none;
    }

    .scroll_design::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	border-radius: 10px;
	background-color: #F5F5F5;
}

.scroll_design::-webkit-scrollbar
{
	width: 12px;
	background-color: #F5F5F5;
}

.scroll_design::-webkit-scrollbar-thumb
{
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #ffe3de;
}



  </style>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      @include('Layouts.top_nav')
      @include('Layouts.Admin_left_nav')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-sm-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Add Alumni</h4>
                  </div>
                  <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#add_single_ambassador_tab" role="tab"
                          aria-controls="home" aria-selected="true"><i class="fas fa-plus"></i>&nbsp;Add Alumni</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#add_bulk_ambassador_tab" role="tab"
                          aria-controls="profile" aria-selected="false"><i class="fas fa-prescription-bottle-alt"></i>&nbsp;Add Bulk Alumni</a>
                      </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="add_single_ambassador_tab" role="tabpanel" aria-labelledby="home-tab">
                          <div class="card-body">
                          <form action="javascript:void(0)" id="add_ambassador_form" method="POST">
                            @csrf
                            <div class="row">
                              <div class="form-group col-md-3 clear_inp">
                                <label for="name">Name <span style="color:red">*</span></label>
                                <input id="name" type="text" class="form-control inp" name="name">
                                <span style="color: red;" class="name_error input_clr" id="name_error"></span>
                              </div>
                              <div class="form-group col-md-3 clear_inp">
                                <label for="emp_id">Employee ID <span style="color:red">*</span></label>
                                <input id="emp_id" type="text" class="form-control inp" name="emp_id">
                                <span style="color: red;" class="emp_id_error input_clr" id="emp_id_error"></span>
                              </div>
                              <div class="form-group col-md-3 clear_inp">
                                <label for="pan_num">Pan Number <span style="color:red">*</span></label>
                                <input id="pan_num" type="text" class="form-control inp" name="pan_num"  maxlength="10" pattern="[A-Za-z0-9]{10}" title="PAN number must be exactly 10 alphanumeric characters" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase()">
                                <span style="color: red;" class="pan_num_error input_clr" id="pan_num_error"></span>
                              </div>
                              <div class="form-group col-md-3 clear_inp">
                                <label for="dob">DOB <span style="color:red">*</span></label>
                                <input id="dob" type="date" class="form-control inp" name="dob">
                                <span style="color: red;" class="dob_error input_clr" id="dob_error"></span>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-3 clear_inp">
                                  <label for="mobileno">Contact Number <span style="color:red">*</span></label>
                                  <input id="mobileno" type="text" class="form-control inp" name="mobileno" maxlength="10" pattern="[0-9]{10}" title="Contact number must be exactly 10 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                  <span style="color: red;" class="mobileno_error input_clr" id="mobileno_error"></span>
                              </div>
                              <div class="form-group col-md-3 clear_inp">
                                  <label for="email">Email <span style="color:red">*</span></label>
                                  <input id="email" type="email" class="form-control inp" name="email">
                                  <span style="color: red;" class="email_error input_clr" id="email_error"></span>
                                </div>
                              <div class="form-group col-md-3 clear_inp">
                                <label for="type_of_leaving">Type of leaving <span style="color:red">*</span></label> 
                                <select name="type_of_leaving" class="form-control inp" id="type_of_leaving" >
                                  <option selected disabled value=""> Select Type of leaving</option>
                                  <option value="Relieved">Relieved</option>
                                  <option value="Terminated">Terminated</option>
                                  <option value="Abscond">Abscond</option>
                                  <option value="Transferred">Transferred</option>
                                </select> 
                                <span style="color: red;" class="type_of_leaving_error input_clr" id="type_of_leaving_error"></span>
                              </div>
                                <div class="form-group col-md-3 clear_inp">
                                    <label for="last_name">Last Working Date <span style="color:red">*</span></label>
                                    <input id="working_date" type="date" class="form-control inp" name="working_date" >
                                    <span style="color: red;" class="working_date_error input_clr" id="working_date_error"></span>
                                </div>
                            </div>
                            <p id="form_resp" style="display:none;"></p>
                            <div class="form-group col-md-3" style="float: right;">
                              <button type="submit" class="btn btn-primary btn-lg btn-block" id="add_ambassador_submit"><i class="fas fa-plus"></i>&nbsp;
                              Add Alumni  
                              </button> 
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="add_bulk_ambassador_tab" role="tabpanel" aria-labelledby="profile-tab">

                      <div class="card-body">
                          <p>Sample Format <a href="{{asset('assets/emp_list_sam_doc/emp_list_sample.csv')}}">DOWNLOAD</a> &nbsp;&nbsp;&nbsp;<b style="color:red;"><br>*Note <br>1) Date Format(YYYY-MM-DD) , <br>2) Type of leaving(Relieved,Terminated,Abscond,Transferred) </br>3) f_f_document(Yes,No) </p>
                          <form action="javascript:void(0)" id="bulk_upload_form" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="row">
 
                              <div class="form-group col-md-6">
                                <!-- <label>Choose file</label> -->
                                <input type="file" name="import_file" accept=".csv,.xls,.xlsx" onchange="checkextension(this)" id="import_file" class="form-control" required maxlength="5242880">
                              </div>
                            <p id="bulk_up_resp" style="display:none;"></p>
                              <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" id="bulk_upload_form_submit"><i class="fas fa-prescription-bottle-alt"></i>&nbsp;
                                Add Alumni  
                                </button> 
                              </div>
                          </div>
                          </form>
                          <div class="error_div " hidden="true" ></div>
                      </div>  
                      </div>

                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
        </section>
        @include('Layouts.query_manage_pop')
          
        @include('Layouts.theme_setting')
      </div> 
      @include('Layouts.footer')
    </div>
  </div>
  @include('Layouts.cmn_footer_link') 
  
 

<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.2/handlebars.min.js"></script>

  <!-- <script src="{{asset('assets/bundles/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script> -->
  <!-- Page Specific JS File -->

  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script src="{{asset('assets/new_add/admin_js/ambassador_manage_landing.js')}}"></script>

  <script>
     $(document).ready(function () {
        $(".ambassador_drop").addClass("active");
        $(".ambassador_a").addClass("toggled");
      })
  </script> 

  <script>
      var add_alumni_submit="{{url('add_alumni_submit')}}";  
      var amb_bulk_upl_submit="{{url('amb_bulk_upl_submit')}}";  
      

      

  </script>
  
</body>
<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->
</html>