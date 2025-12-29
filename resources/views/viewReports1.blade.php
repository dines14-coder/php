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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
        .nav-tabs .nav-item .nav-link{
            color:black;
        }
        #myTab.nav-tabs .nav-item .nav-link.active {
            border-bottom: 2px solid #3eb9f3; 
            border-top: none;
            border-left: none;
            border-right: none;
        }
        #myTab.nav-tabs .nav-item .nav-link:hover {
            border-bottom: 2px solid #bbafaf; 
            border-top: none;
            border-left: none;
            border-right: none;/* Change color of bottom border on hover */
        }
        #myTab.nav-tabs .nav-link {
        border: 0px solid transparent;
         border-top-left-radius: 0.25rem;
         border-top-right-radius: 0.25rem;
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
                                        <input type="hidden" name="tab_type" id="tab_type">
                                        <input type="hidden" name="table_name" id="table_name">
                                        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" id="myTab" role="tablist" style="font-size: 16px;">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="daily_report();" id="home-tab"data-toggle="tab" href="#daily_report" role="tab"aria-controls="home" aria-selected="true"><i class="fas fa-calendar-day"></i>&nbsp;Daily Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="weekly_report();"id="profile-tab" data-toggle="tab" href="#weekly_report"role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-calendar-week"></i>&nbsp;Weekly Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="function_report();" id="contact-tab"data-toggle="tab" href="#function_report" role="tab"aria-controls="contact" aria-selected="false"><i class="fas fa-sync"></i>&nbsp;Function wise Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="ageing_report();" id="contact-tab"data-toggle="tab" href="#ageing_report" role="tab"aria-controls="contact" aria-selected="false"><i class="far fa-hourglass"></i>&nbsp;Ageing Report</a>
                                            </li>
                                        </ul>
                                        <ul class="nav nav-tabs  mb-3" id="category_tab" role="tablist" style="font-size: 16px;">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="completed_within_tat();" id="completed_within_tat_tab"data-toggle="tab" href="#completed_within_tat" role="tab"aria-controls="home" aria-selected="true"><i class="fas fa-check"></i>&nbsp;Completed within TAT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="completed_beyond_tat();"id="completed_beyond_tat_tab" data-toggle="tab" href="#completed_beyond_tat"role="tab" aria-controls="profile" aria-selected="false"><i class="far fa-clock"></i>&nbsp;Completed Beyond TAT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="pending_within_tat();" id="pending_within_tat_tab"data-toggle="tab" href="#pending_within_tat" role="tab"aria-controls="contact" aria-selected="false"><i class="fas fa-hourglass-end"></i>&nbsp;Pending within TAT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="pending_beyond_tat();" id="pending_beyond_tat_tab"data-toggle="tab" href="#pending_beyond_tat" role="tab"aria-controls="contact" aria-selected="false"><i class="fas fa-exclamation-triangle"></i>&nbsp;Pending Beyond TAT</a>
                                            </li>
                                        </ul>

                                        <div class="d-none " id="view_function_filter" >
                                        <button type ="button" id="show_filter" class="btn btn-sm btn-primary">Filter</button>
                                            <div class="filter_div" style="display: none">
                                                    <select name="function" id="function_filter" class="form-control form-control-sm">
                                                        <option value="" id="select">Select</option>
                                                        <option value="hrss">HRSS</option>
                                                        <option value="payroll">Payroll</option>
                                                        <option value="qc">Quality Control</option>
                                                        <option value="finance">Finance</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="daterangefilter d-none float-right" style="display:flex;">
                                        <label>Date Range: <input type="text" name="daterange_f" id="daterange_f"  class ="form-control form-control-sm"  value="" /></label>
                                        </div>
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
                                                                    <!-- <th>Received Date</th>
                                                                    <th>Received Time</th> -->
                                                                    <th>Completed Date</th>
                                                                    <th>Completed Time</th>
                                                                    <th>Age</th>
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
                                                                    <!-- <th>Received Date</th>
                                                                    <th>Received Time</th> -->
                                                                    <th>Completed Date</th>
                                                                    <th>Completed Time</th>
                                                                    <th>Age</th>
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
                                                                    <th>Age</th>
                                                                    <!-- <th>Completed Date</th>
                                                                    <th>Completed Time</th> -->
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
                                                                    <th>Age</th>
                                                                    <!-- <th>Completed Date</th>
                                                                    <th>Completed Time</th> -->
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
    <script src="{{ asset('assets/new_add/admin_js/view_report1.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".v_ff_report").addClass("active");
            $(".v_ff_track_a").addClass("toggled");

            $("#show_filter").on("click", function () {
            $(".filter_div").toggle();
            $(".filter_div").css('display','inline-block')
            $('#function_filter').val('') // Toggle visibility of the filter options
        });
        })
    </script>
</body>
<!-- tabs.html  21 Nov 2019 03:54:41 GMT -->

</html>
