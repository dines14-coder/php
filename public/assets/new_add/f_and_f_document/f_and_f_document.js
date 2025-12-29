$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    template = Handlebars.compile($("#details-template").html());
    var append_table="fresh_c_p_tbl";
    get_ambassadors('Fresh',append_table);
    $("#tab_type").val("Fresh");
    
}); 

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
       .columns.adjust();
 });

function fresh_tab_click(){
    append_table="fresh_c_p_tbl";
    $("#tab_type").val("Fresh");
    get_ambassadors('Fresh',append_table);
}
function inprogress_tab_click(){ 
    append_table="inprogress_c_p_tbl";
    $("#tab_type").val("InProgress");
    get_ambassadors('InProgress',append_table);
}
function completed_tab_click(){
    append_table="completed_c_p_tbl";
    $("#tab_type").val("Completed");
    get_ambassadors('Completed',append_table);
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

function get_ambassadors(type,append_table){ 


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
                url: get_c_p_datatable,
                type: 'POST', 
                data: function (d) {
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
                    "searchable":      true,
                    "data":           null,
                    "defaultContent": ''
                },
                {data: 'DT_RowIndex', name: 'DT_RowIndex' ,orderable: false, searchable: false},
                {data: 'emp_id', name: 'emp_id'},
                {data: 'questions', name: 'questions'},
                // {data: 'action', name: 'action'},
            ],
            createdRow: function ( row, data, index ) {
                if( data['f_f_document']  == "yes" ){
                    $(row).css({"background-color":"#91d9e5cc"});
                }
                if ( data['type_of_leaving']  == "Terminated" || data['type_of_leaving']  == "Abscond") {
                    $(row).css({"background-color":"#f305052b"});
                } else {
                    $(row).addClass('a');
                }
            }
               
            
        });

    }
    if(type=="InProgress"){
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
                url: get_c_p_datatable,
                type: 'POST', 
                data: function (d) {
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
                    "searchable":      true,
                    "data":           null,
                    "defaultContent": ''
                },
                {data: 'DT_RowIndex', name: 'DT_RowIndex' ,orderable: false, searchable: false},
                {data: 'emp_id', name: 'emp_id'},
                {data: 'questions', name: 'questions'},
                {data: 'action', name: 'action'},
            ],
            createdRow: function ( row, data, index ) {
                if( data['f_f_document']  == "yes" ){
                    $(row).css({"background-color":"#91d9e5cc"});
                }
                if ( data['type_of_leaving']  == "Terminated" || data['type_of_leaving']  == "Abscond") {
                    $(row).css({"background-color":"#f305052b"});
                } else {
                    $(row).addClass('a');
                }
            }
            
        });
        

    }
    if(type=="Fresh"){
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
                url: get_c_p_datatable,
                type: 'POST', 
                data: function (d) {
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
                    "searchable":      true,
                    "data":           null,
                    "defaultContent": ''
                },
                {data: 'DT_RowIndex', name: 'DT_RowIndex' ,orderable: false, searchable: false},
                {data: 'emp_id', name: 'emp_id'},
                {data: 'questions', name: 'questions'},
                {data: 'action', name: 'action'},
            ],
            createdRow: function ( row, data, index ) {
                if( data['f_f_document']  == "yes" ){
                    $(row).css({"background-color":"#91d9e5cc"});
                }
                if ( data['type_of_leaving']  == "Terminated" || data['type_of_leaving']  == "Abscond") {
                    $(row).css({"background-color":"#f305052b"});
                } else {
                    $(row).addClass('a');
                }
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



function save_ratings(emp_id){
    var emp_id = emp_id;
    var remarks=Array();
    var rating=Array();
    var Question_id=Array();
    var rating1 = Array();
    
    // Disable the button and show loading state
    $("#save_f_and_f_doc").attr("disabled", "disabled");
    $("#save_f_and_f_doc").html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    
    $(".rating_"+emp_id).each(function(i, v){
            rating1[i] = $(this).val();
        
            rating[i] =  $(this).closest('tr').find(":selected").val();
    })
    $(".Question_id_"+emp_id).each(function(i, v){
        Question_id[i] = $(this).val();
    })
    $(".remarks_"+emp_id).each(function(i, v){
        remarks[i] = $(this).val();
    })
    
    $.ajax({  
        type: "POST",
        url: add_f_and_f_document,  
        data: {'emp_id':emp_id,'Question_id':Question_id,'rating':rating,'remarks':remarks},  
        dataType: "JSON",
        success: function (response) {
            // Always restore button state regardless of response
            $("#save_f_and_f_doc").removeAttr("disabled");
            
            // Restore original button text based on tab type
            var btnText = $("#tab_type").val() == "Fresh" ? "Save" : "Update";
            $("#save_f_and_f_doc").html('<span><i class="fa fa-save"></i></span>&nbsp;' + btnText);
            
            if(response.response=="Success"){
                toastr.success('Updated successfully.');

                if($("#tab_type").val()=="Fresh"){
                    fresh_tab_click();
                }
                if($("#tab_type").val()=="InProgress"){
                    inprogress_tab_click();
                }
                if($("#tab_type").val()=="Completed"){
                    completed_tab_click();
                }
            }
        },
        error: function(er){
            // Restore button state on error
            $("#save_f_and_f_doc").removeAttr("disabled");
            
            // Restore original button text based on tab type
            var btnText = $("#tab_type").val() == "Fresh" ? "Save" : "Update";
            $("#save_f_and_f_doc").html('<span><i class="fa fa-save"></i></span>&nbsp;' + btnText);
            
            toastr.error("Select field is required.");
        },
        timeout: 30000 // Add a timeout of 30 seconds
    })
}
