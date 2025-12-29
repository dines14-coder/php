<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{$website_name}}</title> 
  @include('Layouts.cmn_head_link') 
  <style>
    .doc_name{
      margin: 5px 0 5px 0;
    }
    .first-login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
    }
    .first-login-card {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
    }
  </style>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    @if(isset($isFirstLogin) && $isFirstLogin)
      <!-- First-time login layout without sidebar -->
      <div class="first-login-container">
        <div class="first-login-card">
          <div class="text-center mb-4">
            <h3 style="color: #dd163b !important;">Update Your Password</h3>
            <p class="text-muted">Please update your password to continue</p>
          </div>
          
          <div class="form_field">
            <div class="col-md-12">
              <label for="">Old Password <span class="text-danger">*</span> :</label>
              <input type="password" class="form-control" id="old_password" name="old_password" placeholder="" value="" required>
              <span style="color: red;" class="old_password_error" id="old_password_error"></span>
              <div id="old_pass_res"></div>

              <div class="nk-gap"></div>
              <label for="">New Password <span class="text-danger">*</span> :</label>
              <input type="password" name="new_password" class="form-control" id="new_password" required readonly>
              <span style="color: red;" class="new_password_error" id="new_password_error"></span>
              
              <div class="nk-gap"></div>
              <label for="">Confirm Password <span class="text-danger">*</span> :</label>
              <input type="password" name="new_confirm_password" class="form-control" id="new_confirm_password" required readonly>
              <span style="color: red;" class="new_confirm_password_error" id="new_confirm_password_error"></span>
              <div id="con_pass_res"></div>
                                     
              <b id="errempty" style="color: red; display: none;"></b>
              <b id="update_success" style="display:none;color: green; ">Updated Successfully..!</b> 
                                      
            </div>

            <p id="erroresp" class="text-danger z_margin"></p>

            <div class="col-md-12 mt-3">
              <button id="password_updated" class="btn btn-block btn-danger">Update Password</button>
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Normal layout with sidebar -->
      <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        @include('Layouts.top_nav')
        @include('Layouts.left_nav')
        <!-- Main Content -->
        <div class="main-content">
        <div class="form_field">
                              <div class="col-md-6">
                              <h3 class="text-center" style="color: #dd163b !important;">Password Update</h3>
                                  <div class="nk-gap"></div>
                                  <label for="">Old Password <span class="text-danger">*</span> :</label>
                                  <input type="password" class="form-control" id="old_password" name="old_password" placeholder="" value="" required>
                                  <span style="color: red;" class="old_password_error" id="old_password_error"></span>
                                  <div id="old_pass_res"></div>

                                  <div class="nk-gap"></div>
                                  <label for="">New Password <span class="text-danger">*</span> :</label>
                                  <input type="password" name="new_password" class="form-control" id="new_password" required readonly>
                                  <span style="color: red;" class="new_password_error" id="new_password_error"></span>
                                  <div class="nk-gap"></div>
                                  <label for="">Confirm Password <span class="text-danger">*</span> :</label>
                                  <input type="password" name="new_confirm_password" class="form-control" id="new_confirm_password" required readonly>
                                  <span style="color: red;" class="new_confirm_password_error" id="new_confirm_password_error"></span>
                                  <div id="con_pass_res"></div>
                                                         
                                  <b id="errempty" style="color: red; display: none;"></b>
                                  <b id="update_success" style="display:none;color: green; ">Updated Successfully..!</b> 
                                                          
                              </div>

                              <p id="erroresp" class="text-danger z_margin"></p>

                              <div class="col-md-4">
                                  <div class="nk-gap"></div>

                                  <button id="password_updated" class="btn btn-sm btn-block btn-danger">Update</button>

                              </div>

                                                      

                          </div>

          <!-- document show pop -->
          
          <!-- document show pop end -->

          @include('Layouts.theme_setting')
        </div>
        @include('Layouts.footer') 
      </div>
    @endif
  </div>
  @include('Layouts.cmn_footer_link')
  <script>
     $(document).ready(function () {
        $(".pass_upd_m").addClass("active");
        $(".pass_a").addClass("active");
      })
     
  </script>

  <script src="{{asset('assets/new_add/js/password_update.js')}}"></script>

  <script>
        var check_password = "{{url('check_password')}}";
        var update_pass = "{{url('update_pass')}}";
        var logout = "{{url('logout')}}";
        var isFirstLogin = @json(isset($isFirstLogin) && $isFirstLogin);
  </script>

</body>
<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>