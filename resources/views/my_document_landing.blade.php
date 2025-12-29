<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{$website_name}}</title> 
  @include('Layouts.cmn_head_link') 
  <style>
    .doc_name{
      margin: 5px 0 5px 0;
    }
  </style>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      @include('Layouts.top_nav')
      @include('Layouts.left_nav')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>My Document</h4>
                  <div class="card-header-form">
                    {{-- <form> --}}
                      {{-- <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <div class="input-group-btn">
                          <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                      </div> --}}
                    {{-- </form> --}}
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Document</th>
                          <th>created Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="qmp_doc_rows"> 
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- document show pop -->
        <button type="button" class="btn btn-primary" id="show_doc_pop_trigger" data-toggle="modal"
                      data-target="#doc_show_pop" style="display:none;">Document show</button>
        <div class="modal fade bd-example-modal-lg" id="doc_show_pop" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">
                  Document 
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <h6>Remark :&nbsp;<span id="doc_s_emp_remark"></span></h6>

              <div id="doc_show_div"></div>
              
              </div>
            </div>
          </div>
        </div>
        <!-- document show pop end -->

        @include('Layouts.theme_setting')
      </div>
      @include('Layouts.footer')
    </div>
  </div>
  @include('Layouts.cmn_footer_link')
  <script>
     $(document).ready(function () {
        $(".document_drop").addClass("active");
        $(".document_a").addClass("active"); 
      })
  </script>

  <script src="{{asset('assets/new_add/js/my_document_landing.js')}}"></script>

  <script>
      var get_emp_all_doc_data="{{url('get_emp_all_doc_data')}}"; 
      var doc_updated_detail_emp="{{url('hr_updated_detail_emp_view')}}"; 
  </script>

</body>
<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>