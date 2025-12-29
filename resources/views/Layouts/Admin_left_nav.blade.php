<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('assets/img/logo.png') }}" class="header-logo" /> <span
                    class="logo-name"></span>
            </a>
        </div>
        <ul class="sidebar-menu">

            @if (session()->get('user_type') == 'F_F_HR')
                <li class="menu-header">Main</li>
                <li class="dropdown dashbord_drop">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link dashbord_a"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>

                <li class="menu-header">Query</li>
                <li class="dropdown query_drop">
                    <a href="{{ route('query_manage_landing') }}" class="nav-link query_a"><i
                            data-feather="briefcase"></i><span>Query Management</span></a>
                </li>

                <li class="dropdown v_ff_track_drop">
                    <a href="{{ route('f_f_tracker_landing') }}" class="nav-link v_ff_track_a"><i
                            data-feather="file-text"></i><span>F&F Tracker</span></a>
                </li>

                <li class="dropdown document_drop f_f_rep_">
                    <a href="{{ route('f_f_reports') }}" class="nav-link f_f_rep"><i
                            data-feather="file-text"></i><span>F&F Reports</span></a>
                </li>

                <li class="dropdown ambassador_drop">
                    <a href="{{ route('alumni_manage_landing') }}" class="nav-link ambassador_a"><i
                            data-feather="user-plus"></i><span>Add Alumni</span></a>
                </li>
                <li class="dropdown v_ambassador_drop">
                    <a href="{{ route('view_alumni_landing') }}" class="nav-link v_ambassador_a"><i
                            data-feather="user"></i><span>Alumni</span></a>
                </li>

                <li class="dropdown document_drop">
                    <a href="{{ route('document_manage_landing') }}" class="nav-link document_a"><i
                            data-feather="file-text"></i><span>Document Management</span></a>
                </li>




                <li class="dropdown reg_ambassador_drop">
                    <a href="{{ route('view_reg_alumni_landing') }}" class="nav-link reg_ambassador_a"><i
                            class="fa fa-id-badge"></i><span>Registered Alumni</span></a>
                </li>
                <li class="sidebar-item v_ff_report">
                    <a href="{{route('report_page')}}" class='sidebar-link pass_a'>
                    <i class="fa fa-file" aria-hidden="true"></i>
                        <span>Reports</span>
                    </a>
                </li>
            @endif



            @if (session()->get('user_type') == 'Payroll_QC' || session()->get('user_type') == 'SME')
                <li class="menu-header">Main</li>
                @if (session()->get('user_type') == 'Payroll_QC')
                    <li class="dropdown dashbord_drop">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link dashbord_a"><i
                                data-feather="monitor"></i><span>Dashboard</span></a>
                    </li>
                    <li class="dropdown revert_drop">
                        <a href="{{ route('revert') }}" class="nav-link revert_details"><i {{-- class="fas fa-undo" --}}
                                data-feather="refresh-ccw"></i><span>Revert Details</span></a>
                    </li>
                    <li class="dropdown document_drop">
                        <a href="{{ route('s_document_manage_landing') }}" class="nav-link document_a"><i
                                data-feather="file-text"></i><span>Document Status</span></a>
                    </li>
                    <li class="dropdown qc_mis_drop">
                        <a href="{{ route('qc_mis') }}" class="nav-link qc_mis_details"><i {{-- class="fas fa-undo" --}}
                                data-feather="hard-drive"></i><span>QC Mis Report</span></a>
                    </li>
                @endif
                <li class="dropdown v_ff_track_drop">
                    <a href="{{ route('f_f_tracker_landing') }}" class="nav-link v_ff_track_a"><i
                            data-feather="file-text"></i><span>F&F Tracker</span></a>
                </li>
                </li>
                <li class="sidebar-item v_ff_report">
                    <a href="{{route('report_page')}}" class='sidebar-link pass_a'>
                    <i class="fa fa-file" aria-hidden="true"></i>
                        <span>F & F Reports</span>
                    </a>
                </li>
            @endif


            @if (session()->get('user_type') == 'Claims' ||
                    session()->get('user_type') == 'Payroll_Finance' ||
                    session()->get('user_type') == 'Payroll_HR' ||
                    session()->get('user_type') == 'Payroll_IT' ||
                    session()->get('user_type') == 'IT-INFRA')

                <li class="menu-header">Main</li>
                <li class="dropdown f_f_c_drop">
                    <a href="{{ route('F_and_F_document.form') }}" class="nav-link f_f_c_a"><i
                            data-feather="flag"></i><span>F&F Check point</span></a>
                </li>
                @if (session()->get('user_type') == 'Payroll_Finance' || session()->get('user_type') == 'Payroll_HR')
                    <li class="dropdown v_ff_track_drop">
                        <a href="{{ route('f_f_tracker_landing') }}" class="nav-link v_ff_track_a"><i
                                data-feather="file-text"></i><span>F&F Tracker</span></a>
                    </li>
                @endif

                </li>
                @if (session()->get('user_type') == 'Claims' ||
                        session()->get('user_type') == 'Payroll_Finance' ||
                        session()->get('user_type') == 'Payroll_HR' ||
                        session()->get('user_type') == 'HR-LEAD')
                    <li class="menu-header">Query</li>
                    <li class="dropdown query_drop">
                        <a href="{{ route('query_manage_landing') }}" class="nav-link query_a"><i
                                data-feather="briefcase"></i><span>Query Management</span></a>
                    </li>
                    <li class="dropdown v_ambassador_drop">
                        <a href="{{ route('view_alumni_landing') }}" class="nav-link v_ambassador_a"><i
                                data-feather="user"></i><span>Alumni</span></a>
                    </li>
                @endif

            @endif



            @if (session()->get('user_type') == 'F_F_Admin')
                <li class="menu-header">Main</li>
                <li class="dropdown dashbord_drop">
                    <a href="{{ route('s_admin.dashboard') }}" class="nav-link dashbord_a"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>

                <li class="menu-header">Query</li>
                <li class="dropdown query_drop">
                    <a href="{{ route('s_query_manage_landing') }}" class="nav-link query_a"><i
                            data-feather="briefcase"></i><span>Query Management</span></a>
                </li>

                <li class="dropdown document_drop">
                    <a href="{{ route('s_document_manage_landing') }}" class="nav-link document_a"><i
                            data-feather="file-text"></i><span>Document Management</span></a>
                </li>

                <li class="sidebar-item daily_report_li_m">
                    <a href="{{ route('s_daily_report') }}" class='sidebar-link daily_report_a'>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Daily Report</span>
                    </a>
                </li>
            @endif

            @if (session()->get('user_type') == 'Super_Admin')
                <li class="menu-header">Main</li>
                <li class="dropdown dashbord_drop">
                    <a href="{{ route('p_s_admin.dashboard') }}" class="nav-link dashbord_a"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>


                <li class="menu-header">Query</li>
                <li class="dropdown query_drop">
                    <a href="{{ route('p_s_query_manage_landing') }}" class="nav-link query_a"><i
                            data-feather="briefcase"></i><span>Query Management</span></a>
                </li>

                <li class="dropdown document_drop">
                    <a href="{{ route('p_s_document_manage_landing') }}" class="nav-link document_a"><i
                            data-feather="file-text"></i><span>Document Management</span></a>
                </li>

                <li class="dropdown v_ambassador_drop">
                    <a href="{{ route('view_alumni_landing') }}" class="nav-link v_ambassador_a"><i
                            data-feather="user"></i><span>Alumni</span></a>
                </li>

                <li class="sidebar-item daily_report_li_m">
                    <a href="{{ route('p_s_daily_report') }}" class='sidebar-link daily_report_a'>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Daily Report</span>
                    </a>
                </li>
            @endif


            @if (session()->get('user_type') == 'HR-LEAD')
                <li class="menu-header">Main</li>
                <li class="dropdown dashbord_drop">
                    <a href="{{ route('p_s_admin.dashboard') }}" class="nav-link dashbord_a"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>

                <li class="dropdown f_f_c_drop">
                    <a href="{{ route('F_and_F_document.form') }}" class="nav-link f_f_c_a"><i
                            data-feather="flag"></i><span>F&F Check point</span></a>
                </li>

                <li class="menu-header">Query</li>
                <li class="dropdown query_drop">
                    <a href="{{ route('query_manage_landing') }}" class="nav-link query_a"><i
                            data-feather="briefcase"></i><span>Query Management</span></a>
                </li>

                <li class="dropdown document_drop">
                    <a href="{{ route('document_manage_landing') }}" class="nav-link document_a"><i
                            data-feather="file-text"></i><span>Document Management</span></a>
                </li>

                <li class="dropdown ambassador_drop">
                    <a href="{{ route('alumni_manage_landing') }}" class="nav-link ambassador_a"><i
                            data-feather="user-plus"></i><span>Add Alumni</span></a>
                </li>


                <li class="dropdown v_ambassador_drop">
                    <a href="{{ route('view_alumni_landing') }}" class="nav-link v_ambassador_a"><i
                            data-feather="user"></i><span>Alumni</span></a>
                </li>

                <li class="sidebar-item daily_report_li_m">
                    <a href="{{ route('p_s_daily_report') }}" class='sidebar-link daily_report_a'>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Daily Report</span>
                    </a>
                </li>
            @endif
            @if (session()->get('user_type') == 'Payroll_Finance' ||
                    session()->get('user_type') == 'Payroll_HR')
            <li class="sidebar-item v_ff_report">
                <a href="{{route('report_page')}}" class='sidebar-link pass_a'>
                <i class="fa fa-file" aria-hidden="true"></i>
                    <span> F & F Reports</span>
                </a>
            </li>
            @endif



            <li class="sidebar-item pass_upd_m">
                <a href="{{ route('admin_password_update_landing') }}" class='sidebar-link pass_a'>
                    <i class="fa fa-key" aria-hidden="true"></i>
                    <span>Update password</span>
                </a>
            </li>
            @if (session()->get('user_type') == 'Payroll_Finance')
            <li class="sidebar-item bank_details_m">
                <a href="{{ route('account') }}" class='sidebar-link bank_det_m'>
                    <i class="fa fa-bank" aria-hidden="true"></i>
                    <span>Bank Details</span>
                </a>
            </li>
            @endif
        </ul>
    </aside>
</div>
