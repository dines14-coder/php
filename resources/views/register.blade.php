<!DOCTYPE html>
<html lang="en">


<!-- auth-register.html  21 Nov 2019 04:05:01 GMT -->
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
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="card card-primary">
              <div class="card-header">
                  <h4 class="text-center">Alumni Register</h4>
                <div class="col-sm-3 badge badge-sm " style="margin-left:346px" >
                  <img alt="image" src="{{asset('assets/img/logo.png')}}" class="header-logo ml-4" height="40">
                </div>
              </div>
              <div class="card-body">
                <form action="javascript:void(0)" id="reg_form" method="POST">
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="frist_name">Name</label><span class="text-danger">*</span>
                      <input id="name" type="text"  onkeypress="return /^[ A-Za-z]*$/i.test(event.key)" class="form-control red_border" name="name" autofocus required>
                      <span class="text-danger error-text name_error" ></span>
                    </div>
                    <div class="form-group col-6">
                      <label for="last_name">Employee ID</label><span class="text-danger">*</span>
                      <input id="employee_id" type="text" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" class="form-control red_border" name="employee_id" required>
                      <span class="text-danger error-text employee_id_error" ></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="frist_name">Pan Number</label><span class="text-danger">*</span>
                      <input id="pan_number" type="text" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" class="form-control red_border" name="pan_number" autofocus >
                      <span class="text-danger error-text pan_number_error" ></span>
                    </div>
                    <div class="form-group col-6">
                      <label for="last_name">DOB</label><span class="text-danger">*</span>
                      <input id="dob" type="date"  class="form-control red_border" name="dob" required>
                      <span class="text-danger error-text dob_error" ></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-6">
                        <label for="contact">Contact Number</label><span class="text-danger">*</span>
                        <input maxlength="10" id="contact_number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" type="text" class="form-control red_border" name="contact_number" required>
                        <span class="text-danger error-text contact_number_error" ></span>
                    </div>
                    <div class="form-group col-6">
                        <label for="email">Email</label><span class="text-danger">*&nbsp;Note : Use Your Personal Mail ID</span>
                        <input id="email" type="email" class="form-control red_border" value="" name="email" required>
                        <span class="text-danger error-text email_error" ></span>
                    </div> 
                  </div>
                  <div class="bar"></div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="reg_form_submit">
                      Register 
                    </button> 
                  </div>
                </form>
              </div>
              <div class="mb-4 text-muted text-center">
                Already Registered? <a href="{{route('login')}}">Login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

   @include('Layouts.register_pop') 

  </div>
  @include('Layouts.cmn_footer_link') 

  <script src="{{asset('assets/new_add/js/register.js')}}"></script>

  <script>
      var register_click="{{url('register_process')}}";
      var otp_submit="{{url('otp_submit')}}";
      var send_reg_mail="{{url('send_reg_mail')}}";
  </script>

</body>


<!-- auth-register.html  21 Nov 2019 04:05:02 GMT -->
</html>