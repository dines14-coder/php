  <!-- reassign edit show pop -->
  <button type="button" class="btn btn-primary" id="reas_edit_pop" data-toggle="modal"
  data-target="#reas_edit_pop1" style="display:none;">Reassign</button>
<div class="modal fade bd-example-modal-md" id="reas_edit_pop1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">
          Reassign To&nbsp;
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <span ><b >Employee ID :&nbsp;</b></span><span ><b id="emp_id_u"></b></span>
            <form action="javascript:void(0)" id="update_reassign_form" method="POST">
                <div class="row mt-2">
                    <div class="form-group"hidden>
                        <input id="u_emp_id" type="hidden" name="u_emp_id">
                        <input id="ticket_id" type="hidden" name="ticket_id">
                        <input id="from_docu" type="hidden" name="from_docu">
                        <input id="assign_from" type="hidden" name="assign_from">
                    </div>
                    <div class="form-group col-md-10">
                        <label for="assign_to">Ticket Assigned</label>
                        <select class="form-control red_border" id="assign_to" name="assign_to" required>
                        <option selected disabled value="">Select Department</option>
                        <option value="F_F_HR">F_F_HR</option>
                        <option value="Payroll_Finance">Payroll_Finance</option>
                        <option value="Claims">Claims</option>
                        <option value="Payroll_HR">Payroll_HR</option>
                        </select>
                        <span class="text-danger error-text" id="assign_to_error"></span>
                    </div>
                </div>
                <div class="row mb-3">
                </div>
            </form>
            <button type="button" name="update_reassign" id="update_reassign" class="btn btn-primary float-right">Reassign</button><span id="up_amb" class="float-right ml-2 mr-2"></span>
        </div>
    </div>
  </div>
</div>
<!--reassign edit show pop end -->