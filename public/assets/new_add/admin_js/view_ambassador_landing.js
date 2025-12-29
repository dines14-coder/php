$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    template = Handlebars.compile($("#details-template").html());
       var ut = $("#user_type").val();
        get_ambassadors();
}); 

// for export all data
function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
}

function processInfo(info) {
    var res_found = info.recordsDisplay;
    $('.total_res_show').text(res_found);
}   

function get_ambassadors(){ 
        table = $('#amb_tbl').DataTable({
            lengthMenu: [[50, 100, 200, 300, 400, 500, 1000, -1], [50, 100, 200, 300, 400, 500, 1000, "All"]],
            buttons: [ 
                        // {
                        //     "extend": 'copy',
                        //     "text": '<i class="fa fa-clipboard" ></i> Copy', 
                        //     "titleAttr": 'Copy',
                        //     "exportOptions": {
                        //         'columns': ':visible'
                        //     },
                        //     "action": newexportaction
                        // },
                        {
                            "extend": 'excel',
                            "text": '<i class="fa fa-file-excel" ></i>  Excel',
                            "titleAttr": 'Excel',
                            "exportOptions": {
                                'columns': ':visible'
                            },
                            "action": newexportaction
                        }, 
                        {
                            "extend": 'csv',
                            "text": '<i class="fa fa-file" ></i>  CSV',
                            "titleAttr": 'CSV',
                            "exportOptions": {
                                'columns': ':visible'
                            },
                            "action": newexportaction
                        },
                        {
                            "extend": 'pdf',
                            "text": '<i class="fa fa-file-pdf" ></i>  PDF',
                            "titleAttr": 'PDF',
                            "exportOptions": {
                                'columns': ':visible'
                            },
                            "action": newexportaction
                        },
                        {
                            "extend": 'print',
                            "text": '<i class="fa fa-print" ></i>  Print',
                            "titleAttr": 'Print',
                            "exportOptions": {
                                'columns': ':visible'
                            },
                            "action": newexportaction
                        },
                        // {
                        //     "extend": 'colvis',
                        //     "text": '<i class="fa fa-eye" ></i>  Colvis',
                        //     "titleAttr": 'Colvis',
                        //     // "action": newexportaction
                        // },
                        
            ],  
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            bDestroy: true,
            autoWidth: false,
            scrollX: true,
            iDisplayLength: 50,
            drawCallback : function() {
                processInfo(this.api().page.info());
            },
    
            ajax: {
                url: get_all_alumni_datatable,   
                type: 'POST', 
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    }
               },
            // ajax: "{{ route('users.index') }}",
            
            columns: [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "searchable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                {data: 'DT_RowIndex', name: 'DT_RowIndex' ,orderable: false, searchable: false},
                {data: 'emp_id', name: 'emp_id'},
                {data: 'emp_name', name: 'emp_name'},
                {data: 'document_div', name: 'document_div'},
                {data: 'remark', name: 'remark'},
                {data: 'type_of_leaving', name: 'type_of_leaving'},
                {data: 'created_at', name: 'created_at'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
          
            
        });
        
        if($("#user_type").val() == "Payroll_Finance"){
            table.column(7).visible( false );
        }

} 




  // Add event listener for opening and closing details
  $('.data-table tbody').on('click', 'td.details-control', function () {
    
    var tr = $(this).closest('tr');
    
    var row = table.row(tr);
    

    var tableId = 'posts-' + row.data().emp_id;
    var nester_tbl_u_id = row.data().emp_id;
    
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        row.child(template(row.data())).show();
        initTable(tableId,nester_tbl_u_id, row.data());
        tr.addClass('shown');
        tr.next().find('td').addClass('no-padding bg-gray');
    }
});

function initTable(tableId,nester_tbl_u_id, data) {

    $.ajax({
        type: "POST",
        url: employee_detail,
        data: { 'emp_id':nester_tbl_u_id,},
        success: function (data) {
            var emp_details = '<tr><td>'+data[0].emp_id+'</td><td>'+data[0].emp_name+'</td><td>'+data[0].pan_no+'</td><td>'+data[0].dob+'</td><td>'+data[0].mobileno+'</td><td>'+data[0].email+'</td></tr>';
            $('#posts-'+nester_tbl_u_id+' #inner_tbody').html(emp_details);
        }
    })
    
}

function doc_detail(emp_id,emp_name,remark){
    $.ajax({
        type: "POST",
        url: doc_updated_detail,
        data: { 'emp_id':emp_id,},
        success: function (data) {
            if (data.response == "success") {
                $("#doc_s_emp_name").html(emp_name);
                $("#doc_s_emp_id").html(emp_id);
                $("#doc_s_emp_remark").html(remark);
                $("#doc_show_div").html(data.show_div);
                $("#show_doc_pop_trigger").click();

            } 
            
            
        }
    })

}

function f_and_f_document_popup(emp_id){
    $("#hidden_pop_emp_id").val(emp_id);

    $.ajax({
        type: "POST",
        url: f_and_f_document_pop,
        data: {'emp_id':emp_id,},
        dataType: "JSON",
        success: function (response) {
            if (response.response == "Success") {
                $("#data").html(response.data);
                $("#f_and_f_document_popup1").click();
                
            } 
        }
    })

}

function reset_ambassador_password(emp_id,name){
    $("#password_reset").click();
    $("#emp_details").html(emp_id+' / '+name);
    $("#u_emp_id").val(emp_id);
}

function edit_ambassador(id,emp_id,name,pan_no,dob,mobileno,email,lwd){

    $('.error-text').text('');
    $('.red_border').css('border-color','#e4e6fc');

    $("#amb_edit_pop").click();
    $("#emp_id").html(emp_id);
    $("#u_emp_id").val(emp_id);
    $("#name").val(name);
    $("#pan_num").val(pan_no);
    $("#dob").val(dob);
    $("#mobileno").val(mobileno);
    $("#lwd").val(lwd);
    $("#email").val(email);
    $("#u_id").val(id);
}



$('#reset_pass').click(function () { 
    $('#reset_pass').attr("disabled",true);
    var emp_id = $("#u_emp_id").val();
    $.ajax({
        type: "POST",
        url: reset_password,
        data: {emp_id:emp_id},
        dataType: "JSON",
        success: function (data) {
            if(data.res == "success"){
                $("#pass_up").css("display","block");
                $("#pass_up").html('<b style="color:green;">Updated Successfully..!</b></b>');
                $("#pass_up").delay(3000).fadeOut(500);
                get_ambassadors();
                var explode = function(){
                    $(".close").click();
                    $('#reset_pass').attr("disabled",false);
                };
                setTimeout(explode, 3000);
            }
        }
    })
})


$('#update_amb').click(function () { 
    $('#update_amb').attr("disabled",true);
    $.ajax({
        type: "POST",
        url: update_amb_form,
        data: $("#update_amb_form").serialize(),
        dataType: "JSON",
        beforeSend:function(){
            $('.error-text').text('');
            $('.red_border').css('border-color','#e4e6fc');
        },
        success: function (data) {
            if(data.res == "success"){
                $("#up_amb").css("display","block");
                $("#up_amb").html('<b style="color:green;">Updated Successfully..!</b></b>');
                $("#up_amb").delay(3000).fadeOut(500);
                get_ambassadors();
                var explode = function(){
                    $(".close").click();
                    $('#update_amb').attr("disabled",false);
                };
                setTimeout(explode, 3000);
            }
            if(data.status == 0){
                $.each(data.error, function(i,val){
                    $('#'+i+'_error').text(val[0]);
                    $('#'+i).css('border-color','red');
                })
                $('#update_amb').attr("disabled",false);
            }
        }
    })
})


        

$('#download').click(function () { 
  
    var emp_id=$("#hidden_pop_emp_id").val();

    $.ajax
    ({
      type: "POST", 
      url: downloadPDF,
      data: {"emp_id":emp_id},
      success: function(response)
      {
        // var host = window.location.origin+'/CK_Alumni/public';
        // var host = window.location.origin;
        // var host_url = host+"/"+response;
        var host_url = response;

        window.open(host_url, '_blank').focus();
      }
    });
});

// Function to open Form 16 bulk upload modal
function openForm16BulkModal() {
    $('#form16BulkUploadModal').modal('show');
}