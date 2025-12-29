$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $('input[name="daterange_f"]').daterangepicker({
        opens: "left",
    });
    $('input[name="daterange"]').daterangepicker({
        opens: "left",
    });
    $('input[name="daterange_f"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: "Clear",
        },
    });
    $('input[name="daterange1"]').daterangepicker({
        opens: "left",
    });
    $('input[name="daterange2"]').daterangepicker({
        opens: "left",
    });
    $('input[name="daterange3"]').daterangepicker({
        opens: "left",
    });
    template = Handlebars.compile($("#details-template").html());
    var append_table = "completed_within_tat_tbl";
    $('#tab_type').val('daily_report')
    get_data($('#tab_type').val(), append_table,'completed_within_tat_tbl');


    $(".select2").each(function () {
        var $p = $(this).parent();
        $(this).select2({
            dropdownParent: $p,
        });
    });
});

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$('#function_filter').on('focus',function(){
    $('#select').attr('disabled','');
})

$('#function_filter').on('change',function(){
    function_report();
})
$('#daterange_f').on('change',function(){
    pending_within_tat();
})

function completed_within_tat() {
    $(".dataTables_filter").remove('#date_label1')
    $(".dataTables_filter").remove('#date_label2')
    $(".dataTables_filter").remove('#date_label3')
    append_table = "completed_within_tat_tbl";
    get_data($("#tab_type").val(), append_table,'completed_within_tat_tbl');
}
function completed_beyond_tat() {
    // $(".dataTables_filter").remove('#date_label')
    $(".dataTables_filter").remove('#date_label2')
    $(".dataTables_filter").remove('#date_label')
    $(".dataTables_filter").remove('#date_label3')
    append_table = "completed_beyond_tat_tbl";
    get_data($("#tab_type").val(), append_table,'completed_beyond_tat_tbl');
}
function pending_beyond_tat() {
    $(".dataTables_filter").remove('#date_label1')
    $(".dataTables_filter").remove('#date_label')
    $(".dataTables_filter").remove('#date_label2')
    append_table = "pending_beyond_tat_tbl";
    get_data($("#tab_type").val(), append_table,'pending_beyond_tat_tbl');
}
function pending_within_tat() {
    $(".dataTables_filter").remove('#date_label1')
    $(".dataTables_filter").remove('#date_label')
    $(".dataTables_filter").remove('#date_label3')

    append_table = "pending_within_tat_tbl";
    get_data($("#tab_type").val(), append_table,'pending_within_tat_tbl');
}

function daily_report() {
    // append_table = "daily_report_tbl";
    // get_data("daily_report", append_table);
    $("#tab_type").val("daily_report");
    $('#completed_within_tat_tab').click();
    $('#view_function_filter').addClass('d-none');
    $('#category_tab').removeClass('d-none');
 
}
function weekly_report() {
    $("#tab_type").val("weekly_report");
    $('#completed_within_tat_tab').click();
    $('#view_function_filter').addClass('d-none');
    $('#category_tab').removeClass('d-none');
 
 
    // append_table = "weekly_report_tbl";
    // get_data("weekly_report", append_table);
}
function function_report() {
    $("#tab_type").val("function_report");
    $('#completed_within_tat_tab').click();
    $('#category_tab').removeClass('d-none');
 
    // append_table = "function_report_tbl";
    $('#view_function_filter').removeClass('d-none');
    // get_data("function_report", append_table);
}
function ageing_report() {
    $("#tab_type").val("ageing_report");
    $('#completed_within_tat_tab').click();
    $('#view_function_filter').addClass('d-none');
    // $('#category_tab').addClass('d-none');
    // append_table = "ageing_report_tbl";
    // get_data("ageing_report", append_table);
}


// function daily_report() {
//     append_table = "daily_report_tbl";
//     get_data("daily_report", append_table);
// }
// function weekly_report() {

//     append_table = "weekly_report_tbl";
//     get_data("weekly_report", append_table);
// }
// function function_report() {

//     append_table = "function_report_tbl";
//     $('#view_function_filter').removeClass('d-none');
//     get_data("function_report", append_table);
// }
// function ageing_report() {

//     append_table = "ageing_report_tbl";
//     get_data("ageing_report", append_table);
// }
// for export all data
function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one("preXhr", function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one("preDraw", function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf("buttons-copy") >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self,e,dt,button,config);
            } else if (button[0].className.indexOf("buttons-excel") >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.excelHtml5.action.call(self,e,dt,button,config)
                    : $.fn.dataTable.ext.buttons.excelFlash.action.call(self,e,dt,button,config);
            } else if (button[0].className.indexOf("buttons-csv") >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.csvHtml5.action.call(self,e,dt,button,config)
                    : $.fn.dataTable.ext.buttons.csvFlash.action.call(self,e,dt,button,config);
            } else if (button[0].className.indexOf("buttons-pdf") >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self,e,dt,button,config)
                    : $.fn.dataTable.ext.buttons.pdfFlash.action.call(self,e,dt,button,config);
            } else if (button[0].className.indexOf("buttons-print") >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one("preXhr", function (e, s, data) {
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            setTimeout(dt.ajax.reload, 0);
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
}

function processInfo(info) {
    var res_found = info.recordsDisplay;
    $(".total_res_show").text(res_found);
}
var cur_type ;
var table_name ;

function get_data(type, append_table,table_id) {
    cur_type = type;
    table_name =table_id;
    if(cur_type == 'ageing_report'){
        is_age_report = true;
    }else{
        is_age_report = false;
    }
    table = $("#" + append_table).DataTable({
        lengthMenu: [
            [50, 100, 200, 300, 400, 500, 1000, -1],
            [50, 100, 200, 300, 400, 500, 1000, "All"],
        ],
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-file-excel" ></i>  Excel',
                titleAttr: "Excel",
                exportOptions: {
                    columns: ":visible",
                },
                action: newexportaction,
            },
            {
                extend: "csv",
                text: '<i class="fa fa-file" ></i>  CSV',
                titleAttr: "CSV",
                exportOptions: {
                    columns: ":visible",
                },
                action: newexportaction,
            },
            {
                extend: "pdf",
                text: '<i class="fa fa-file-pdf" ></i>  PDF',
                titleAttr: "PDF",
                exportOptions: {
                    columns: ":visible",
                },
                action: newexportaction,
            },
            {
                extend: "print",
                text: '<i class="fa fa-print" ></i>  Print',
                titleAttr: "Print",
                exportOptions: {
                    columns: ":visible",
                },
                action: newexportaction,
            },
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        autoWidth: false,
        scrollX: true,
        iDisplayLength: 50,
        drawCallback: function () {
            processInfo(this.api().page.info());
        },

        ajax: {
            url: "view_report",
            type: "POST",
            data: function (d) {
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.type = cur_type;
                d.table = table_name;
                console.log(table_name);
                console.log( $('#daterange_f').val());
                var dateRange = $('input[name="daterange_f"]').data('daterangepicker');
                console.log( $('#daterange_f').val());
                if (dateRange && $('#daterange_f').val() != '' && d.from_date != d.to_date) {
                    d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                    d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                }
                    console.log($('#daterange').val());
                    console.log($('#daterange1').val());
                    console.log($('#daterange2').val());
                    console.log($('#daterange3').val());
                if(table_name == 'completed_within_tat_tbl'){
                    

                    var dateRange = $('input[name="daterange"]').data('daterangepicker');
                    if (dateRange && $('#daterange').val() != '') {
                        d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                        d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                    }
                }else if(table_name == 'completed_beyond_tat_tbl'){
                    var dateRange = $('input[name="daterange1"]').data('daterangepicker');
                    if (dateRange && $('#daterange1').val() != '') {
                        d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                        d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                    }
                }else if(table_name == 'pending_beyond_tat_tbl'){
                    var dateRange = $('input[name="daterange3"]').data('daterangepicker');
                    if (dateRange && $('#daterange3').val() != '') {
                        d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                        d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                    }
                }else if(table_name == 'pending_within_tat_tbl'){
                    var dateRange = $('input[name="daterange2"]').data('daterangepicker');
                    if (dateRange && $('#daterange2').val() != '') {
                        d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                        d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                    }
                }
                
                var date = $('input[name="date"]');
                if (date) {
                    d.dr_date = $("#date").val();
                } 
                if(cur_type == "function_report"){
                    d.function = $('#function_filter').val();
                }
            },
        },
        columns: [
            {
                className: "details-control",
                orderable: false,
                searchable: false,
                data: null,
                defaultContent: "",
            },
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "emp_id", name: "emp_id" },
            { data: "emp_name", name: "emp_name" },
            // {data: "received_date", name: "received_date" },
            {data: "completed_date", name: "completed_date" },
            {data: "completed_time", name: "completed_time" },
            {data: "age_report", name: "age_report" ,visible:is_age_report},
        ],
        createdRow: function (row, data, index) {
            if (data["f_f_document"] == "yes") {
                $(row).css({ "background-color": "#91d9e5cc" });
            }
            if (
                data["type_of_leaving"] == "Terminated" ||
                data["type_of_leaving"] == "Abscond"
            ) {
                $(row).css({ "background-color": "#f305052b" });
            } else {
                $(row).addClass("a");
            }
        },
    });
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    if (type == "ageing_report" && table_id == "completed_within_tat_tbl") {
        $(".dataTables_filter").append(
            '&nbsp&nbsp&nbsp<label id="date_label">Date Range: <input type="text" name="daterange" id="daterange"  class ="form-control form-control-sm"  value="" /></label>'
        );
    
        // Apply the date range filter
        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: "Clear",
            },
        });
        $('input[name="daterange"]').on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
                
                table.draw();
            }
        );
            $('#daterange').val('');
        $('input[name="daterange"]').on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
                table.draw();
            }
        );
        $('input[name="daterange"]').on('focus', function (ev,picker) {
            
            if($(this).val()){
                $('.applyBtn').removeAttr("disabled",'');
            }else{
                $('.applyBtn').attr("disabled",'');
            }
         });
    }
    if (type == "ageing_report" && table_id == "completed_beyond_tat_tbl") {
        $(".dataTables_filter").append(
            '&nbsp&nbsp&nbsp<label id="date_label1">Date Range1: <input type="text" name="daterange1" id="daterange1"  class ="form-control form-control-sm"  value="" /></label>'
        );
    
        // Apply the date range filter
        $('input[name="daterange1"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: "Clear",
            },
        });
        $('input[name="daterange1"]').on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
                
                table.draw();
            }
        );
            $('#daterange1').val('');
        $('input[name="daterange1"]').on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
                table.draw();
            }
        );
        $('input[name="daterange1"]').on('focus', function (ev,picker) {
            
            if($(this).val()){
                $('.applyBtn').removeAttr("disabled",'');
            }else{
                $('.applyBtn').attr("disabled",'');
            }
         });
    } 
    if (type == "ageing_report" && table_id == "pending_within_tat_tbl") {
        $(".dataTables_filter").append(
            '&nbsp&nbsp&nbsp<label id="date_label2">Date Range2: <input type="text" name="daterange2" id="daterange2"  class ="form-control form-control-sm"  value="" /></label>'
        );
    
        // Apply the date range filter
        console.log('Before initializing datepicker');
        $('input[name="daterange2"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: "Clear",
            },
        });
        console.log('After initializing datepicker');
        $('input[name="daterange2"]').on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
                
                table.draw();
            }
        );
            // $('#daterange2').val('');
        $('input[name="daterange2"]').on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
                table.draw();
            }
        );
        $('input[name="daterange2"]').on('focus', function (ev,picker) {
            if($(this).val()){
                $('.applyBtn').removeAttr("disabled",'');
            }else{
                $('.applyBtn').attr("disabled",'');
            }
         });
    }  
    if (type == "ageing_report" && table_id == "pending_beyond_tat_tbl") {
        $(".dataTables_filter").append(
            '&nbsp&nbsp&nbsp<label id="date_label3">Date Range3: <input type="text" name="daterange3" id="daterange3"  class ="form-control form-control-sm"  value="" /></label>'
        );
    
        // Apply the date range filter
        $('input[name="daterange3"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: "Clear",
            },
        });
        $('input[name="daterange3"]').on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
                
                table.draw();
            }
        );
            $('#daterange3').val('');
        $('input[name="daterange3"]').on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
                table.draw();
            }
        );
        $('input[name="daterange3"]').on('focus', function (ev,picker) {
            
            if($(this).val()){
                $('.applyBtn').removeAttr("disabled",'');
            }else{
                $('.applyBtn').attr("disabled",'');
            }
         });
    }   
    
    
}

// Add event listener for opening and closing details
$(".data-table tbody").on("click", "td.details-control", function () {
    var tr = $(this).closest("tr");
    var row = table.row(tr);
    var tableId = "posts-" + row.data().emp_id;
    var nester_tbl_u_id = row.data().emp_id;
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass("shown");
    } else {
        // Open this row
        row.child(template(row.data())).show();
        initTable(tableId, nester_tbl_u_id, row.data());
        tr.addClass("shown");
        tr.next().find("td").addClass("no-padding bg-#E9ECEF");
    }
});

function initTable(tableId, nester_tbl_u_id, data) {
    $.ajax({
        type: "POST",
        url: "employee_detail",
        data: { emp_id: nester_tbl_u_id },
        success: function (data) {
            var emp_details =
                "<tr><td>" +
                data[0].emp_id +
                "</td><td>" +
                data[0].emp_name +
                "</td><td>" +
                data[0].pan_no +
                "</td><td>" +
                moment(data[0].dob).format("DD-MM-YYYY") +
                "</td><td>" +
                data[0].mobileno +
                "</td><td>" +
                data[0].email +
                "</td></tr>";
            $("#posts-" + nester_tbl_u_id + " #inner_tbody").html(emp_details);
        },
    });
}

$("#f_f_tracker_view-tab").on("click", function () {
    var emp_id = $("#f_f_pop_emp_id").val();
    $.ajax({
        type: "POST",
        url: "get_f_f_tracker_files",
        data: { emp_id: emp_id, sg_id: 1 },
        success: function (data) {
            $(".sg_doc_view").html(data.tbody);
        },
    });
});



function fresh_tab_click() {
    if ($("#user_type").val() == "F_F_HR") {
        append_table2 = "fresh_c_p_tbl_check";
    } else {
        append_table2 = "fresh_c_p_tbl";
    }
    var emp_id = "";
    $("#tab_type2").val("Fresh");
    var emp_id = $(".pop_f_f_track_emp_id").val();
    get_ambassadors("Fresh", append_table2, emp_id);
}
function inprogress_tab_click2() {
    if ($("#user_type").val() == "F_F_HR") {
        append_table2 = "inprogress_c_p_tbl_check";
    } else {
        append_table2 = "inprogress_c_p_tbl";
    }
    $("#tab_type2").val("InProgress");
    var emp_id = "";
    get_ambassadors("InProgress", append_table2, emp_id);
}
function completed_tab_click2() {
    if ($("#user_type").val() == "F_F_HR") {
        append_table2 = "completed_c_p_tbl_check";
    } else {
        append_table2 = "completed_c_p_tbl";
    }
    $("#tab_type2").val("Completed");
    var emp_id = $(".pop_f_f_track_emp_id").val();
    get_ambassadors("Completed", append_table2, emp_id);
}
