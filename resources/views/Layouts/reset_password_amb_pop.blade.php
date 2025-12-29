  <!-- reset password show pop -->
<button type="button" class="btn btn-primary" id="password_reset" data-toggle="modal"
  data-target="#password_reset1" style="display:none;">Reset Password</button>
<div class="modal fade bd-example-modal-lg" id="password_reset1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">
          Reset Password&nbsp;
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <input id="u_emp_id" type="hidden" name="u_emp_id">
        <div class="modal-body">
            <span ><b >Confirm to reset password :&nbsp;</b></span><span ><b class="text-dark" id="emp_details"></b></span>
        </div>

        <div class="modal-footerr">
            <button type="button" class=" btn btn-danger mb-2 mr-2  float-right" data-dismiss="modal" >Cancel</button>
            <button type="button" name="reset_pass" id="reset_pass" class="btn btn-primary float-right mr-1 mb-2 ml-2">Submit</button>
            <span id="pass_up" class="float-right  ml-2 mr-2"></span>
        </div>
    </div>
  </div>
</div>
<!--reset password show pop end -->
 
 