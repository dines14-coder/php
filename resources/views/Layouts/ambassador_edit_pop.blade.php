  <!-- ambassador edit show pop -->
<button type="button" class="btn btn-primary" id="amb_edit_pop" data-toggle="modal"
  data-target="#amb_edit_pop1" style="display:none;">Edit Alumni</button>
<div class="modal fade bd-example-modal-lg" id="amb_edit_pop1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">
          Edit Alumni&nbsp;
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <span ><b >Employee ID :&nbsp;</b></span><span ><b id="emp_id"></b></span>
            <form action="javascript:void(0)" id="update_amb_form" method="POST">
                <div class="row mt-2">
                    <div class="form-group col-md-4">
                        <label for="frist_name">Name</label>
                        <input id="name" type="text"  onkeypress="return /^[ A-Za-z]*$/i.test(event.key)" class="form-control red_border" name="name" autofocus required>
                        <input id="u_emp_id" type="hidden" name="u_emp_id">
                        <input id="u_id" type="hidden" name="u_id">
                         <span class="text-danger error-text" id="name_error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="frist_name">Pan Number</label>
                        <input id="pan_num" type="text" maxlength="10" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" class="form-control red_border" name="pan_num" autofocus >
                        <span class="text-danger error-text" id="pan_num_error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="last_name">DOB</label>
                        <input id="dob" type="date" class="form-control red_border" name="dob" required>
                        <span class="text-danger error-text" id="dob_error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="contact">Contact Number</label>
                        <input id="mobileno" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" type="text" class="form-control red_border" name="mobileno" required>
                        <span class="text-danger error-text" id="mobileno_error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control red_border" value="" name="email" required>
                        <span class="text-danger error-text" id="email_error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="lwd">LWD</label>
                        <input id="lwd" type="date" class="form-control red_border" value="" name="lwd" required>
                        <span class="text-danger error-text" id="lwd_error"></span>
                    </div>
                </div>
            </form>
            <button type="button" name="update_amb" id="update_amb" class="btn btn-primary float-right">Update</button><span id="up_amb" class="float-right ml-2 mr-2"></span>
        </div>
    </div>
  </div>
</div>
<!--ambassador edit show pop end -->
 
 