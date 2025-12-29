<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{route('admin.dashboard')}}"> <img alt="image" src="{{asset('assets/img/logo.png')}}" class="header-logo" /> <span
            class="logo-name"></span>
        </a>
      </div>
      <ul class="sidebar-menu"> 

      @if(session()->get('user_type')=="F_F_HR") 

        <li class="menu-header">Main</li>
        <li class="dropdown dashbord_drop">
          <a href="{{route('admin.dashboard')}}" class="nav-link dashbord_a"><i data-feather="monitor"></i><span>Dashboard</span></a>
        </li>
       
        <li class="menu-header">Query</li>
        <li class="dropdown query_drop">
          <a href="{{route('query_manage_landing')}}" class="nav-link query_a"><i
              data-feather="briefcase"></i><span>Query Management</span></a>
        </li>

        <li class="dropdown ambassador_drop">
          <a href="{{route('alumni_manage_landing')}}" class="nav-link ambassador_a"><i
              data-feather="user-plus"></i><span>Add Alumni</span></a>
        </li>
        <li class="dropdown v_ambassador_drop">
          <a href="{{route('view_alumni_landing')}}" class="nav-link v_ambassador_a"><i
              data-feather="user"></i><span>Alumni</span></a>
        </li>

        <li class="dropdown document_drop">
          <a href="{{route('document_manage_landing')}}" class="nav-link document_a"><i
              data-feather="file-text"></i><span>Document Management</span></a>
        </li>

        <li class="dropdown reg_ambassador_drop">
          <a href="{{route('view_reg_alumni_landing')}}" class="nav-link reg_ambassador_a"><i class="fa fa-id-badge"></i><span>Registered Alumni</span></a>
        </li>

        <li class="sidebar-item pass_upd_m">
            <a href="{{route('report_page')}}" class='sidebar-link pass_a'>
            <i class="fa fa-key" aria-hidden="true"></i>
                <span>Reports</span>
            </a>
        </li>
        
      @endif

      


      @if(session()->get('user_type')=="F_F_Admin") 

        <li class="menu-header">Main</li>
        <li class="dropdown dashbord_drop">
          <a href="{{route('s_admin.dashboard')}}" class="nav-link dashbord_a"><i data-feather="monitor"></i><span>Dashboard</span></a>
        </li>
       
        <li class="menu-header">Query</li> 
        <li class="dropdown query_drop">
          <a href="{{route('s_query_manage_landing')}}" class="nav-link query_a"><i
              data-feather="briefcase"></i><span>Query Management</span></a>
        </li>

        <li class="dropdown document_drop">
          <a href="{{route('s_document_manage_landing')}}" class="nav-link document_a"><i
              data-feather="file-text"></i><span>Document Management</span></a>
        </li>

        <li class="sidebar-item daily_report_li_m">
            <a href="{{route('s_daily_report')}}" class='sidebar-link daily_report_a'>
            <i class="fa fa-calendar" aria-hidden="true"></i>
                <span>Daily Report</span>
            </a>
        </li>

      @endif

      @if(session()->get('user_type')=="Super_Admin") 

        <li class="menu-header">Main</li>
        <li class="dropdown dashbord_drop">
          <a href="{{route('p_s_admin.dashboard')}}" class="nav-link dashbord_a"><i data-feather="monitor"></i><span>Dashboard</span></a>
        </li>
       

        <li class="menu-header">Query</li> 
        <li class="dropdown query_drop">
          <a href="{{route('p_s_query_manage_landing')}}" class="nav-link query_a"><i
              data-feather="briefcase"></i><span>Query Management</span></a>
        </li>

        <li class="dropdown document_drop">
          <a href="{{route('p_s_document_manage_landing')}}" class="nav-link document_a"><i
              data-feather="file-text"></i><span>Document Management</span></a>
        </li>

        <li class="dropdown v_ambassador_drop">
          <a href="{{route('view_alumni_landing')}}" class="nav-link v_ambassador_a"><i
              data-feather="user"></i><span>Alumni</span></a>
        </li>

        <li class="sidebar-item daily_report_li_m">
            <a href="{{route('p_s_daily_report')}}" class='sidebar-link daily_report_a'>
            <i class="fa fa-calendar" aria-hidden="true"></i>
                <span>Daily Report</span>
            </a>
        </li>

       
      @endif

        
        
        <li class="sidebar-item pass_upd_m">
            <a href="{{route('admin_password_update_landing')}}" class='sidebar-link pass_a'>
            <i class="fa fa-key" aria-hidden="true"></i>
                <span>Update password</span>
            </a>
        </li>

      </ul>
    </aside>
</div>