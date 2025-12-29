 <!-- error pop -->
 <button type="button" class="btn btn-primary" id="log_f_p_pop_trigger" data-toggle="modal"
                      data-target="#error_pop" style="display:none;">error pop</button>

      <div class="modal fade" id="error_pop" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalCenterTitle">Forgot Password..!</h5>
                <button type="button" class="close cls_btn" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="text-align:center;">
                
              <section class="section">
                      <div class="card card-primary">
                        <div class="card-header">
                          <h4>Submit Your Email</h4>
                        </div>
                        <div class="card-body">
                          <form method="POST" id="f_p_login_form" action="javascript:void(0)" class="needs-validation" novalidate="">
                            <div class="form-group">
                              <!-- <label for="emp_id">Emp ID</label> -->
                              <input id="f_p_mailid" type="text" class="form-control" name="f_p_mailid" tabindex="1" required autofocus>
                              <div class="invalid-feedback text-left">
                                Please fill in this field.
                              </div>
                            </div> 
                            <div class="invalid-feedback login_resp text-left">
                            </div>
                            <div class="form-group">
                              <button type="submit" id="f_p_form_submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                Submit
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                      
              </section> 

              </div>
              <!-- <div class="modal-footer bg-whitesmoke br"> -->
                <!-- <a href="tel:9087428914">
                <button type="button" class="btn btn-primary">
                  <i class="fa fa-phone" aria-hidden="true"></i>
                &nbsp;Contact</button>
                </a> -->
                <!-- <div class="bar_2"></div>  -->

                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
              <!-- </div> -->
            </div>
          </div>
        </div>
    <!-- error pop end -->
    <!-- mail otp pop-->
    <button type="button" class="btn btn-primary" id="mail_pop_trigger" data-toggle="modal"
                      data-target="#exampleModalCenter" style="display:none;">Vertically
                      Centered</button>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Verify your email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Just one quick check to make sure you’re really you.<br> We’ve sent a verification code to
                <b id="email_txt">sample@gmail.com</b>
                (remember to check your Spam).
                <div class="form-group">
                    <label></label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-lock"></i>
                        </div>
                      </div>
                      <input type="text" id="mail_otp" class="form-control" placeholder="Enter Verification Code" name="email_otp">
                    </div>
                </div>
              </div>
							<b id="otp_resp" style="display:none;text-align: center;"></b>

              <div class="modal-footer bg-whitesmoke br">
                <button type="button" id="otp_submit" class="btn btn-primary">Verify</button>
              </div>
            </div>
          </div>
        </div>
    <!-- end mail otp -->