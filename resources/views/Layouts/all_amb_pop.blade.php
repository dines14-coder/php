 <!-- document show pop -->
    <button type="button" class="btn btn-primary" id="show_doc_pop_trigger" data-toggle="modal"
                      data-target="#doc_show_pop" style="display:none;">Document show</button>
        <div class="modal fade bd-example-modal-lg" id="doc_show_pop" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">
                  Document &nbsp;
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <h6>Emp Name (Emp ID) : <span id="doc_s_emp_name"></span>&nbsp;(<span id="doc_s_emp_id"></span>)</h6>
              <h6>Remark : <span id="doc_s_emp_remark"></span></h6>

              <div id="doc_show_div"></div>
              
              </div>
            </div>
          </div>
        </div>
        <!-- document show pop end -->