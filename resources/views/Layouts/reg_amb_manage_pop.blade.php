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
                <div class="form-group hide_div">
                      <label>Type of leaving <span class="text-danger">*</span></label> 
                      <select name="type_of_leaving" class="form-control red_border" id="type_of_leaving" >
                        <option selected disabled value=""> Type of leaving</option>
                        <option value="Relieved">Relieved</option>
                        <option value="Terminated">Terminated</option>
                        <option value="Abscond">Abscond</option>
                        <option value="Transferred">Transferred</option>
                        
                      </select>  
                       <span class="text-danger error-text type_of_leaving_error" ></span>
                </div>
                <div class="form-group hide_div">
                  <label for="last_name">Last Working Date <span class="text-danger">*</span></label>
                  <input id="last_working_date" type="date" class="form-control red_border" name="last_working_date">
                  <span class="text-danger error-text last_working_date_error" ></span>
                </div>
                <div class="form-grou hide_div">
                  <input type="checkbox" id="checkbox" name="checkbox" value="Yes" checked>
                  <label for="checkbox">F&F Work</label><br>
                </div> 
                <div class="form-group" id="dec_rem" style="display:none;">
                    <label>Remarks <span class="text-danger">*</span></label> 
                    <textarea class="form-control red_border" name="dec_remark" id="dec_remark"></textarea>
                     <span class="text-danger error-text dec_remark_error" ></span>
                </div>
              </div>
							<b id="resp" style="display:none;text-align: center;"></b>
              <div class="modal-footer bg-whitesmoke br">
                <button type="button" id="confirm_pop_submit" class="btn btn-success ">Confirm</button>
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
                  Document Upload&nbsp;(&nbsp;<b id="pop_ticket_id"></b>&nbsp;)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <h6>Emp Name (Emp ID) : <span id="pop_emp_name"></span>&nbsp;(<span id="pop_emp_id"></span>)</h6>
              <h6>Remark:<span id="pop_emp_remark"></span></h6>
              <form action="javascript:void(0)" method="POST" id="doc_upload_form" >

              <input type="hidden" id="pop_document" name="pop_document">
              <input type="hidden" id="emp_id" name="emp_id">
              <input type="hidden" id="ticket_id_hidden" name="ticket_id_hidden">

                    <div class="row">
                      <div class="col-md-6 doc_upl" id="pay_slip_input">
                        <div class="form-group">
                          <label>Pay Slips</label>
                          <input type="file" name="pay_slip_doc" id="pay_slip_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="ff_statement_input">
                        <div class="form-group">
                          <label>F&F Statement</label>
                          <input type="file" name="ff_statement_doc" id="ff_statement_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="form_16_input">
                        <div class="form-group">
                          <label>Form 16</label>
                          <input type="file" name="form_16_doc" id="form_16_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="rel_letter_input">
                        <div class="form-group">
                          <label>Relieving Letter</label>
                          <input type="file" name="rel_letter_doc" id="rel_letter_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="ser_letter_input"> 
                        <div class="form-group">
                          <label>Service Letter</label>
                          <input type="file" name="ser_letter_doc" id="ser_letter_doc" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-6 doc_upl" id="others_input">
                        <div class="form-group">
                          <label>Others</label> 
                          <input type="file" name="others_doc" id="others_doc" class="form-control" >
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Remarks</label> 
                      <textarea class="form-control" name="remark" id="remark"></textarea>
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
                  Document &nbsp;(&nbsp;<b id="doc_s_ticket_id"></b>&nbsp;)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <h5>Emp Name (Emp ID) : <span id="doc_s_emp_name"></span>&nbsp;(<span id="doc_s_emp_id"></span>)</h5>
              <h6>Remark:<span id="doc_s_emp_remark"></span></h6>

              <div id="doc_show_div"></div>
              
              </div>
            </div>
          </div>
        </div>
        <!-- document show pop end -->