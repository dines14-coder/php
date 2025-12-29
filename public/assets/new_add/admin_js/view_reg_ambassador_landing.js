$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    template = Handlebars.compile($("#details-template").html());
    get_ambassadors();
}); 

function pending_amb(){
    get_ambassadors();
}
function declined_amb(){
    get_declined_ambassadors();
}

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

function processInfo_2(info) {
    var res_found = info.recordsDisplay;
    $('.total_res_show_2').text(res_found);
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
            url: get_all_reg_alumni_datatable,  
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
            {data: 'pan_no', name: 'pan_no'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action'},
        ],
        
    });
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

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
       .columns.adjust();
 });



function get_declined_ambassadors(){ 


    

    table = $('#declined_amb_tbl').DataTable({
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
            processInfo_2(this.api().page.info());
        },

        ajax: {
            url: get_all_declined_alumni_datatable,  
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
            {data: 'pan_no', name: 'pan_no'},
            {data: 'remark', name: 'remark'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action'},
        ],
        
        
    });
} 



function update_reg_amb(emp_id,type){

    $('.error-text').text('');
    $('.red_border').css('border-color','#e4e6fc');
      
    // pop open
    var status="";
    if(type=="Declined"){
        status="Declined";
        $("#dec_rem").css("display","block");
        $(".hide_div").attr("hidden",true);
    }
    else{
        status="Active";
        $("#dec_rem").css("display","none");
        $(".hide_div").attr("hidden",false);
    }
  
    $("#confirm_pop_submit").unbind('click'); 

    $("#confirm_pop_trigger").click();
    $("#confirm_pop_submit").click(function(e){ 

        e.preventDefault();
        if($('#checkbox').is(":checked")){
           var f_f_document= "Yes"; 
        }else{
            var f_f_document= "No"; 
        }
        dec_remark=$("#dec_remark").val();
        type_of_leaving=$("#type_of_leaving").val();
        last_working_date=$("#last_working_date").val();


        $("#confirm_pop_submit").html("Loading..");
        $("#confirm_pop_submit").attr("disabled","true");
       
        $.ajax({
            type: "POST",
            url: update_reg_alumni,
            data: { 'emp_id':emp_id,'f_f_document':f_f_document,'last_working_date':last_working_date,'status':status,'type_of_leaving':type_of_leaving,'dec_remark':dec_remark},
            beforeSend:function(){
                $('.error-text').text('');
                $('.red_border').css('border-color','#e4e6fc');
            },
            success: function (data) {

               
                $("#confirm_pop_submit").html("Confirm");
                if(data.status == 0){
                    $.each(data.error, function(i,val){
                        $('.'+i+'_error').text("Please fill in this field.");
                        $('#'+i).css('border-color','red');
                    })
                    $("#confirm_pop_submit").removeAttr("disabled");
                } 

                if (data.response == "success") {
                    
                    $("#resp").css("display","block");
                    $("#resp").html('<b style="color:green;">Updated</b></b>');
                    $("#resp").delay(3000).fadeOut(500);

                    $("#dec_remark").val("");
                    $("#type_of_leaving").val("");
                    $("#last_working_date").val("");
                    $("#checkbox").removeAttr("checked");


                    var explode = function(){
                        get_ambassadors();
                        get_declined_ambassadors();
                        $(".close").click();
                        $("#confirm_pop_submit").removeAttr("disabled");
                    };
                    setTimeout(explode, 3000);

                    if(type!=="Declined"){

                         // send mail tigger
                        $.ajax({
                            type: "POST",
                            url: send_emp_status_mail,
                            data: { 'emp_id':data.emp_id,},
                            success: function (data) {
                    
                                if (data.response == "success") {
                    
                                } 
                                
                                
                            }
                        })
                        // send mail end
                    }


                }

            }
        })
    })
    
}