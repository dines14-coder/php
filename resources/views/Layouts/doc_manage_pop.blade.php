<!-- confirm pop  -->
<button type="button" class="btn btn-primary" data-toggle="modal" id="confirm_pop_trigger" data-target="#confirm_pop" style="display:none;">confirm pop</button>
        <div class="modal fade" id="confirm_pop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm..!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <div class="form-group" id="dec_rem" style="display:none;">
                  <label>Remarks<span class="text-danger">*</span></label> 
                  <textarea class="form-control" name="dec_remark" id="dec_remark"></textarea>
              </div>
              <b id="rem_resp" style="display:none;"></b>

              </div>
							<b id="resp" style="display:none;text-align: center;"></b>
              <div class="modal-footer bg-whitesmoke br">
                <button type="button" id="confirm_pop_submit" class="btn btn-success">Confirm</button>
                <button type="button" class="btn text-white" style="background-color:#f26d6d" data-dismiss="modal">Close</button>
              </div> 
            </div>
          </div>
        </div>
        <!-- confirm pop end -->
        <!-- documnt upload pop -->
        <button type="button" class="btn btn-primary" id="upl_doc_pop_trigger" data-toggle="modal"
                      data-target="#doc_upload_pop" style="display:none;">Document Upload</button>
        <div class="modal fade bd-example-modal-lg" id="doc_upload_pop" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">
                  Document Upload
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body scrolly" >
              <h6>Emp Name (Emp ID) : <span id="pop_emp_name"></span>&nbsp;(<span id="pop_emp_id"></span>)</h6>
              <form action="javascript:void(0)" method="POST" id="doc_upload_form" >

              <input type="hidden" id="pop_document" name="pop_document">
              <input type="hidden" id="emp_id" name="emp_id">
              <input type="hidden" id="ticket_id_hidden" name="ticket_id_hidden">
              <input type="hidden" id="type" name="type">

                    <div class="row">
                      <div class="col-md-6 doc_upl" id="pay_slip_input">
                        <div class="form-group">
                          <label>Pay Slips</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="pay_slip_doc[]" id="pay_slip_doc" multiple class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="ff_statement_input">
                        <div class="form-group">
                          <label>F&F Statement</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="ff_statement_doc" id="ff_statement_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="form_16_input">
                        <div class="form-group"> 
                          <label>Form 16</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="form_16_doc[]" id="form_16_doc" multiple class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="rel_letter_input">
                        <div class="form-group">
                          <label>Relieving Letter</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="rel_letter_doc" id="rel_letter_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="ser_letter_input">
                        <div class="form-group">
                          <label>Service Letter</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="ser_letter_doc" id="ser_letter_doc" class="form-control" >
                        </div> 
                      </div>

                      <div class="col-md-6 doc_upl" id="bonus_input"> 
                        <div class="form-group">
                          <label>Bonus</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="bonus_doc" id="bonus_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="performance_incentive_input"> 
                        <div class="form-group">
                          <label>Performance Incentive</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="performance_incentive_doc" id="performance_incentive_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="sales_travel_claim_input"> 
                        <div class="form-group">
                          <label>Sales Travel claim</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="sales_travel_claim_doc" id="sales_travel_claim_doc" class="form-control" >
                        </div>
                      </div>

                      <div class="col-md-6 doc_upl" id="parental_medical_reimbursement_input"> 
                        <div class="form-group">
                          <label>Parental medical reimbursement</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="parental_medical_reimbursement_doc" id="parental_medical_reimbursement_doc" class="form-control" >
                        </div>
                      </div>

                     

                      <!-- bonus doc from hr -->
                      <div class="col-md-6 doc_upl" id="f_and_f_input"> 
                        <div class="form-group">
                          <label>F&F</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="f_and_f_document" id="f_and_f_document" class="form-control" >
                        </div>
                      </div>

                      <!-- type 2 -->
                      <div class="col-md-6 doc_upl" id="pf_input"> 
                        <div class="form-group">
                          <label>PF</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="pf_doc" id="pf_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="gratuity_input"> 
                        <div class="form-group">
                          <label>Gratuity</label>
                          <input type="file" onchange="checkextension(this)" accept="image/*,.pdf,.doc" name="gratuity_doc" id="gratuity_doc" class="form-control" >
                        </div>
                      </div>
                      <!-- end type 2 -->
                      
                    </div>
                    <div class="form-group">
                      <label>Remarks</label> 
                      <textarea class="form-control" autocomplete="off"  name="remark" id="remark" ></textarea>
                    </div>
                    <b id="doc_resp" style="display:none;"></b>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" id="document_upload_submit" type="submit">Submit</button>
                    </div>
                    
                    </form>
              </div>
            </div>
          </div>
        </div>
        <!-- document upload pop end -->

        <!-- document show pop -->
        <button type="button" class="btn btn-primary" id="show_doc_pop_trigger" data-toggle="modal"
                      data-target="#doc_show_pop" style="display:none;">Document show</button>
        <div class="modal fade bd-example-modal-lg" id="doc_show_pop" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">
                  Documents &nbsp;
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body scrolly">
              <h6>Emp Name (Emp ID) : <span id="doc_s_emp_name"></span>&nbsp;(<span id="doc_s_emp_id"></span>)</h6>
              <h6>Remark :&nbsp;<span id="doc_s_emp_remark"></span></h6>

              <div id="doc_show_div"></div>
              
              </div>
            </div>
          </div>
        </div>
        <!-- document show pop end -->