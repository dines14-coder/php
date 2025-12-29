<!DOCTYPE html>
<html lang="en">
<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $website_name }}</title>
    @include('Layouts.cmn_head_link')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .ac_btn {
            margin: 5px 5px 5px 5px;
        }

        td.details-control {
            background: url('../assets/img/icon/details_open.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('../assets/img/icon/details_close.png') no-repeat center center;
        }

        .btn-group button {
            padding: 0.2rem 0.2rem;
        }

        .scrolly2 {
            height: 530px;
            overflow-y: scroll;
        }

        .scrolly {
            height: 530px;
            overflow-y: scroll;
        }

        .select2-container--default .select2-selection--single {
            height: auto;
        }

        .select2 {
            width: 75% !important;
        }

        .heading_css {
            text-decoration: underline;
            text-align: center;
            margin-top: 10px;
        }

        .head {
            border: 1px solid black;
            color: black;
            font-size: 20px;
            border-bottom: none;
        }

        .modal-dialog {
            max-width: 1250px;
        }
        #bank_account_table{
          max-width: 
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
            <input type="hidden" value="<?php echo session('user_type'); ?>" id="user_type">
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Bank Account Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="pending_table();" id="home-tab"data-toggle="tab" href="#bank_detail" role="tab"aria-controls="home" aria-selected="true"><i class="fas fa-exclamation-triangle"></i>&nbsp;pending_table</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="completed_table();"id="profile-tab" data-toggle="tab" href="#bank_completed_detail"role="tab" aria-controls="profile" aria-selected="false"><i class="far fa-edit"></i>&nbsp;completed_table</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="rejected_table();" id="contact-tab"data-toggle="tab" href="#bank_rejected_detail" role="tab"aria-controls="contact" aria-selected="false"><i class="fas fa-check"></i>&nbsp;rejected_table</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="bank_detail" role="tabpanel"aria-labelledby="home-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table" id="bank_account_table">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                <th>ID</th>
                                                                <th>Employee ID</th>
                                                                <th>Cheque</th>
                                                                <th>Passbook</th>
                                                                <th>Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="bank_completed_detail" role="tabpanel"aria-labelledby="profile-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="bank_completed_table">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                <th>ID</th>
                                                                <th>Employee ID</th>
                                                                <th>Cheque</th>
                                                                <th>Passbook</th>
                                                                <th>Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="bank_rejected_detail" role="tabpanel" aria-labelledby="contact-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="bank_rejected_table">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                <th>ID</th>
                                                                <th>Employee ID</th>
                                                                <th>Cheque</th>
                                                                <th>Passbook</th>
                                                                <th>Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
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
                
            </div>
            @include('Layouts.footer')
        </div>
    </div>
    @include('Layouts.cmn_footer_link')
    <script>
     $(document).ready(function () {
        $(".bank_details_m").addClass("active");
        $(".bank_det_m").addClass("active");
      })
  </script>

    <script id="details-template" type="text/x-handlebars-template">

</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.2/handlebars.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('assets/new_add/js/account.js') }}"></script>
    
</body>
<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->

</html>
