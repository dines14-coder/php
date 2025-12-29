<!-- confirm pop  -->
<button type="button" class="btn btn-primary" data-toggle="modal" id="confirm_pop_trigger" data-target="#confirm_pop" style="display:none;">confirm pop</button>
        <div class="modal fade" id="confirm_pop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <div class="form-group" id="dec_rem" style="display:none;">
                  <label>Remarks<span class="text-danger">*</span></label> 
                  <textarea class="form-control red_border" name="dec_remark" id="dec_remark"></textarea>
                  <span class="text-danger error-text dec_remark_error" ></span>
              </div>

              </div>
							<b id="resp" style="display:none;text-align: center;"></b>
              <div class="modal-footer bg-whitesmoke br">
                <button type="button" id="confirm_pop_submit" class="btn btn-success">Confirm</button>
                <button type="button" class="btn text-white" style="background-color:#f26d6d"  data-dismiss="modal">Close</button>
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
                  Document Upload&nbsp;(&nbsp;<span id="pop_ticket_id"></span>&nbsp;)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body scrolly">
              <!-- Validation Alert -->
              <div class="alert alert-danger" id="validation_alert" style="display:none;">
                <i class="fa fa-exclamation-triangle"></i>
                <strong>Validation Error:</strong>
                <span id="validation_message"></span>
              </div>
              
              <h6>Emp Name (Emp ID) : <span id="pop_emp_name"></span>&nbsp;(<span id="pop_emp_id"></span>)</h6>
              <!-- <h6>Remark:<span id="pop_emp_remark"></span></h6> -->
              <form action="javascript:void(0)" method="POST" id="doc_upload_form" >

              <input type="hidden" id="pop_document" name="pop_document">
              <input type="hidden" id="emp_id" name="emp_id">
              <input type="hidden" id="ticket_id_hidden" name="ticket_id_hidden">

                    <div class="row">
                      <div class="col-md-12 doc_upl" id="pay_slip_input">
                      <div class="row">

                        <div class="form-group col-md-6"> 
                          <label>Pay Slips</label>
                          <input type="file" name="pay_slip_doc[]" id="pay_slip_doc" onchange="checkextension(this)" accept="image/*,.pdf,.doc" multiple  class="form-control doc" >
                        </div>

                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="pay_slip_remark" id="pay_slip_remark"></textarea>
                          <span class="text-danger" id="pay_slip_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div> 
                      </div> 
                      <div class="col-md-12 doc_upl" id="ff_statement_input">
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>F&F Statement</label>
                          <input type="file" onchange="checkextension(this)" name="ff_statement_doc" id="ff_statement_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>

                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="ff_statement_remark" id="ff_statement_remark"></textarea>
                          <span class="text-danger" id="ff_statement_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                      <div class="col-md-12 doc_upl" id="form_16_input">
                      <div class="row">
                        <div class="col-md-12">
                          <h6>Form 16</h6>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label>Form 16 - Part A</label> 
                          <input type="file" onchange="checkextension(this)" name="form_16_part_a" id="form_16_part_a" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>

                        <div class="form-group col-md-6"> 
                          <label>Part A Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="form_16_part_a_remark" id="form_16_part_a_remark"></textarea>
                          <span class="text-danger" id="form_16_part_a_remark_error" style="display:none;">Please enter remark</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label>Form 16 - Part B</label> 
                          <input type="file" onchange="checkextension(this)" name="form_16_part_b" id="form_16_part_b" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>

                        <div class="form-group col-md-6"> 
                          <label>Part B Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="form_16_part_b_remark" id="form_16_part_b_remark"></textarea>
                          <span class="text-danger" id="form_16_part_b_remark_error" style="display:none;">Please enter remark</span>
                        </div>
                      </div>
                      </div>
                      <div class="col-md-12 doc_upl" id="rel_letter_input">
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Relieving Letter</label>
                          <input type="file" onchange="checkextension(this)" name="rel_letter_doc" id="rel_letter_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="rel_letter_remark" id="rel_letter_remark"></textarea>
                          <span class="text-danger" id="rel_letter_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                      <div class="col-md-12 doc_upl" id="ser_letter_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Service Letter</label>
                          <input type="file" onchange="checkextension(this)" name="ser_letter_doc" id="ser_letter_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="ser_letter_remark" id="ser_letter_remark"></textarea>
                          <span class="text-danger" id="ser_letter_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>

                      <div class="col-md-12 doc_upl" id="bonus_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Bonus</label>
                          <input type="file" onchange="checkextension(this)" name="bonus_doc" id="bonus_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="bonus_remark" id="bonus_remark"></textarea>
                          <span class="text-danger" id="bonus_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                      <div class="col-md-12 doc_upl" id="performance_incentive_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Performance Incentive</label>
                          <input type="file" onchange="checkextension(this)" name="performance_incentive_doc" id="performance_incentive_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="performance_incentive_remark" id="performance_incentive_remark"></textarea>
                          <span class="text-danger" id="performance_incentive_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                      <div class="col-md-12 doc_upl" id="sales_travel_claim_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Sales Travel claim</label>
                          <input type="file" onchange="checkextension(this)" name="sales_travel_claim_doc" id="sales_travel_claim_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="sales_travel_claim_remark" id="sales_travel_claim_remark"></textarea>
                          <span class="text-danger" id="sales_travel_claim_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>

                       <div class="col-md-12 doc_upl" id="parental_medical_reimbursement_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Parental medical reimbursement</label>
                          <input type="file" onchange="checkextension(this)" name="parental_medical_reimbursement_doc" id="parental_medical_reimbursement_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="parental_medical_reimbursement_remark" id="parental_medical_reimbursement_remark"></textarea>
                          <span class="text-danger" id="parental_medical_reimbursement_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                      <!-- type 2 -->
                      <div class="col-md-12 doc_upl" id="pf_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>PF</label>
                          <input type="file" onchange="checkextension(this)" name="pf_doc" id="pf_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="pf_remark" id="pf_remark"></textarea>
                          <span class="text-danger" id="pf_remark_error" style="display:none;">Please enter remark</span>
                        </div> 

                      </div>
                      </div>
                      <div class="col-md-12 doc_upl" id="gratuity_input"> 
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Gratuity</label>
                          <input type="file" onchange="checkextension(this)" name="gratuity_doc" id="gratuity_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="gratuity_remark" id="gratuity_remark"></textarea>
                          <span class="text-danger" id="gratuity_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                      <!-- end type 2 -->
                      <div class="col-md-12 doc_upl" id="others_input">
                      <div class="row">

                        <div class="form-group col-md-6">
                          <label>Others</label> 
                          <input type="file" onchange="checkextension(this)" name="others_doc" id="others_doc" accept="image/*,.pdf,.doc" class="form-control doc" >
                        </div>
                        <div class="form-group col-md-6"> 
                          <label>Remark</label><span class="text-danger">*</span></label> 
                          <textarea class="form-control" name="others_remark" id="others_remark"></textarea>
                          <span class="text-danger" id="others_remark_error" style="display:none;">Please enter remark</span>
                        </div>

                      </div>
                      </div>
                    </div>
                    <div class="form-group" style="display:none;">
                      <label>Remarks </label> <span class="text-danger">*</span></label> 
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
                  Document &nbsp;(&nbsp;<span id="doc_s_ticket_id"></span>&nbsp;)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body scrolly">
              <h6>Emp Name (Emp ID) : <span id="doc_s_emp_name"></span>&nbsp;(<span id="doc_s_emp_id"></span>) </h6>
              {{-- <h6>Remark : <span id="doc_s_emp_remark"></span></h6> --}}

              <div id="doc_show_div"></div>
              
              </div>
            </div>
          </div>
        </div>
        <!-- document show pop end -->
<script>
document.getElementById('document_upload_submit').addEventListener('click', function(e) {
    // Check Form 16 validation
    const form16Input = document.getElementById('form_16_input');
    if (form16Input && form16Input.style.display !== 'none') {
        const partA = document.getElementById('form_16_part_a').files.length;
        const partB = document.getElementById('form_16_part_b').files.length;
        const partARemark = document.getElementById('form_16_part_a_remark').value.trim();
        const partBRemark = document.getElementById('form_16_part_b_remark').value.trim();
        
        let form16Error = false;
        
        // If no documents uploaded, show professional validation alert
        if (partA === 0 && partB === 0) {
            e.preventDefault();
            $('#validation_message').text('Please choose Form 16 Part A and Part B documents');
            $('#validation_alert').show();
            $('#validation_alert')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function() { $('#validation_alert').fadeOut(); }, 10000);
            return;
        }
        
        // If only one document uploaded, require both
        if ((partA > 0 && partB === 0) || (partA === 0 && partB > 0)) {
            e.preventDefault();
            $('#validation_message').text('Please upload both Form 16 Part A and Part B');
            $('#validation_alert').show();
            $('#validation_alert')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function() { $('#validation_alert').fadeOut(); }, 10000);
            return;
        }
        
        // If documents uploaded but remarks missing, require remarks
        if (partA > 0 && partB > 0 && (!partARemark || !partBRemark)) {
            e.preventDefault();
            $('#validation_message').text('Please enter remarks for both Form 16 Part A and Part B');
            $('#validation_alert').show();
            $('#validation_alert')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function() { $('#validation_alert').fadeOut(); }, 10000);
            return;
        }
    }
    
    // Check Pay Slip specific validation - document mandatory when remark entered
    const paySlipInput = document.getElementById('pay_slip_input');
    if (paySlipInput && paySlipInput.style.display !== 'none') {
        const paySlipDoc = document.getElementById('pay_slip_doc').files.length;
        const paySlipRemark = document.getElementById('pay_slip_remark').value.trim();
        
        if (paySlipRemark && paySlipDoc === 0) {
            e.preventDefault();
            alert('Pay Slip document is mandatory');
            return;
        }
    }
    
    // Clear all previous error messages
    $('.text-danger[id$="_error"]').hide();
    $('#validation_alert').hide();
    
    let hasError = false;
    let hasValidEntry = false;
    
    // Check remarks are mandatory, documents are optional
    const docRemarkPairs = [
        {doc: 'pay_slip_doc', remark: 'pay_slip_remark'},
        {doc: 'ff_statement_doc', remark: 'ff_statement_remark'},
        {doc: 'form_16_part_a', remark: 'form_16_part_a_remark'},
        {doc: 'form_16_part_b', remark: 'form_16_part_b_remark'},
        {doc: 'rel_letter_doc', remark: 'rel_letter_remark'},
        {doc: 'ser_letter_doc', remark: 'ser_letter_remark'},
        {doc: 'bonus_doc', remark: 'bonus_remark'},
        {doc: 'performance_incentive_doc', remark: 'performance_incentive_remark'},
        {doc: 'sales_travel_claim_doc', remark: 'sales_travel_claim_remark'},
        {doc: 'parental_medical_reimbursement_doc', remark: 'parental_medical_reimbursement_remark'},
        {doc: 'pf_doc', remark: 'pf_remark'},
        {doc: 'gratuity_doc', remark: 'gratuity_remark'},
        {doc: 'others_doc', remark: 'others_remark'}
    ];
    
    for (let pair of docRemarkPairs) {
        const docField = document.getElementById(pair.doc);
        const remarkField = document.getElementById(pair.remark);
        const parent = remarkField.closest('.doc_upl');
        
        if (parent && parent.style.display !== 'none') {
            const hasDoc = docField.files && docField.files.length > 0;
            const hasRemark = remarkField.value.trim() !== '';
            
            // If document is uploaded, remark is mandatory
            if (hasDoc && !hasRemark) {
                $('#' + pair.remark + '_error').show();
                hasError = true;
            }
            
            // If remark is entered (with or without document), it's valid
            if (hasRemark) {
                hasValidEntry = true;
            }
        }
    }
    
    // Check if at least one remark is entered
    if (!hasValidEntry) {
        // Show error only on fields that have documents uploaded but no remarks
        let hasDocumentWithoutRemark = false;
        for (let pair of docRemarkPairs) {
            const docField = document.getElementById(pair.doc);
            const remarkField = document.getElementById(pair.remark);
            const parent = remarkField.closest('.doc_upl');
            
            if (parent && parent.style.display !== 'none') {
                const hasDoc = docField.files && docField.files.length > 0;
                const hasRemark = remarkField.value.trim() !== '';
                
                if (hasDoc && !hasRemark) {
                    hasDocumentWithoutRemark = true;
                }
            }
        }
        
        // If no documents uploaded at all, show error on all visible fields
        if (!hasDocumentWithoutRemark) {
            for (let pair of docRemarkPairs) {
                const remarkField = document.getElementById(pair.remark);
                const parent = remarkField.closest('.doc_upl');
                if (parent && parent.style.display !== 'none') {
                    $('#' + pair.remark + '_error').show();
                }
            }
        }
        hasError = true;
    }
    
    if (hasError) {
        e.preventDefault();
    }
});

// Clear error messages and reset form when modal is closed
$('#doc_upload_pop').on('hidden.bs.modal', function () {
    $('.text-danger[id$="_error"]').hide();
    $('#doc_upload_form')[0].reset();
});

// Clear error messages and reset form when modal is opened
$('#doc_upload_pop').on('shown.bs.modal', function () {
    $('.text-danger[id$="_error"]').hide();
    $('#doc_upload_form')[0].reset();
    $('#doc_resp').hide();
});

// Handle close button click to refresh modal
$('#doc_upload_pop .close').on('click', function () {
    $('.text-danger[id$="_error"]').hide();
    $('#doc_upload_form')[0].reset();
    $('#doc_resp').hide();
});
</script>