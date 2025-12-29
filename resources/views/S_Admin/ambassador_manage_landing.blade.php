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
                    <h4>Alumni</h4>
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
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="frist_name">Name</label>
                                <input id="name" type="text" class="form-control" name="name" autofocus required>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="last_name">Employee ID</label>
                                <input id="emp_id" type="text" class="form-control" name="emp_id" required>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="frist_name">Pan Number</label>
                                <input id="pan_num" type="text" class="form-control" name="pan_num" autofocus >
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="last_name">DOB</label>
                                <input id="dob" type="date" class="form-control" name="dob" required>
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="contact">Contact Number</label>
                                  <input id="mobileno" type="text" class="form-control" name="mobileno" required>
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="email">Email</label>
                                  <input id="email" type="email" class="form-control" value="" name="email" required>
                              </div> 
                            </div>
                            <p id="form_resp" style="display:none;"></p>
                          
                            <div class="form-group col-md-4" style="float: right;">
                              <button type="submit" class="btn btn-primary btn-lg btn-block" id="add_ambassador_submit"><i class="fas fa-plus"></i>&nbsp;
                              Add Alumni  
                              </button> 

                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="add_bulk_ambassador_tab" role="tabpanel" aria-labelledby="profile-tab">

                      <div class="card-body">
                          <p>Sample Format <a href="{{asset('assets/emp_list_sam_doc/emp_list_sample.csv')}}">DOWNLOAD</a></p>
                          <form action="javascript:void(0)" id="bulk_upload_form" method="POST" enctype="multipart/form-data">
                          <div class="row">
 
                              <div class="form-group col-md-6">
                                <!-- <label>Choose file</label> -->
                                <input type="file" name="import_file" id="import_file" class="form-control" required>
                              </div>

                            <p id="bulk_up_resp" style="display:none;"></p>
                              
                              <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" id="bulk_upload_form_submit"><i class="fas fa-prescription-bottle-alt"></i>&nbsp;
                                Add Alumni  
                                </button> 
                              </div>
                          </div>
                          </form>
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