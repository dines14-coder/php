<!DOCTYPE html>
<html lang="en">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{$website_name}}</title>
  @include('Layouts.cmn_head_link')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <style>
    #login_form_submit:hover {
      background-color: #d4761a !important;
      border-color: #d4761a !important;
    }
    .card-primary {
      border-top: 3px solid #EC8922 !important;
    }
  </style>
</head>
 
<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
              <div class="row">
               <div class="col-sm-12  badge badge-sm mt-2" style="" >
                  <img alt="image" src="{{asset('assets/img/logo.png')}}" class="header-logo" height="60">
                </div>
                
                <div class="col-sm mt-3" >
                  <h6 class="text-center text-dark">Alumni Login Page</h6>
                </div>
              </div>
              
              </div>
              <div class="card-body">
                <form method="POST" id="login_form" action="javascript:void(0)" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="emp_id">Email</label>
                    <input id="emp_id" type="text" class="form-control" name="emp_id" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in this field.
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                      <div class="float-right">
                        <a onclick="forgot_pass_pop();" style="cursor:pointer;" class="text-small">
                          Forgot Password?
                        </a>
                      </div>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                    <div class="invalid-feedback">
                      Please fill in this field.
                    </div>
                  </div>
                  <div class="form-group m-0">
                    <div class="custom-control custom-checkbox" style="margin-left:-20px">
                      <input type="checkbox" style="cursor:pointer;" name="remember" class="" tabindex="3" id="remember-me">
                      <label class=" remember-me" style="cursor:pointer;" for="remember-me">Remember Me</label>
                    </div>
                  </div>
                  <div class="invalid-feedback login_resp">
                  </div>
                  <div class="form-group">
                    <button type="submit" id="login_form_submit" class="btn btn-primary btn-lg btn-block" tabindex="4" style="background-color: #EC8922; border-color: #EC8922;">
                      Login
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
              Don't have an account? <a href="{{route('register')}}">Self Register</a>
            </div>
          </div>
        </div>
      </div>
    </section> 
  @include('Layouts.login_pop')
  </div>
  @include('Layouts.cmn_footer_link')

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script src="{{asset('assets/new_add/js/login.js')}}"></script>

  <script>
      var login_check="{{url('login_check')}}";
      var f_p_submit="{{url('f_p_submit')}}";
  </script>

</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>