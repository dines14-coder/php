    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{route('dashboard')}}"> <img alt="image" src="{{asset('assets/img/logo.png')}}" class="header-logo" /> <span
                class="logo-name"></span>
            </a>
          </div>
          <ul class="sidebar-menu">
            @if(auth()->check() && (auth()->user()->is_first_login ?? false))
              <!-- First login - show only Update Password -->
              <li class="menu-header">Settings</li>
              <li class="dropdown pass_upd_m">
                <a href="{{route('password_update_landing')}}" class="nav-link pass_a"><i data-feather="key"></i><span>Update Password</span></a>
              </li>
            @else
              <!-- Normal menu after password update -->
              <li class="menu-header">Main</li>
              <li class="dropdown dashbord_drop">
                <a href="{{route('dashboard')}}" class="nav-link dashbord_a"><i data-feather="monitor"></i><span>Dashboard</span></a>
              </li>
             
              <li class="menu-header">Query</li>
              <li class="dropdown query_drop">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="briefcase"></i><span>Query</span></a>
                <ul class="dropdown-menu">
                  <li class="r_query_li"><a class="nav-link r_query_a" href="{{route('create_query_landing')}}">Raise Your Query</a></li>
                  <li class="query_status_li"><a class="nav-link query_status_a" href="{{route('query_status_landing')}}">Query Status</a></li>
                </ul>
              </li>

              <li class="dropdown document_drop">
                <a href="{{route('my_document_landing')}}" class="nav-link document_a"><i
                    data-feather="file-text"></i><span>My Document</span></a>
              </li>

              <li class="dropdown pass_upd_m">
                <a href="{{route('password_update_landing')}}" class="nav-link pass_a"><i data-feather="key"></i><span>Update Password</span></a>
              </li>
            @endif
            
          </ul>
        </aside>
    </div>