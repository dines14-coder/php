<!DOCTYPE html>
<html lang="en">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{$website_name}}</title>
  @include('Layouts.cmn_head_link')
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
                  <img alt="image" src="{{asset('assets/img/logo.png')}}" class="header-logo" height="40">
                </div>
                <div class="col-sm mt-3" >
                  <h6 class="text-center text-dark">Alumni Reset Password Page</h6>
                </div>
              </div>
              </div>
              <div class="card-body">
                <form method="POST" id="pass_reset_form" action="javascript:void(0)" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="emp_id">Password</label>
                    <input id="password" type="password" class="form-control" name="password" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in this field.
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="emp_id">Confirm Password</label>
                    <input id="c_password" type="password" class="form-control" name="c_password" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in this field.
                    </div>
                  </div>

                  <input type="hidden" name="email" id="email" value="<?php echo $_GET['mail']?>">
                  
                  <div class="invalid-feedback login_resp">
                  </div>
                  <div class="form-group">
                    <button type="submit" id="pass_reset_form_submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Change Password
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
              <!-- Don't have an account? <a href="{{route('register')}}">Create One</a> -->
            </div>
          </div>
        </div>
      </div>
    </section> 
  </div>
  @include('Layouts.cmn_footer_link')

  <script src="{{asset('assets/new_add/js/login.js')}}"></script>

  <script>
      var password_change_submit="{{url('password_change_submit')}}";
  </script>

</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>