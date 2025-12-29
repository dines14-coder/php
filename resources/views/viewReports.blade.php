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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
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
                                        <h4>F&F Report</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="completed_within_tat();" id="home-tab"data-toggle="tab" href="#completed_within_tat" role="tab"aria-controls="home" aria-selected="true"><i class="fas fa-exclamation-triangle"></i>&nbsp;Completed within TAT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="completed_beyond_tat();"id="profile-tab" data-toggle="tab" href="#completed_beyond_tat"role="tab" aria-controls="profile" aria-selected="false"><i class="far fa-edit"></i>&nbsp;Completed Beyond TAT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="pending_within_tat();" id="contact-tab"data-toggle="tab" href="#pending_within_tat" role="tab"aria-controls="contact" aria-selected="false"><i class="fas fa-check"></i>&nbsp;Pending within TAT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="pending_beyond_tat();" id="contact-tab"data-toggle="tab" href="#pending_beyond_tat" role="tab"aria-controls="contact" aria-selected="false"><i class="fas fa-check"></i>&nbsp;Pending within TAT</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="completed_within_tat" role="tabpanel"aria-labelledby="home-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table" id="completed_within_tat_tbl">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="text-center">#</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Received Date</th>
                                                                    <th>Received Time</th>
                                                                    <th>Completed Date</th>
                                                                    <th>Completed Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="completed_beyond_tat" role="tabpanel"aria-labelledby="profile-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="completed_beyond_tat_tbl">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="text-center">#</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Received Date</th>
                                                                    <th>Received Time</th>
                                                                    <th>Completed Date</th>
                                                                    <th>Completed Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="pending_within_tat" role="tabpanel" aria-labelledby="contact-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="pending_within_tat_tbl">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="text-center">#</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Received Date</th>
                                                                    <th>Received Time</th>
                                                                    <th>Completed Date</th>
                                                                    <th>Completed Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="pending_beyond_tat" role="tabpanel" aria-labelledby="contact-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table" id="pending_beyond_tat_tbl">
                                                            <h5>Records Found: <span class="total_res_show"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="text-center">#</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Received Date</th>
                                                                    <th>Received Time</th>
                                                                    <th>Completed Date</th>
                                                                    <th>Completed Time</th>
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
    <script src="{{ asset('assets/new_add/admin_js/view_report.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".v_ff_report").addClass("active");
            $(".v_ff_track_a").addClass("toggled");
        })
    </script>
</body>
<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->

</html>
