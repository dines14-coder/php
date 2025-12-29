<button type="button" class="btn btn-primary" id="qry_suc_pop_trigger" data-toggle="modal"
                      data-target="#qry_pop" style="display:none;">Vertically
                      Centered</button>

        <div class="modal fade" id="qry_pop" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="pop_title">Your Query Submitted Successfully..!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <div class="alert alert-success alert-has-icon" id="pop_clr">
                      <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                      <div class="alert-body">
                        <div class="alert-title" id="pop_sub_title">Success</div>
                        <b id="pop_msg">This is a success alert.</b>
                      </div>
                    </div>
              </div>

              <div class="modal-footer bg-whitesmoke br">
               <a href="{{route('query_status_landing')}}"><button type="button" id="otp_submit" class="btn btn-primary">Check Query Status</button></a>
              </div>
            </div>
          </div>
        </div>