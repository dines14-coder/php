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
                    <h4>Daily Report</h4>
                    <!-- <button type="button" class="btn btn-warning btn-icon icon-left">
                        <i class="fas fa-envelope"></i> Sending.. <span id="send_mail_cnt" class="badge badge-transparent">0</span>
                    </button> -->

                    <input type="hidden" id="tab_type" name="tab_type">

                    <div class="col-md-4">
                      <div class="input-group">
                        <select name="slectbox_hr" class="form-control" id="slectbox_hr">
                          
                        </select> 
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="date" name="filter_date" id="filter_date" value="<?php echo date('Y-m-d');?>" class="form-control" style="height: 100%;"> 
                      </div>
                    </div>
                    
                  </div>
                  <div class="card-body">
                    
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="pending_query_tab" role="tabpanel" aria-labelledby="home-tab">

                          <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped data-table" id="daily_report">
                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                <thead> 
                                <tr>
                                    <th></th>
                                    <th class="text-center">
                                    #
                                    </th>
                                    <th>Ticket ID</th>
                                    <th>Emp ID</th>
                                    <th>Document</th>
                                    <th>Remark</th>
                                    <th>Updated by</th>
                                    <!-- <th>Status</th> -->
                                    <!-- <th>Action</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
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
  
<script id="details-template" type="text/x-handlebars-template">
@verbatim
<div class="table-responsive">
        <table class="table details-table " id="posts-{{emp_id}}">
            <thead>
                <tr>
                    <th>Emp ID</th>
                    <th>Emp Name</th>
                    <th>Pan No</th>
                    <th>DOB</th>
                    <th>Mobile No</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id="inner_tbody"></tbody>
        </table>
</div>
@endverbatim
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.2/handlebars.min.js"></script>


  <!-- <script src="{{asset('assets/bundles/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script> -->
  <!-- Page Specific JS File -->
  <!-- <script src="{{asset('assets/js/page/datatables.js')}}"></script> -->
  <!--Data Tables js-->
<script src="{{asset('plugins/bootstrap-datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datatable/js/buttons.colVis.min.js')}}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script src="{{asset('assets/new_add/admin_js/s_daily_report.js')}}"></script>

  <script> 
     $(document).ready(function () {
        $(".daily_report_li_m").addClass("active"); 
        $(".daily_report_a").addClass("toggled");
      })
  </script>
  
  <script>
      var get_s_admin_daily_report="{{url('get_s_admin_daily_report')}}";  

      var employee_detail="{{url('employee_detail')}}"; 
      var update_query="{{url('update_query_status')}}"; 
      var doc_upload_admin_submit="{{url('doc_upload_admin_submit')}}"; 
      var doc_updated_detail="{{url('doc_updated_detail')}}"; 
      var send_qry_stats_mail="{{url('send_qry_stats_mail')}}"; 
      

      var adm_get_emp_sel_box="{{url('adm_get_emp_sel_box')}}";
      
  </script>
  
</body>
<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->
</html>