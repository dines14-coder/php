$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    template = Handlebars.compile($("#details-template").html());
    var tab_type = $("#tab_type").val();
    get_data(tab_type);
});

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

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
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(
                    self,
                    e,
                    dt,
                    button,
                    config
                );
            } else if (button[0].className.indexOf("buttons-excel") >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.excelHtml5.action.call(
                          self,
                          e,
                          dt,
                          button,
                          config
                      )
                    : $.fn.dataTable.ext.buttons.excelFlash.action.call(
                          self,
                          e,
                          dt,
                          button,
                          config
                      );
            } else if (button[0].className.indexOf("buttons-csv") >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.csvHtml5.action.call(
                          self,
                          e,
                          dt,
                          button,
                          config
                      )
                    : $.fn.dataTable.ext.buttons.csvFlash.action.call(
                          self,
                          e,
                          dt,
                          button,
                          config
                      );
            } else if (button[0].className.indexOf("buttons-pdf") >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.pdfHtml5.action.call(
                          self,
                          e,
                          dt,
                          button,
                          config
                      )
                    : $.fn.dataTable.ext.buttons.pdfFlash.action.call(
                          self,
                          e,
                          dt,
                          button,
                          config
                      );
            } else if (button[0].className.indexOf("buttons-print") >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one("preXhr", function (e, s, data) {
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

// Get Pending Data
function pending_tab_click() {
    $("#tab_type").val("pending");
    $("#reset_btn").trigger("click");
}

function ffdetail_tab_click() {
    $("#tab_type").val("ffdetail");
    $("#reset_btn").trigger("click");
}

// Get In Progress Data
function inprogress_tab_click() {
    $("#tab_type").val("in_progress");
    $("#reset_btn").trigger("click");
}
// F&F Completed
function completed_tab_click() {
    $("#tab_type").val("f_f_completed");
    $("#reset_btn").trigger("click");
}
// Relieving & Service Completed
function rel_and_service_tab_click() {
    $("#tab_type").val("relieving_service");
    $("#reset_btn").trigger("click");
}
// Payroll Query Completed
function queries_solved_click() {
    payroll_related_tab();
}

// Payroll Query Completed
function payroll_related_tab() {
    $("#tab_type").val("payroll");
    $("#reset_btn").trigger("click");
}
// HRSS Query Completed
function hrss_related_tab() {
    $("#tab_type").val("hrss");
    $("#reset_btn").trigger("click");
}
// Unresolved Queries
function unresolved_tab_click() {
    $("#tab_type").val("unresolved");
    $("#reset_btn").trigger("click");
}

function get_details_data(type) {
    // alert(1)
    table = $("#" + type + "_tbl").DataTable({
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
        iDisplayLength: 20,
        drawCallback: function (settings) {
            var recordsTotal = settings.json.recordsFiltered;
            $(".total_res_show_pending").text(recordsTotal);   
        },

        ajax: {   
            url: "get_fftrack_details",
            type: "GET",
            data: function (d) {
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.type = type;
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
            { data: "supervisor_clearance", name: "supervisor_clearance" },
            { data: "c_admin_clearance", name: "c_admin_clearance" },
            { data: "finanace_clearance", name: "finanace_clearance" },
            { data: "it_clearance",name: "it_clearance"},
            { data: "grade_set", name: "grade_set" },
            { data: "grade", name: "grade" },
            { data: "department", name: "department" },
            { data: "work_location", name: "work_location" },
            { data: "supervisor_name", name: "supervisor_name" },
            { data: "reviewer_name", name: "reviewer_name" },
            { data: "headquarters", name: "headquarters" },
            { data: "hrbp_name", name: "hrbp_name" },
            { data: "last_working_date", name: "last_working_date" },
            { data: "seperation_date", name: "seperation_date" },
            { data: "date_of_joining", name: "date_of_joining" },
            { data: "date_of_resignation", name: "date_of_resignation" }
        ],
    });

    if (type == "f_f_completed" || type == "relieving_service") {
        table.column(6).visible(false);
    }
    if (type != "relieving_service") {
        table.column(8).visible(false);
    }

    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
}

function get_data(type) {
    table = $("#" + type + "_tbl").DataTable({
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
        iDisplayLength: 20,
        drawCallback: function () {
            processInfo(this.api().page.info());
        },

        ajax: {
            url: "get_pending_f_f_data",
            type: "POST",
            data: function (d) {
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.type = type;
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
            { data: "type_of_leaving", name: "type_of_leaving" },
            { data: "last_working_date", name: "last_working_date" },
            { data: "stagegate", name: "stagegate" },
            { data: "created_at", name: "created_at" },
            { data: "created_at1", name: "created_at1" },
        ],
    });
    if (type == "f_f_completed" || type == "relieving_service") {
        table.column(6).visible(false);
    }
    if (type != "relieving_service") {
        table.column(8).visible(false);
    }

    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
}

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

function processInfo(info) {
    var res_found = info.recordsDisplay;
    var tab_type = $("#tab_type").val();
    $(".total_res_show_" + tab_type).text(res_found);
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

// Reset Filter
$(document).on("click", "#reset_btn", function () {
    $("#start_date").val("");
    $("#end_date").val("");
    var tab_type = $("#tab_type").val();
    if (
        tab_type == "payroll" ||
        tab_type == "hrss" ||
        tab_type == "unresolved"
    ) {
        get_query_report(tab_type);
    } else if (tab_type == "in_progress") {
        f_f_transaction_report(tab_type);
    }else if(tab_type == "ffdetail") {
        get_details_data(tab_type);
    }else {
        get_data(tab_type);
    }
});

// Filter Data
$(document).on("click", "#filter_btn", function () {
    var tab_type = $("#tab_type").val();
    if (
        tab_type == "payroll" ||
        tab_type == "hrss" ||
        tab_type == "unresolved"
    ) {
        get_query_report(tab_type);
    } else if (tab_type == "in_progress") {
        f_f_transaction_report(tab_type);
    }else if(tab_type=="ffdetail") {
        get_details_data(tab_type);
    }
    else {
        get_data(tab_type);
       
    }
});

// Get Query Details
function get_query_report(type) {
    table = $("#" + type + "_tbl").DataTable({
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
        iDisplayLength: 20,
        drawCallback: function () {
            processInfo(this.api().page.info());
        },

        ajax: {
            url: "get_query_report",
            type: "POST",
            data: function (d) {
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.type = type;
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
            { data: "ticket_id", name: "ticket_id" },
            { data: "emp_id", name: "emp_id" },
            { data: "emp_name", name: "emp_name" },
            { data: "type_of_leaving", name: "type_of_leaving" },
            { data: "last_working_date", name: "last_working_date" },
            { data: "document", name: "document" },
            { data: "qry_created_at", name: "qry_created_at" },
            { data: "updated_at", name: "updated_at" },
        ],
    });
    if (type == "unresolved") {
        table.column(9).visible(false);
    }
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
}

function f_f_transaction_report(type) {
    table = $("#" + type + "_tbl").DataTable({
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
        iDisplayLength: 20,
        drawCallback: function () {
            processInfo(this.api().page.info());
        },

        ajax: {
            url: "f_f_transaction_report",
            type: "POST",
            data: function (d) {
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.type = type;
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
            { data: "sg1", name: "sg1" },
            { data: "sg1_", name: "sg1_" },
            { data: "sg2", name: "sg2" },
            { data: "sg2_", name: "sg2_" },
            { data: "sg3", name: "sg3" },
            { data: "sg3_", name: "sg3_" },
            { data: "sg4", name: "sg4" },
            // { data: "sg4_", name: "sg4_" },
            // { data: "sg5", name: "sg5" },
            { data: "sg5_", name: "sg5_" },
            { data: "sg6", name: "sg6" },
            { data: "sg6_", name: "sg6_" },
        ],
    });
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
}

$("#in_progress_tbl").on("xhr.dt", function () {
    $("#in_progress_tbl thead th:eq(5)").width("600");
});
