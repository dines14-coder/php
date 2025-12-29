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
            height: 600px;
            overflow-y: scroll;
        }

        .scrolly {
            height: 600px;
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

        @media screen and (min-width: 1500px) {
            .modal-dialog {
                max-width: 1550px;
            }
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
            <input type="hidden" value="<?php echo session()->get('user_type'); ?>" id="user_type">
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>F&F Reports</h4>
                                        <input type="hidden" value="pending" id="tab_type">
                                    </div>
                                    <div class="ml-5 row">
                                        <div class="col-md-4">
                                            <label>Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                placeholder="Start Date" autocomplete="off">
                                        </div>
                                        <div class="col-md-4">
                                            <label>End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                placeholder="End Date" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <button class="btn btn-primary" id='filter_btn'>Submit</button>
                                            <button class="btn btn-danger" id='reset_btn'>Reset</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="pending_tab_click();" id="home-tab"
                                                    data-toggle="tab" href="#pending_tab" role="tab"
                                                    aria-controls="home-tab" aria-selected="true">&nbsp;F&F Pending</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="inprogress_tab_click();" id="profile-tab"
                                                    data-toggle="tab" href="#inprogress_tab" role="tab"
                                                    aria-controls="profile-tab" aria-selected="false">&nbsp;F&F In
                                                    progress</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="completed_tab_click();" id="contact-tab"
                                                    data-toggle="tab" href="#completed_tab" role="tab"
                                                    aria-controls="contact-tab" aria-selected="false">&nbsp;F&F
                                                    Statement</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="rel_and_service_tab_click();"
                                                    id="rel_serve-tab" data-toggle="tab" href="#rel_serve_tab"
                                                    role="tab" aria-controls="rel_serve-tab"
                                                    aria-selected="false">&nbsp;Relieving Letter & Service Letter
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="queries_solved_click();"
                                                    id="queries_solved-tab" data-toggle="tab"
                                                    href="#queries_solved_tab" role="tab"
                                                    aria-controls="queries_solved-tab"
                                                    aria-selected="false">&nbsp;No.of Queries Solved</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="unresolved_tab_click();"
                                                    id="unresolved-tab" data-toggle="tab" href="#unresolved_tab"
                                                    role="tab" aria-controls="queries_solved-tab"
                                                    aria-selected="false">&nbsp;Unresolved Queries</a>
                                            </li>
                                            <li class="nav-item">
                                                        <a class="nav-link" onclick="ffdetail_tab_click();" id="ffdetail-tab"
                                                            data-toggle="tab" href="#ff_tab" role="tab"
                                                            aria-controls="ffdetail-tab" aria-selected="true">&nbsp;F&F Detail Report</a>
                                                    </li>
                                        </ul>
                                       
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="pending_tab" role="tabpanel"
                                                aria-labelledby="home-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="pending_tbl">
                                                            <h5>Records Found: <span
                                                                    class="total_res_show_pending"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>S.No</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Type of leaving</th>
                                                                    <th>LWD</th>
                                                                    <th>Stagegate</th>
                                                                    <th>Created At</th>
                                                                    <th>Relieving Letter</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="inprogress_tab" role="tabpanel"
                                                aria-labelledby="profile-tab">

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="in_progress_tbl">
                                                            <h5>Records Found: <span
                                                                    class="total_res_show_in_progress"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>S.No</th>
                                                                    <th>Emp ID</th>
                                                                    {{-- <th>Emp Name</th> --}}
                                                                    <th>SG1(HRSS - HR001) Received Date</th>
                                                                    <th>SG1(HRSS - HR001) Completed Date</th>
                                                                    <th>SG2(Payroll HR - PRHR001) Received Date</th>
                                                                    <th>SG2(Payroll HR - PRHR001) Completed Date</th>
                                                                    <th>SG3(Payroll Finance - PRFN001) Received Date</th>
                                                                    <th>SG3(Payroll Finance - PRFN001) Completed Date</th>
                                                                    <!-- <th>SG4(SME - SME001) Received Date</th>
                                                                    <th>SG4(SME - SME001) Completed Date</th> -->
                                                                    <th>SG4(Payroll QC - PRQC001) Received Date</th>
                                                                    <th>SG4(Payroll QC - PRQC001) Completed Date</th>
                                                                    <th>SG5(HRSS - HR001) Received Date</th>
                                                                    <th>SG5(HRSS - HR001) Completed Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="completed_tab" role="tabpanel"
                                                aria-labelledby="contact-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="f_f_completed_tbl">
                                                            <h5>Records Found: <span
                                                                    class="total_res_show_f_f_completed"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>S.No</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Type of leaving</th>
                                                                    <th>LWD</th>
                                                                    <th>Stagegate</th>
                                                                    <th>Provided Date</th>
                                                                    <th>Relieving Letter</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade" id="rel_serve_tab" role="tabpanel"
                                                aria-labelledby="rel_serve-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="relieving_service_tbl">
                                                            <h5>Records Found: <span
                                                                    class="total_res_show_relieving_service"></span>
                                                            </h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>S.No</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Type of leaving</th>
                                                                    <th>LWD</th>
                                                                    <th>Stagegate</th>
                                                                    <th>Service Letter</th>
                                                                    <th>Relieving Letter</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                              
                                            <!-- <div class="tab-pane fade show " id="ff_tab" role="tabpanel"
                                                aria-labelledby="ffdetail-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="pending_tbl1">
                                                            <h5>Records Foundh: <span
                                                                    class="total_res_show_pending"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Supervisor Clearance</th>
                                                                    <th>Commercial / Admin Clearance</th>
                                                                    <th>Finance Clearance</th>
                                                                    <th>IT Clearance</th>
                                                                    <th>Grade Set</th>
                                                                    <th>Grade</th>
                                                                    <th>Department</th>
                                                                    <th>Work Location</th>
                                                                    <th>Supervisor Name</th>
                                                                    <th>Reviewer Name</th>
                                                                    <th>Headquarters</th>
                                                                    <th>HRBP Name</th>
                                                                    <th>Last Working Date</th>
                                                                    <th>Seperation Date</th>
                                                                    <th>Date Of Joining</th>
                                                                    <th>Date Of Resignation</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                </div>
                                            </div> -->

                                            <div class="tab-pane fade" id="queries_solved_tab" role="tabpanel"
                                                aria-labelledby="queries_solved-tab">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" onclick="payroll_related_tab();"
                                                            id="payroll-tab" data-toggle="tab" href="#payroll_tab"
                                                            role="tab" aria-controls="payroll-tab"
                                                            aria-selected="true">&nbsp;Payroll</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" onclick="hrss_related_tab();"
                                                            id="hrss-tab" data-toggle="tab" href="#hrss_tab"
                                                            role="tab" aria-controls="hrss-tab"
                                                            aria-selected="true">&nbsp;HRSS</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="payroll_tab"
                                                        role="tabpanel" aria-labelledby="payroll-tab">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped data-table"
                                                                    id="payroll_tbl">
                                                                    <h5>Records Found: <span
                                                                            class="total_res_show_payroll"></span>
                                                                    </h5>
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>S.No</th>
                                                                            <th>Ticket ID</th>
                                                                            <th>Emp ID</th>
                                                                            <th>Emp Name</th>
                                                                            <th>Type of leaving</th>
                                                                            <th>LWD</th>
                                                                            <th>Query Document</th>
                                                                            <th>Query Raised Date</th>
                                                                            <th>Query Solved Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="hrss_tab" role="tabpanel"
                                                        aria-labelledby="hrss-tab">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped data-table"
                                                                    id="hrss_tbl">
                                                                    <h5>Records Found: <span
                                                                            class="total_res_show_hrss"></span>
                                                                    </h5>
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>S.No</th>
                                                                            <th>Ticket ID</th>
                                                                            <th>Emp ID</th>
                                                                            <th>Emp Name</th>
                                                                            <th>Type of leaving</th>
                                                                            <th>LWD</th>
                                                                            <th>Query Document</th>
                                                                            <th>Query Raised Date</th>
                                                                            <th>Query Solved Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="unresolved_tab" role="tabpanel"
                                                aria-labelledby="unresolved_tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="unresolved_tbl">
                                                            <h5>Records Found: <span
                                                                    class="total_res_show_unresolved"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>S.No</th>
                                                                    <th>Ticket ID</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Emp Name</th>
                                                                    <th>Type of leaving</th>
                                                                    <th>LWD</th>
                                                                    <th>Query Document</th>
                                                                    <th>Query Raised Date</th>
                                                                    <th>Query Solved Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show" id="ff_tab" role="tabpanel"
                                                aria-labelledby="ffdetail-tab">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped data-table"
                                                            id="ffdetail_tbl">
                                                            <h5>Records Found: <span
                                                                    class="total_res_show_pending"></span></h5>
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>S.No</th>
                                                                    <th>Emp ID</th>
                                                                    <th>Supervisor Clearance</th>
                                                                    <th>Commercial / Admin Clearance</th>
                                                                    <th>Finance Clearance</th>
                                                                    <th>IT Clearance</th>
                                                                    <th>Grade Set</th>
                                                                    <th>Grade</th>
                                                                    <th>Department</th>
                                                                    <th>Work Location</th>
                                                                    <th>Supervisor Name</th>
                                                                    <th>Reviewer Name</th>
                                                                    <th>Headquarters</th>
                                                                    <th>HRBP Name</th>
                                                                    <th>Last Working Date</th>
                                                                    <th>Seperation Date</th>
                                                                    <th>Date Of Joining</th>
                                                                    <th>Date Of Resignation</th>
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
                        </div>
                </section>
                {{-- @include('Layouts.f_f_tracker_inp_pop')  --}}
                @include('Layouts.theme_setting')
                {{-- @include('Layouts.revert_docs_pop') --}}
                @include('Layouts.f_and_f_document_popup')
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



    <!-- <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script> -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Page Specific JS File -->
    <!-- <script src="{{ asset('assets/js/page/datatables.js') }}"></script> -->
    <!--Data Tables js-->
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


    <script src="{{ asset('assets/new_add/admin_js/f_f_reports.js') }}"></script>




    <script>
        $(document).ready(function() {
            $(".f_f_rep_").addClass("active");
            $(".f_f_rep").addClass("toggled");
        })
    </script>



</body>

</html>
