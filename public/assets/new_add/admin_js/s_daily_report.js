$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    template = Handlebars.compile($("#details-template").html());
    var append_table="pending_query_tbl";
    get_query('Pending',append_table);

    load_emp();
    $("#tab_type").val("Pending");

}); 

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
       .columns.adjust();
 });

 
function load_emp(){
    
    $.ajax({  
        type: "POST",
        url: adm_get_emp_sel_box, 
        data: {},
        dataType: "JSON",

        success: function (data) {

            if(data.response=="Success"){

                $("#slectbox_hr").html(data.emp_div);

            }
        }
    })

}

$("#slectbox_hr").change(function(){
    get_query();
})
$("#filter_date").change(function(){
    get_query();
})

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

function get_query(){


        table = $('#daily_report').DataTable({
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
                url: get_s_admin_daily_report,  
                type: 'POST',
                data: function (d) {
                    d.hr_id = $('#slectbox_hr').val();
                    d.filter_date = $('#filter_date').val();
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
                {data: 'ticket_id', name: 'ticket_id'},
                {data: 'emp_id', name: 'emp_id'},
                {data: 'document', name: 'document'},
                {data: 'remark', name: 'remark'},
                {data: 'updated_by', name: 'updated_by'},
                // {data: 'action', name: 'action'},
            ],
            // createdRow: function ( row, data, index ) {
            //     table.columns.adjust().draw()
            // }
            
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

function update_query_click_dec_tab(ticket_id,type){

    if(type=="Declined"){
        $("#dec_rem").css("display","block");
    }
    else{
        $("#dec_rem").css("display","none");
    }
    // pop open

    $("#confirm_pop_submit").unbind('click');

    $("#confirm_pop_trigger").click();
    $("#confirm_pop_submit").click(function(e){

        e.preventDefault();

        var dec_remark="";
        if(type=="Declined"){
            dec_remark=$("#dec_remark").val();
        }

        $("#confirm_pop_submit").attr("disabled","true");
        $("#confirm_pop_submit").addClass("btn-progress");

        $.ajax({
            type: "POST",
            url: update_query,
            data: { 'ticket_id':ticket_id,'type':type,},
            success: function (data) {
                
                $("#resp").css("display","block");
                $("#resp").html('<b style="color:green;">Updated</b></b>');
                $("#resp").delay(3000).fadeOut(500);


                var explode = function(){
                    $("#confirm_pop_submit").removeAttr("disabled");
                    $("#confirm_pop_submit").removeClass("btn-progress");

                    declined_tab_click();
                    $(".close").click();
                };
                setTimeout(explode, 3000);

                // send mail tigger
                $.ajax({
                    type: "POST",
                    url: send_qry_stats_mail,
                    data: { 'ticket_id':data.ticket_id,},
                    success: function (data) {
            
                        if (data.response == "success") {
            
                            
            
                        } 
                        
                        
                    }
                })
                // send mail end
                
            }
        })
    })
}

function upload_document(ticket_id,emp_name,emp_id,document_string,remark){
    
    $(".doc_upl").css("display","none");

    $("#pop_ticket_id").html(ticket_id);
    $("#pop_emp_name").html(emp_name);
    $("#pop_emp_name").html(emp_name);
    $("#pop_emp_id").html(emp_id);
    $("#emp_id").val(emp_id);
    $("#pop_emp_remark").html(remark);
    $("#pop_document").val(document_string);
    $("#ticket_id_hidden").val(ticket_id);

    if(document_string.includes("Pay Slips")){
        $("#pay_slip_input").css("display","block");
    }
    if(document_string.includes("F&F Statement")){
        $("#ff_statement_input").css("display","block");
    }
    if(document_string.includes("Form 16")){
        $("#form_16_input").css("display","block");
    }
    if(document_string.includes("Relieving Letter")){
        $("#rel_letter_input").css("display","block");
    }
    if(document_string.includes("Service Letter")){
        $("#ser_letter_input").css("display","block"); 
    }

    if(document_string.includes("Bonus")){
        $("#bonus_input").css("display","block"); 
    }
    if(document_string.includes("Performance Incentive")){
        $("#performance_incentive_input").css("display","block"); 
    }
    if(document_string.includes("Sales Travel claim")){
        $("#sales_travel_claim_input").css("display","block"); 
    }
    if(document_string.includes("Parental medical reimbursement")){
        $("#parental_medical_reimbursement_input").css("display","block"); 
    }
    // type2
    if(document_string.includes("PF")){
        $("#pf_input").css("display","block"); 
    }
    if(document_string.includes("Gratuity")){
        $("#gratuity_input").css("display","block"); 
    }
    // end type 2

    if(document_string.includes("Others")){
        $("#others_input").css("display","block");
    }

    $("#upl_doc_pop_trigger").click();


}



function doc_detail(ticket_id,emp_id,emp_name,admin_remark){

    $.ajax({
        type: "POST",
        url: doc_updated_detail,
        data: { 'ticket_id':ticket_id,'emp_id':emp_id,},
        success: function (data) {

            if (data.response == "success") {

                $("#doc_s_ticket_id").html(ticket_id);
                $("#doc_s_emp_name").html(emp_name);
                $("#doc_s_emp_id").html(emp_id);
                $("#doc_s_emp_remark").html(admin_remark);

                $("#doc_show_div").html(data.show_div);
                $("#show_doc_pop_trigger").click();

            } 
            
            
        }
    })

}