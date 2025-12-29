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
    var tab_type=$("#tab_type").val();

    if(tab_type=="Pending"){
        pending_tab_click();
    }
    if(tab_type=="Approved"){
        inprogress_tab_click();
    }
    if(tab_type=="Completed"){
        completed_tab_click();
    }
    if(tab_type=="Declined"){
        declined_tab_click();
    }

})

function pending_tab_click(){
    $("#tab_type").val("Pending");

    append_table="pending_query_tbl";
    get_query('Pending',append_table);
}
function inprogress_tab_click(){
    $("#tab_type").val("Approved");

    append_table="inprogress_query_tbl";
    get_query('Approved',append_table);
}
function completed_tab_click(){
    $("#tab_type").val("Completed");

    append_table="completed_query_tbl";
    get_query('Completed',append_table);
}
function declined_tab_click(){
    $("#tab_type").val("Declined");

    append_table="declined_query_tbl";
    get_query('Declined',append_table);
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

function get_query(type,append_table){


    if(type=="Completed"){

        table = $('#'+append_table).DataTable({
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
                url: get_p_s_admin_query_datatable,  
                type: 'POST',
                data: function (d) {
                    d.hr_id = $('#slectbox_hr').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.type = type;
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
                {data: 'emp_name', name: 'emp_name'},
                // {data: 'document_div', name: 'document_div'},
                {data: 'remark', name: 'remark'},
                {data: 'type_of_leaving', name: 'type_of_leaving'},
                {data: 'created_at', name: 'created_at'},
                // {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
            createdRow: function ( row, data, index ) {
                if ( data['type_of_leaving']  == "Terminated" || data['type_of_leaving']  == "Abscond") {
                    $(row).css({"background-color":"#f305052b"});
                } else {
                    $(row).addClass('a');
                }
                // table.columns.adjust().draw()
            }
        });

    }
    else{

    table = $('#'+append_table).DataTable({
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
            url: get_p_s_admin_query_datatable,  
            type: 'POST',
            data: function (d) {
                d.hr_id = $('#slectbox_hr').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.type = type;
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
            {data: 'document_div', name: 'document_div'},
            {data: 'remark', name: 'remark'},
            {data: 'type_of_leaving', name: 'type_of_leaving'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            // {data: 'action', name: 'action'},
        ],
        createdRow: function ( row, data, index ) {
            if ( data['type_of_leaving']  == "Terminated" || data['type_of_leaving']  == "Abscond") {
                $(row).css({"background-color":"#f305052b"});
            } else {
                $(row).addClass('a');
            }
            // table.columns.adjust().draw()
        }
    });

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

function update_query_click(ticket_id,type){
    // pop open

    if(type=="Declined"){
        $("#dec_rem").css("display","block");
    }
    else{
        $("#dec_rem").css("display","none");
    }

    $("#confirm_pop_submit").unbind('click');

    $("#confirm_pop_trigger").click();
    $("#confirm_pop_submit").click(function(e){ 

        e.preventDefault();

        var dec_remark="";
        if(type=="Declined"){
            dec_remark=$("#dec_remark").val();
        }

        $("#confirm_pop_submit").html("Loading..");
        $("#confirm_pop_submit").attr("disabled","true");

        $.ajax({
            type: "POST",
            url: update_query,
            data: { 'ticket_id':ticket_id,'type':type},
            success: function (data) {

                if (data.response == "success") {
                    
                    $("#resp").css("display","block");
                    $("#resp").html('<b style="color:green;">Updated</b></b>');
                    $("#resp").delay(3000).fadeOut(500);


                    var explode = function(){
                        $("#confirm_pop_submit").removeAttr("disabled");

                        pending_tab_click();
                        $(".close").click();
                        $("#confirm_pop_submit").html("Confirm");

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



$("#doc_upload_form").submit(function(e){
    e.preventDefault();

    $("#document_upload_submit").attr("disabled","true"); 

    var formData = new FormData(this);

    var pay_slip=$("#pay_slip_doc")[0];
    var ff_statement=$("#ff_statement_doc")[0];
    var form16=$("#form_16_doc")[0];
    var rel_letter=$("#rel_letter_doc")[0];
    var ser_letter=$("#ser_letter_doc")[0];

    var bonus=$("#bonus_doc")[0];
    var performance_incentive=$("#performance_incentive_doc")[0];
    var sales_travel_claim=$("#sales_travel_claim_doc")[0];
    var parental_medical_reimbursement=$("#parental_medical_reimbursement_doc")[0];
    // type2
    var pf=$("#pf_doc")[0];
    var gratuity=$("#gratuity_doc")[0];
    // end type2

    var others_doc=$("#others_doc")[0];
    var pop_document=$("#pop_document").val();
    var emp_id=$("#emp_id").val();
    var ticket_id=$("#ticket_id_hidden").val();

    formData.append('emp_id', emp_id);
    formData.append('ticket_id', ticket_id);

    if(pop_document.includes("Pay Slips")){
        if($("#pay_slip_doc").val()!== ''){
            formData.append('pay_slip', pay_slip.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("F&F Statement")){
        if($("#ff_statement_doc").val()!== ""){
            formData.append('ff_statement', ff_statement.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Form 16")){
        if($("#form_16_doc").val()!== ""){
            formData.append('form16', form16.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Relieving Letter")){
        if($("#rel_letter_doc").val()!== ""){
            formData.append('rel_letter', rel_letter.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Service Letter")){
        if($("#ser_letter_doc").val()!== ""){
            formData.append('ser_letter', ser_letter.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Bonus")){
        if($("#bonus_doc").val()!== ""){
            formData.append('bonus', bonus.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    // if(pop_document.includes("Parental medical reimbursement")){
    //     if($("#parental_medical_reimbursement_doc").val()!== ""){
    //         formData.append('parental_medical_reimbursement', bonus.files[0]);
    //     } 
    //     else{
    //         $("#doc_resp").css("display","block");
    //         $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
    //         $("#doc_resp").delay(3000).fadeOut(500);
    //         $("#document_upload_submit").removeAttr("disabled");
    //         return false;
    //     }
    // }
    if(pop_document.includes("Performance Incentive")){
        if($("#performance_incentive_doc").val()!== ""){
            formData.append('performance_incentive', performance_incentive.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Sales Travel claim")){
        if($("#sales_travel_claim_doc").val()!== ""){
            formData.append('sales_travel_claim', sales_travel_claim.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Parental medical reimbursement")){
        if($("#parental_medical_reimbursement_doc").val()!== ""){
            formData.append('parental_medical_reimbursement', parental_medical_reimbursement.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    // type2
    if(pop_document.includes("PF")){
        if($("#pf_doc").val()!== ""){
            formData.append('pf', pf.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    if(pop_document.includes("Gratuity")){
        if($("#gratuity_doc").val()!== ""){
            formData.append('gratuity', gratuity.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }
    // end type2

    if(pop_document.includes("Others")){
        if($("#others_doc").val()!== ""){
            formData.append('others_doc', others_doc.files[0]);
        } 
        else{
            $("#doc_resp").css("display","block");
            $("#doc_resp").html('<b style="color:red;">Need All Fields..!</b></b>');
            $("#doc_resp").delay(3000).fadeOut(500);
            $("#document_upload_submit").removeAttr("disabled");
            return false;
        }
    }

    $("#document_upload_submit").html("Loading..");



    // mail send cnt
    var send_mail_cnt=parseInt($("#send_mail_cnt").html());
    var new_count=send_mail_cnt+1;
    $("#send_mail_cnt").html(new_count);
    // mail send cnt end

    $.ajax({  
        type: "POST",
        url: doc_upload_admin_submit,  
        data: formData, 
        cache:false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.response == "success") {
                // Show success message only when upload is actually successful
                $("#doc_resp").css("display", "block");
                $("#doc_resp").html(
                    '<b style="color:green;">Updated Successfully..!</b>'
                );
                $("#doc_resp").delay(3000).fadeOut(500);
                
                // Reset form and close modal after successful upload
                setTimeout(function() {
                    inprogress_tab_click();
                    $(".close").click();
                    $("#doc_upload_form")[0].reset();
                    $("#document_upload_submit").html("Submit");
                    $("#document_upload_submit").removeAttr("disabled");
                }, 2000);

                // mail send cnt
                send_mail_cnt=parseInt($("#send_mail_cnt").html());
                new_count=send_mail_cnt-1;
                $("#send_mail_cnt").html(new_count);
            } else if (data.status == 0 && data.error) {
                // Handle validation errors - do NOT show success message
                $("#document_upload_submit").html("Submit");
                $("#document_upload_submit").removeAttr("disabled");
                
                // mail send cnt - revert count on error
                send_mail_cnt = parseInt($("#send_mail_cnt").html());
                new_count = send_mail_cnt - 1;
                $("#send_mail_cnt").html(new_count);
                
                // Clear previous error messages
                $(".error-text").html("");
                $(".form-control").removeClass("is-invalid");
                $(".invalid-feedback").remove();
                
                // Clear file inputs for failed uploads
                $("#form_16_part_a").val("");
                $("#form_16_part_b").val("");
                
                // Display validation errors
                $.each(data.error, function(field, messages) {
                    var errorMessage = Array.isArray(messages) ? messages[0] : messages;
                    
                    if (field === 'form_16_part_a') {
                        $("#form_16_part_a").addClass("is-invalid");
                        $("#form_16_part_a").after('<div class="invalid-feedback">' + errorMessage + '</div>');
                        toastr.error("Form 16 Part A: " + errorMessage);
                    } else if (field === 'form_16_part_b') {
                        $("#form_16_part_b").addClass("is-invalid");
                        $("#form_16_part_b").after('<div class="invalid-feedback">' + errorMessage + '</div>');
                        toastr.error("Form 16 Part B: " + errorMessage);
                    } else {
                        toastr.error(errorMessage);
                    }
                });
                
                // Show error message in modal
                $("#doc_resp").css("display", "block");
                $("#doc_resp").html(
                    '<b style="color:red;">Upload Failed - Please check the errors above</b>'
                );
                $("#doc_resp").delay(20000).fadeOut(500);
                
                // Auto refresh page after 20 seconds when there's an error
                setTimeout(function() {
                    location.reload();
                }, 20000);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors
            $("#document_upload_submit").html("Submit");
            $("#document_upload_submit").removeAttr("disabled");
            
            // mail send cnt - revert count on error
            send_mail_cnt = parseInt($("#send_mail_cnt").html());
            new_count = send_mail_cnt - 1;
            $("#send_mail_cnt").html(new_count);
            
            toastr.error("An error occurred while uploading documents. Please try again.");
            
            $("#doc_resp").css("display", "block");
            $("#doc_resp").html(
                '<b style="color:red;">Upload Failed - Server Error</b>'
            );
            $("#doc_resp").delay(20000).fadeOut(500);
            
            // Auto refresh page after 20 seconds when there's a server error
            setTimeout(function() {
                location.reload();
            }, 20000);
        }
    })

})

// Handle modal close button click for document upload modal
$("#doc_upload_pop").on("hidden.bs.modal", function () {
    // Clear file inputs when modal is closed
    $("#form_16_part_a").val("");
    $("#form_16_part_b").val("");
    
    // Check if there were any validation errors displayed
    if ($("#doc_resp").is(":visible") && $("#doc_resp").html().includes("Upload Failed")) {
        // Refresh page when modal is closed after an error
        location.reload();
    }
});

// Handle modal open to ensure clean state
$("#doc_upload_pop").on("shown.bs.modal", function () {
    // Clear file inputs when modal is opened
    $("#form_16_part_a").val("");
    $("#form_16_part_b").val("");
    
    // Clear any previous error messages
    $("#doc_resp").hide();
    $(".error-text").html("");
    $(".form-control").removeClass("is-invalid");
    $(".invalid-feedback").remove();
});

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