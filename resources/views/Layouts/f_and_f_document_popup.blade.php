  <!-- document show pop -->
<button type="button" class="btn btn-primary" id="f_and_f_document_popup1" data-toggle="modal"
  data-target="#f_and_f_document_popup" style="display:none;">Document show</button>
<div class="modal fade bd-example-modal-lg" id="f_and_f_document_popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">
          F&F Check Points&nbsp;
          </h5>
          <input type="hidden" id="hidden_pop_emp_id">
          <button type="button" class="btn btn-sm btn-info" name="download" id="download">
          <i class="fa fa-file-pdf" style="margin: 5px 5px 5px 5px;"></i>
            Download PDF
          </button>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body scrolly">
            <div id="data"></div>
        </div>
    </div>
  </div>
</div>
<!-- document show pop end -->
 
 