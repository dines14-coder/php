 <!-- error pop -->
 <button type="button" class="btn btn-primary" id="error_pop_trigger" data-toggle="modal"
                      data-target="#error_pop" style="display:none;">error pop</button>

      <div class="modal fade" id="error_pop" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Requested Successfully..!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="text-align:center;">
                <b>Your Request under validation..!</b>
                <br><br>
                <b>we will reply within 24 or 48 hours</b>
                <!-- <a href="mailto:lakshminarayanan@hemas.in">lakshminarayanan@hemas.in</a> -->
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <!-- <a href="tel:9087428914">
                <button type="button" class="btn btn-primary">
                  <i class="fa fa-phone" aria-hidden="true"></i>
                &nbsp;Contact</button>
                </a> -->
                <div class="bar_2"></div> 

                <button type="button" class="btn text-white" style="background-color:#dd9087" data-dismiss="modal">Close</button>
              </div>
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