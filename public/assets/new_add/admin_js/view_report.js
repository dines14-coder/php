$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    template = Handlebars.compile($("#details-template").html());
    var append_table = "completed_within_tat_tbl";
    get_data("Pending", append_table);

    $("#tab_type").val("Pending");
    $("#revert").attr("hidden", true);

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


function completed_within_tat() {
    append_table = "completed_within_tat_tbl";
    $("#tab_type").val("Pending");
    get_data("Pending", append_table);
}
function completed_beyond_tat() {
    append_table = "completed_beyond_tat_tbl";
    $("#tab_type").val("InProgress");
    get_data("InProgress", append_table);
}
function pending_beyond_tat() {
    append_table = "pending_beyond_tat_tbl";
    $("#tab_type").val("Completed");
    get_data("Completed", append_table);
}
function pending_within_tat() {
    append_table = "pending_within_tat_tbl";
    $("#tab_type").val("Completed");
    get_data("Completed", append_table);
}
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

function get_data(type, append_table) {
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
            {data: "received_date", name: "received_date" },
            {data: "completed_date", name: "completed_date" },
            {data: "received_date", name: "received_date" },
            {data: "completed_date", name: "completed_date" },
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
}

// Add event listener for opening and closing details
$(".data-table tbody").on("click", "td.details-control", function () {
    var tr = $(this).closest("tr");
    var row = table.row(tr);
    console.log(row.data());
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
