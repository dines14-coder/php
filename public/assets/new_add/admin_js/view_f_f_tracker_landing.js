$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    template = Handlebars.compile($("#details-template").html());
    var append_table = "pending_tbl";
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

jQuery(function ($) {
    $("#f_f_inp_show_pop").on("hidden.bs.modal", function (e) {
        if ($("#tab_type").val() == "Pending") {
            pending_tab_click();
        } else if ($("#tab_type").val() == "InProgress") {
            inprogress_tab_click();
        } else if ($("#tab_type").val() == "Completed") {
            completed_tab_click();
        } else if ($("#tab_type").val() == "Declined") {
            declined_tab_click();
        }
    });
});
 
function pending_tab_click() {
    append_table = "pending_tbl";
    $("#tab_type").val("Pending");
    get_data("Pending", append_table);
}
function inprogress_tab_click() {
    append_table = "inprogress_tbl";
    $("#tab_type").val("InProgress");
    get_data("InProgress", append_table);
}
function completed_tab_click() {
    append_table = "completed_tbl";
    $("#tab_type").val("Completed");
    get_data("Completed", append_table);
}
function declined_tab_click() {
    append_table = "declined_tbl";
    $("#tab_type").val("Declined");
    get_data("Declined", append_table);
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
        iDisplayLength: 20,
        drawCallback: function () {
            processInfo(this.api().page.info());
        },

        ajax: {
            url: "get_f_f_tracker_data",
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
            { data: "c_stage_gate", name: "c_stage_gate" },
            { data: "type_of_leaving", name: "type_of_leaving" },
            { data: "created_at", name: "created_at" },
            { data: "last_working_date", name: "last_working_date" },
            { data: "action", name: "action" },
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
    console.log(table);

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

function f_f_action_pop(
    emp_id,
    emp_name,
    process_s_g,
    lwd,
    seperation_date,
    $view
) {
    $("#f_f_tracker_inp_form").attr("disabled", true);
    $(".spinner-border").removeClass("d-none");
    $("#f_f_pop_submit").attr("disabled", true);
    $("#confirm_submit").attr("disabled", true);
    $("#myTab1").attr("disabled", true);

    $("#f_f_pop_emp_id").val(emp_id);
    $("#home1-tab").click();
    $("#confirm_submit").prop("checked", false);
    $(".error-text").text("");
    $(".red_border").css("border-color", "#e4e6fc");
    $("#c_c_s_g_").val(process_s_g);
    if (process_s_g == "3" || process_s_g == "4") {
        $(".sg6").attr("hidden", true);
        $(".sg7").attr("hidden", true);
    } else {
        $(".sg7").attr("hidden", false);
    }
    if (process_s_g > 2) {
        $("#history_tab").attr("hidden", false);
    } else {
        $("#history_tab").attr("hidden", true);
    }
    $("#show_er").html("");
    $(".hide_all").html("");
    $(".label_div").attr("hidden", true);
    $("#rec_val option:disabled").removeAttr("disabled");
    $("#rec_val option:disabled").css("background-color", "white");
    if (
        $("#user_type").val() == "Payroll_Finance" ||
        $("#user_type").val() == "Payroll_QC"
    ) {
        $("#home_tab").click();
    }
    $("#f_f_tracker_inp_form")[0].reset();

    if (
        $("#user_type").val() == "Payroll_HR" ||
        $("#user_type").val() == "Payroll_Finance"
    ) {
        var append_table2 = "fresh_c_p_tbl";
        get_ambassadors("Fresh", append_table2, emp_id);
        $("#tab_type2").val("Fresh");
    }

    $.ajax({
        type: "POST",
        url: "fetch_tracker_details",
        data: { emp_id: emp_id, process_s_g: process_s_g, lwd: lwd },
        success: function (data) {
            $("#f_f_tracker_inp_form").attr("disabled", false);
            $(".spinner-border").addClass("d-none");
            $("#f_f_pop_submit").attr("disabled", false);
            $("#confirm_submit").attr("disabled", false);
            $("#myTab1").attr("disabled", false);

            $("#last_working_date").val(lwd);
            $("#seperation_date").val(seperation_date);
            // Darwin Box Api  
            if (data.emp_data != "nodata" && data.res == "") {
                $("#grade_set").val(data.emp_data.band);
                $("#grade").val(data.emp_data.grade);
                $("#department").val(data.emp_data.department);
                $("#work_location").val(data.emp_data.location_type);
                $("#hrbp_name").val(data.emp_data.hrbp_name);
                $("#supervisor_name").val(data.emp_data.direct_manager_name);
                $("#reviewer_name").val(data.emp_data.reviewer);
                $("#headquarters").val(data.emp_data.city_type);
            }
            if (data.sal_data != "salnodata") {
                $("#basic").val(data.sal_data.basic);
                $("#da").val(data.sal_data.dearness_allowance);
                $("#other_allowance").val(data.sal_data.other_allowances);
                $("#hra").val(data.sal_data.hra);
                $("#addl_hra").val(data.sal_data.additional_hra);
                $("#conveyance").val(data.sal_data.conveyance_allowance);
                $("#lta").val(data.sal_data.lta);
                $("#medical").val(data.sal_data.medical_allowance);
                $("#spl_allowance").val(data.sal_data.special_allowance);
                $("#nps").val(data.sal_data.nps);
                $("#sales_incentive").val(data.sal_data.sales_incentive);
                $("#super_annuation").val(data.sal_data.superannuation_allowance);
                $("#super_annuation").val(data.sal_data.superannuation_allowance);
                $("#fva").val(data.sal_data.vehicle_allowance);
                $("#gross").val(data.sal_data.gross_salary);
                $("#date_of_joining").val(data.sal_data.joining_date);
            }
            if (data.res != "") {
                $("#sg1").click();
                $("#supervisor_clearance").val(data.res[0].supervisor_clearance);
                $("#commercial_admin_clearance").val(data.res[0].c_admin_clearance);
                $("#finanace_clearance").val(data.res[0].finanace_clearance);
                $("#it_clearance").val(data.res[0].it_clearance);
                if(data.res[0].re_open_ct != '' && data.res[0].re_open_ct != null){
                    $("#reopenstatus").val(data.res[0].re_open_ct);
                }

                $("#reviewer_name").val(data.res[0].reviewer_name);
                $("#headquarters").val(data.res[0].headquarters);
                $("#date_of_resignation").val(data.res[0].date_of_resignation);
                $("#grade_set").val(data.res[0].grade_set);
                $("#grade").val(data.res[0].grade);
                $("#department").val(data.res[0].department);
                $("#work_location").val(data.res[0].work_location);
                $("#hrbp_name").val(data.res[0].hrbp_name);
                $("#supervisor_name").val(data.res[0].supervisor_name);

                if (data.res[0].basic != "" && data.res[0].basic != null) {
                    $("#basic").val(data.res[0].basic);
                    $("#da").val(data.res[0].da);
                    $("#other_allowance").val(data.res[0].other_allowance);
                    $("#hra").val(data.res[0].hra);
                    $("#addl_hra").val(data.res[0].addl_hra);
                    $("#conveyance").val(data.res[0].conveyance);
                    $("#lta").val(data.res[0].lta);
                    $("#medical").val(data.res[0].medical);
                    $("#spl_allowance").val(data.res[0].spl_allowance);
                    $("#nps").val(data.res[0].nps);
                    $("#super_annuation").val(data.res[0].super_annuation);
                    $("#sales_incentive").val(data.res[0].sales_incentive);
                    $("#fva").val(data.res[0].fixed_vehicle_allowance);
                    $("#gross").val(data.res[0].gross);
                    $("#date_of_joining").val(data.res[0].date_of_joining);

                    $("#sap_doc_number").val(data.res[0].sap_doc_no);
                    $("#posting_date").val(data.res[0].posting_date);
                    $("#f_and_f_payable_recoverable").val(data.res[0].pay_rec);
                    $("#f_and_f_amount").val(data.res[0].ff_amount);
                    // $("#f_f_accounting_remark").val(data.res[0].date_of_joining);

                }

                $("#leave_balance_cl").val(data.res[0].leave_balance_cl);
                $("#leave_balance_pl").val(data.res[0].leave_balance_pl);
                $("#leave_balance_sl").val(data.res[0].leave_balance_sl);
                $("#is_probation_completed").val(data.res[0].is_probation_completed);

                $(".supervisor_clearance_view").html(moment(data.res[0].supervisor_clearance).format("DD-MM-YYYY"));
                $(".c_admin_clearance_view").html(moment(data.res[0].c_admin_clearance).format("DD-MM-YYYY"));
                $(".finanace_clearance_view").html(moment(data.res[0].finanace_clearance).format("DD-MM-YYYY"));
                $(".it_clearance_view").html(moment(data.res[0].it_clearance).format("DD-MM-YYYY"));
                $(".grade_set_view").html(data.res[0].grade_set);
                $(".grade_view").html(data.res[0].grade);
                $(".department_view").html(data.res[0].department);
                $(".work_location_view").html(data.res[0].work_location);
                $(".supervisor_name_view").html(data.res[0].supervisor_name);
                $(".reviewer_name_view").html(data.res[0].reviewer_name);
                $(".fixed_stipend").html(data.res[0].fixed_stipend);
                $(".headquarters_view").html(data.res[0].headquarters);
                $(".hrbp_name_view").html(data.res[0].hrbp_name);
                $(".seperation_date_view").html(moment(data.res[0].seperation_date).format("DD-MM-YYYY"));
                $(".date_of_resignation_view").html(moment(data.res[0].date_of_resignation).format("DD-MM-YYYY"));
                $(".last_working_date_view").html(moment(data.res[0].last_working_date).format("DD-MM-YYYY"));
                $(".date_of_joining_view").html(moment(data.res[0].date_of_joining).format("DD-MM-YYYY"));
                let array = [
                    "supervisor_clearance_view",
                    "c_admin_clearance_view",
                    "finanace_clearance_view",
                    "it_clearance_view",
                    "grade_set_view",
                    "grade_view",
                    "department_view",
                    "work_location_view",
                    "supervisor_name_view",
                    "reviewer_name_view",
                    "fixed_stipend",
                    "headquarters_view",
                    "hrbp_name_view",
                    "seperation_date_view",
                    "date_of_resignation_view",
                    "date_of_joining_view",
                    "last_working_date_view",
                ];

                for (let index = 0; index < array.length; index++) {
                    if (
                        $("." + array[index]).text() == "" ||
                        $("." + array[index]).text() == "Invalid date"
                    ) {
                        $("." + array[index]).html("---");
                    }
                }
                if (data.fetch == "" && process_s_g != "2") {
                    $(".hide_all").html("<b>No Data available.</b>");
                } else {
                    $(".hide_all").html(data.fetch);
                }
                $("#rec_val").html(data.rec);

                if ($("#rec_val > option:not(:disabled)").length == "20") {
                    $(".label_div").attr("hidden", true);
                } else {
                    $(".label_div").attr("hidden", false);
                }
                $("#rec_val").val(data.res[0].supervisor_name);

                $(".basic_view").html(data.res[0].basic);

                $(".da_view").html(data.res[0].da);
                $(".other_allowance_view").html(data.res[0].other_allowance);
                $(".hra_view").html(data.res[0].hra);
                $(".addl_hra_view").html(data.res[0].addl_hra);
                $(".conveyance_view").html(data.res[0].conveyance);
                $(".lta_view").html(data.res[0].lta);
                $(".medical_view").html(data.res[0].medical);
                $(".spl_allowance_view").html(data.res[0].spl_allowance);
                $(".nps_view").html(data.res[0].nps);
                $(".super_annuation_view").html(data.res[0].super_annuation);
                $(".sales_incentive_view").html(data.res[0].sales_incentive);
                $(".fva_view").html(data.res[0].fixed_vehicle_allowance);
                $(".gross_view").html(data.res[0].gross);

                $(".leave_balance_cl_view").html(data.res[0].leave_balance_cl);
                $(".leave_balance_pl_view").html(data.res[0].leave_balance_pl);
                $(".leave_balance_sl_view").html(data.res[0].leave_balance_sl);
                $(".is_probation_completed_view").html(
                    data.res[0].is_probation_completed
                );

                if ($(".basic_view").text() == "") {
                    $(".basic_view").html("---");
                }
                if ($(".da_view").text() == "") {
                    $(".da_view").html("---");
                }
                if ($(".other_allowance_view").text() == "") {
                    $(".other_allowance_view").html("---");
                }
                if ($(".hra_view").text() == "") {
                    $(".hra_view").html("---");
                }
                if ($(".addl_hra_view").text() == "") {
                    $(".addl_hra_view").html("---");
                }
                if ($(".conveyance_view").text() == "") {
                    $(".conveyance_view").html("---");
                }

                if ($(".lta_view").text() == "") {
                    $(".lta_view").html("---");
                }
                if ($(".medical_view").text() == "") {
                    $(".medical_view").html("---");
                }
                if ($(".spl_allowance_view").text() == "") {
                    $(".spl_allowance_view").html("---");
                }
                if ($(".nps_view").text() == "") {
                    $(".nps_view").html("---");
                }
                if ($(".super_annuation_view").text() == "") {
                    $(".super_annuation_view").html("---");
                }
                if ($(".sales_incentive_view").text() == "") {
                    $(".sales_incentive_view").html("---");
                }
                if ($(".fva_view").text() == "") {
                    $(".fva_view").html("---");
                }
                if ($(".gross_view").text() == "") {
                    $(".gross_view").html("---");
                }

                if ($(".leave_balance_cl_view").text() == "") {
                    $(".leave_balance_cl_view").html("---");
                }
                if ($(".leave_balance_pl_view").text() == "") {
                    $(".leave_balance_pl_view").html("---");
                }
                if ($(".leave_balance_sl_view").text() == "") {
                    $(".leave_balance_sl_view").html("---");
                }
                if ($(".is_probation_completed_view").text() == "") {
                    $(".is_probation_completed_view").html("---");
                }
            }
            var col = data.col;
            

            if (data.res2[0]) {
                if (
                    (data.res2[0][col] == "Fresh" && process_s_g == "3") ||
                    (data.res2[0][col] == "Fresh" && process_s_g == "5")
                ) {
                    $("#ff_sts").html("F&F Check Point is Pending");
                    $("#save_f_and_f_doc").attr("hidden", true);
                    $("#f_f_pop_submit").attr("hidden", true);
                    $("#save_f_and_f_doc2").attr("hidden", false);
                } else if (
                    (data.res2[0][col] == "InProgress" && process_s_g == "3") ||
                    (data.res2[0][col] == "InProgress" && process_s_g == "5")
                ) {
                    $("#ff_sts").html("F&F Check Point is In Progress");
                    $("#save_f_and_f_doc2").attr("hidden", true);
                    $("#f_f_pop_submit").attr("hidden", true);
                    $("#save_f_and_f_doc").attr("hidden", false);
                } else {
                    $("#save_f_and_f_doc2").attr("hidden", true);
                    $("#save_f_and_f_doc").attr("hidden", true);
                    $("#f_f_pop_submit").attr("hidden", false);
                    $("#ff_sts").html("");
                }
            }
            if (data.col == "hr_ld_c_p") {
                if (data.res2[0][col] == "Fresh") {
                    $("#ff_sts").html("F&F Check Point is Pending");
                    $("#save_f_and_f_doc").attr("hidden", true);
                    $("#f_f_pop_submit").attr("hidden", true);
                    $("#save_f_and_f_doc2").attr("hidden", false);
                } else if (data.res2[0][col] == "InProgress") {
                    $("#ff_sts").html("F&F Check Point is In Progress");
                    $("#save_f_and_f_doc2").attr("hidden", true);
                    $("#f_f_pop_submit").attr("hidden", true);
                    $("#save_f_and_f_doc").attr("hidden", false);
                } else {
                    $("#save_f_and_f_doc2").attr("hidden", true);
                    $("#save_f_and_f_doc").attr("hidden", true);
                    $("#f_f_pop_submit").attr("hidden", false);
                    $("#ff_sts").html("");
                }
            }
            var stg = process_s_g - 1;
            var html = "";
            if (stg == 0) {
                html +=
                    '<option value="" selected disabled>No Stage to revert</option>';
            } else {
                var depName = [
                    "",
                    "HR",
                    "Payroll HR",
                    "Payroll QC",
                    "Payroll Finance",
                    "Payroll Finance",
                    "HR",
                ];
                html += '<option value="">Choose</option>';
                for (var i = 1; i < stg; i++) {
                    html +=
                        '<option value="' +
                        i +
                        '">' +
                        i +
                        "->" +
                        depName[i] +
                        "</option>";
                }
            }
            $("#sgt_revert").html(html);
        },
    });

    $(".pop_f_f_track_emp_id").html(emp_id);
    $(".pop_f_f_track_emp_id").val(emp_id);

    $("#ff_emp_id").val(emp_id);

    $(".pop_f_f_track_emp_name").html(emp_name);
    $("#f_f_process_s_g").val(process_s_g);

    if (process_s_g == "2") {
        $(".pop_f_f_inp_div_set").css("display", "none");
        $("#s_g_2_field").css("display", "block");
        $("#f_f_pop_save").css("display", "block");
        // $("#f_f_pop_submit").css("display", "block");
        $("#f_f_pop_submit").css("display", "flex");
        $("#f_f_pop_submit").html(
            '<i id="load" class="fa fa-paper-plane"  style="margin-top:0.3rem"></i>&nbsp;Submit'
        );
        $("#home-tab1").click();
        $("#myTab1").attr("hidden", true);
        $("#mytab").hide();
        $("#myTabContent0").hide();
        $(".scrolly2").addClass("p-0");
        $(
            "#home1-tab,#home-tab2,#ctc_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2"
        ).click();
    } else {
        $("#f_f_pop_save").css("display", "none");
        // $("#f_f_pop_submit").css("display", "block");
        $("#f_f_pop_submit").css("display", "flex");
        $("#f_f_pop_submit").html(
            '<i id="load" class="fa fa-paper-plane" style="margin-top:0.3rem"></i>&nbsp;Submit'
        );
        $("#myTab1").attr("hidden", false);
        $(".scrolly2").removeClass("p-0");
    }
    if (process_s_g == "3") {
        $(".pop_f_f_inp_div_set").css("display", "none");
        $("#s_g_3_field").css("display", "block");
        $("#s_g_5_field").css("display", "none");
        $(".sg2").attr("hidden", true);
        $("#mytab").hide();
        // $("#myTabContent0").hide();
        $(
            "#home1-tab,#home-tab2,#ctc_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2"
        ).click();
    } else {
        $(".sg2").attr("hidden", false);
    }
    if (process_s_g == "4") {
        $(".pop_f_f_inp_div_set").css("display", "none");
        $("#s_g_4_field").css("display", "block");
        $(
            "#home1-tab,#ctc_tab,#ctc_master_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2,#home_tab"
        ).click();
    }
    if (process_s_g == "5") {
        $(".pop_f_f_inp_div_set").css("display", "none");
        $("#s_g_5_field").css("display", "block");
        $(".financeFirstTab").attr("hidden", true);
        $(
            "#home1-tab,#home-tab2,#ctc_tab,#ctc_master_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2,#home_tab"
        ).click();
    } else {
        $(".financeFirstTab").attr("hidden", false);
    }
    if (process_s_g == "6") {
        $(".pop_f_f_inp_div_set").css("display", "none");
        $("#s_g_6_field").css("display", "block");
        // $("#mytab").attr("hidden", true);
        $(".revert").removeClass("d-none");
        $('#profile1-tab').attr('hidden',true);
        // $(
        //     "#home1-tab,#ctc_tab,#ctc_master_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2"
        // ).click();
        $(
            "#home1-tab,#ctc_tab,#profiles2_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2"
        ).click();
    } else {
        if( process_s_g != "2" ){
            $("#mytab").attr("hidden", false);

        }
    }
    if (process_s_g == "7") {
        $(".pop_f_f_inp_div_set").css("display", "none");
        $("#historyTab").attr("hidden", true);

        var pdfPath = '../F_F_tracker/'+emp_id+'/manual_computation_file/pdf/'+emp_id+'.pdf';
        $.ajax({
            type: 'HEAD',
            url: pdfPath,
            success: function () {
                $('#convertedPdfShown').css('display', 'block');
                $('#f_f_statement_file').css('display', 'none');
                $('#pdfLink').attr('href', pdfPath);
                $('#f_f_statement_file').prop('required', false);
            },
            error: function () {
                $('#f_f_statement_file').css('display', 'block');
                $('#f_f_statement_file').prop('required', true);
                $('#convertedPdfShown').css('display', 'none');
            }
        });

        $("#s_g_7_field").css("display", "block");
        $(
            "#home1-tab,#ctc_tab,#ctc_master_tab,#sg_1_details,#revert_remark_tab,#ctcMasterTabViewStage2"
        ).click();
        $(".revert").addClass("d-none");
    }
    if ($view == "only_view") {
        $("#home1-tab").addClass("d-none");
        $('#qcheader').addClass("d-none");
        $("#qualityCheckTab").attr("hidden", false);
        $("#qualityCheckTab").text("Quality Check View");
        $("#profiles-tab").click();
        $("#myTabContent0").hide();
    }else{
        $('#qcheader').removeClass("d-none");
        $("#home1-tab").removeClass("d-none");

    }
    if($("#ff_sts").html() == "F&F Check Point is Pending"){
        $("#save_f_and_f_doc").attr("hidden", true);
        $("#f_f_pop_submit").attr("hidden", true);
        $("#save_f_and_f_doc2").attr("hidden", false);
    }
    
    $("#show_f_f_inp_pop_trigger").click();
}

function fresh_tab_click() {
    if ($("#user_type").val() == "F_F_HR") {
        append_table2 = "fresh_c_p_tbl_check";
    } else {
        append_table2 = "fresh_c_p_tbl";
    }
    var emp_id = "";
    $("#tab_type2").val("Fresh");
    var emp_id = $(".pop_f_f_track_emp_id").val();
    if ($("#user_type").val() != "Payroll_QC") {
        get_ambassadors("Fresh", append_table2, emp_id);
    }
}
function inprogress_tab_click2() {
    if ($("#user_type").val() == "F_F_HR") {
        append_table2 = "inprogress_c_p_tbl_check";
    } else {
        append_table2 = "inprogress_c_p_tbl";
    }
    $("#tab_type2").val("InProgress");
    // var emp_id = "";
    var emp_id = $(".pop_f_f_track_emp_id").val();
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

function get_ambassadors(type, append_table2, emp_id) {
    if (type == "Completed") {
        table = $("#" + append_table2).DataTable({
            bDestroy: true,
            bPaginate: false,
            searching: false,
            bInfo: false,
            bAutoWidth: false,
            ajax: {
                url: "get_c_p_datatable",
                type: "POST",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.end_date = $("#end_date").val();
                    d.type = type;
                    d.emp_id = emp_id;
                },
            },
            columns: [{ data: "questions", name: "questions" }],
        });
    }
    if (type == "InProgress") {
        table = $("#" + append_table2).DataTable({
            bDestroy: true,
            bPaginate: false,
            searching: false,
            bInfo: false,
            bAutoWidth: false,
            ajax: {
                url: "get_c_p_datatable",
                type: "POST",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.end_date = $("#end_date").val();
                    d.type = type;
                    d.emp_id = emp_id;
                },
            },
            columns: [{ data: "questions", name: "questions" }],
        });
    }
    if (type == "Fresh") {
        table = $("#" + append_table2).DataTable({
            bDestroy: true,
            bPaginate: false,
            searching: false,
            bInfo: false,
            bAutoWidth: false,
            ajax: {
                url: "get_c_p_datatable",
                type: "POST",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.end_date = $("#end_date").val();
                    d.type = type;
                    d.emp_id = emp_id;
                },
            },
            columns: [{ data: "questions", name: "questions" }],
        });
    }
}

$("#home_tab").click(function () {
    $("#revert").attr("hidden", true);
   
    if($("#ff_sts").html() == "F&amp;F Check Point is Pending"){
        $("#f_f_pop_submit").attr("hidden", true);
    }else{
        $("#f_f_pop_submit").attr("hidden", false);
    }

});

$("#contact_tab").click(function () {
    $("#show_er").html("");

    if($("#ff_sts").html() == "F&amp;F Check Point is Pending"){
        $("#f_f_pop_submit").attr("hidden", true);
    }else{
        $("#f_f_pop_submit").attr("hidden", false);
    }
    $("#revert").attr("hidden", false);
    $("#f_f_pop_submit").attr("hidden", true);
});

$("#f_f_pop_save").click(function () {
    $("#submit_type").val("Save");
    $("#real_submit").click();
});

$("#f_f_pop_submit").click(function () {
    $("#submit_type").val("Submit");
    $("#real_submit").click();
});

$(document).on("click", "#revert,#revert1", function () {
    if ($("#confirm_submit").is(":checked")) {
        $("#revert").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "save_revert",
            data: $("#f_f_tracker_inp_form").serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $(".error-text").text("");
                $(".red_border").css("border-color", "#e4e6fc");
            },
            success: function (data) {
                if (data.status == 0) {
                    $.each(data.error, function (i, val) {
                        $("." + i + "_error").text(
                            "The Revert field is required."
                        );
                        $("#" + i).css("border-color", "red");
                    });
                    $("#revert").attr("disabled", false);
                }
                if (data.res == "success") {
                    toastr.success("Reverted Successfully");
                    var explode = function () {
                        $(".close").click();
                        $("#home-tab").click();
                        $("#revert").attr("disabled", false);
                    };
                    setTimeout(explode, 3000);
                }
            },
            error: function (data) {
                toastr.error("Something went wrong!");
                $("#revert").attr("disabled", false);
            },
        });
    } else {
        toastr.error("Please Click Confirm.");
    }
});

// function save_f_f_entry(){
$("#f_f_tracker_inp_form").submit(function (e) {
    if ($("#confirm_submit").is(":checked")) {
        $("#f_f_tracker_inp_form").attr("disabled", true);
        $("#f_f_pop_submit").attr("disabled", true);

        $("#load").addClass("spinner-border spinner-border-sm");
        $("#load").removeClass("fa fa-paper-plane");
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "save_f_f_tracker_inp",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $(".error-text").text("");
                $(".red_border").css("border-color", "#e4e6fc");
            },
            success: function (data) {
                $("#f_f_tracker_inp_form").attr("disabled", false);
                if (data.status == 0) {
                    $("#f_f_pop_submit").attr("disabled", false);
                    $("#load").removeClass("spinner-border spinner-border-sm");
                    $("#load").addClass("fa fa-paper-plane");
                    toastr.error("Plese fill all required fields.");
                    $.each(data.error, function (i, val) {
                        console.log(i);
                        $("#" + i + "_error").text(val[0]);
                        $("#" + i).css("border-color", "red");
                    });
                }
                if (data.response == "success") {
                    toastr.success("Updated Successfully");
                    var c_tab = $("#tab_type").val();
                    if (c_tab == "Pending") {
                        pending_tab_click();
                    }
                    if (c_tab == "InProgress") {
                        inprogress_tab_click();
                    }
                    if (c_tab == "Completed") {
                        completed_tab_click();
                    }
                    if (c_tab == "Declined") {
                        declined_tab_click();
                    }

                    var explode = function () {
                        $(".close").click();
                        $("#f_f_pop_submit").attr("disabled", false);
                        $("#load").removeClass(
                            "spinner-border spinner-border-sm"
                        );
                        $("#load").addClass("fa fa-paper-plane");
                    };
                    setTimeout(explode, 3000);
                    $("#show_er").html("");
                }
                if (data.res == "rec_er") {
                    toastr.error(
                        "Please fill the Recovery value after click submit."
                    );
                    $("#f_f_pop_submit").attr("disabled", false);
                    $("#load").removeClass("spinner-border spinner-border-sm");
                    $("#load").addClass("fa fa-paper-plane");
                }
                if (data.res == "hold_empty") {
                    toastr.error(
                        "Please fill the hold Salary amount after click submit."
                    );
                    $("#f_f_pop_submit").attr("disabled", false);
                    $("#load").removeClass("spinner-border spinner-border-sm");
                    $("#load").addClass("fa fa-paper-plane");
                }
            },
        });
    } else {
        toastr.error("Please Click Confirm.");
    }
});

$(document).on("click", ".save_f_and_f_doc", function () {
    var emp_id = $(".pop_f_f_track_emp_id").val();
    save_ratings(emp_id);
});

function save_ratings(emp_id) {
    var emp_id = emp_id;
    var remarks = Array();
    var rating = Array();
    var Question_id = Array();
    var rating1 = Array();
    $(".rating_" + emp_id).each(function (i, v) {
        rating1[i] = $(this).val();
        rating[i] = $(this).closest("tr").find(":selected").val();
    });
    $(".Question_id_" + emp_id).each(function (i, v) {
        Question_id[i] = $(this).val();
    });
    $(".remarks_" + emp_id).each(function (i, v) {
        remarks[i] = $(this).val();
    });

    $.ajax({
        type: "POST",
        url: "add_f_and_f_document",
        data: {
            emp_id: emp_id,
            Question_id: Question_id,
            rating: rating,
            remarks: remarks,
        },
        dataType: "JSON",
        success: function (response) {
            var col = response.col;
            if (response.res2[0][col] == "InProgress") {
                $("#save_f_and_f_doc2").attr("hidden", true);
                $("#save_f_and_f_doc").attr("hidden", false);
            } else {
                $("#save_f_and_f_doc2").attr("hidden", true);
                $("#save_f_and_f_doc").attr("hidden", true);
                $("#f_f_pop_submit").attr("hidden", false);
                $("#ff_sts").html("");
            }

            if (response.response == "Success") {
                $("#save_f_and_f_doc").removeAttr("disabled");

                toastr.success("Updated successfully.");

                if ($("#tab_type2").val() == "Fresh") {
                    fresh_tab_click();
                }
                if ($("#tab_type2").val() == "InProgress") {
                    inprogress_tab_click2();
                }
                if ($("#tab_type2").val() == "Completed") {
                    completed_tab_click2();
                }
            } else {
                toastr.error("Failed..!");
            }
        },
        error: function () {
            toastr.error("Select field is required.");
        },
    });
}

function checkextension(file) {
    var ext = file.files[0].name.split(".").pop().toLowerCase();
    if (
        ext != "pdf" &&
        ext != "jpeg" &&
        ext != "doc" &&
        ext != "png" &&
        ext != "jpg" &&
        ext != "xls" &&
        ext != "xlsx" &&
        ext != "csv"
    ) {
        toastr.error("Sorry, this file format is not supported.");
        $("#" + file.name.replace("[]", "")).val("");
        return false;
    }
}

$(".close_rev").on("click", function () {
    $("#f_f_inp_show_pop").fadeIn("slow");
});

$("#add_rec").click(function () {
    var r_id = $("#rec_val").val();
    $.ajax({
        type: "POST",
        url: "get_recovery_val",
        data: { r_id: r_id },
        dataType: "JSON",
        success: function (data) {
            $(".label_div").attr("hidden", false);
            $(".hide_all").append(
                '<div class="row "><div class="col-md-4 show_row">' +
                    data.res +
                    '</div><div class="col-md-3"><input type="text" name="value[]" class="form-control "></div><div class="col-md-4"><textarea type="text" id="question" name="remark[]" class="form-control mb-3" placeholder="Enter Remark"></textarea></div><div class="col-md-1"><button value="' +
                    r_id +
                    '" class="btn btn-outline-danger remove_field" title="Remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>'
            );
            $("#rec_val")
                .children("option[value=" + r_id + "]")
                .attr("disabled", true);
            $("#rec_val")
                .children("option[value=" + r_id + "]")
                .css("background-color", "#E9ECEF");
            $("#rec_val").val("");
        },
        error: function (e) {
            toastr.error("Please Select a recovery after click add button.");
        },
    });
});

$(".hide_all").on("click", ".remove_field", function () {
    var del_id = $(this).val();
    var emp_id = $("#f_f_pop_emp_id").val();
    if ($(this).val().length == "5") {
        $(this).parent("div").parent("div").remove();
        $("#rec_val")
            .children("option[value=" + $(this).val() + "]")
            .attr("disabled", false);
        $("#rec_val")
            .children("option[value=" + $(this).val() + "]")
            .css("background-color", "white");
        if ($("#tab_type").val() == "InProgress") {
            if ($("#rec_val > option:not(:disabled)").length == "20") {
                $(".label_div").attr("hidden", true);
            } else {
                $(".label_div").attr("hidden", false);
            }
        } else {
            if ($("#rec_val > option:not(:disabled)").length == "21") {
                $(".label_div").attr("hidden", true);
            } else {
                $(".label_div").attr("hidden", false);
            }
        }
    } else {
        $.ajax({
            type: "POST",
            url: "delete_recoveries",
            data: { del_id: del_id, emp_id: emp_id },
            dataType: "JSON",
            success: function (data) {
                if (data.res == "success") {
                    $(".del_close").click();
                    $("#rec_val")
                        .children("option[value=" + data.r_id + "]")
                        .attr("disabled", false);
                    $("#rec_val")
                        .children("option[value=" + data.r_id + "]")
                        .css("background-color", "#E9ECEF");
                }
            },
        });
    }
});

$("#net_pay_f").on("keyup", function () {
    $("#e_total").html($(this).val());
    $("#f_total").html($("#e_total").text() - $("#d_total").text());
});

$(".netpay_date input").datepicker({
    format: "MM, yyyy",
    autoclose: true,
    viewMode: "months",
    minViewMode: "months",
});

$("#add_hold").click(function () {
    var date = $("#month_year").val();
    $.ajax({
        type: "POST",
        url: "check_already_exist_netpay",
        data: $("#f_f_tracker_inp_form").serialize(),
        success: function (data) {
            if (data.res == "success") {
                $(".hs_title").attr("hidden", false);
                $(".show_hold").append(
                    '<div class="row mt-3"><div class="col-md-5 netpay_date"  style="background: #fff; cursor: pointer;"><input type="text" name="month_year_[]" class="form-control month_yr" readonly  value="' +
                        date +
                        '"></div><div class="col-md-5"><input type="text" name="n_amount[]" onkeypress="javascript: return isNumber(event)"  class="form-control"></div><div class="col-md-1"><button class="btn btn-outline-danger remove_field1" title="Remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div>'
                );
            } else {
                toastr.error(data.my + " Already added.");
            }
        },
    });
});

let isNumber = (evt) => {
    let iKeyCode = evt.which ? evt.which : evt.keyCode;
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;

    return true;
};

$(".show_hold").on("click", ".remove_field1", function () {
    $(this).parent("div").parent("div").remove();
    if ($(".month_yr").val()) {
        $(".hs_title").attr("hidden", false);
    } else {
        $(".hs_title").attr("hidden", true);
    }
});

$(".hold_salary_tab").on("click", function () {
    var emp_id = $("#f_f_pop_emp_id").val();
    $.ajax({
        type: "POST",
        url: "get_hold_salary",
        data: { emp_id: emp_id },
        dataType: "JSON",
        success: function (data) {
            $(".show_hold_salary").html(data.hold_sal);
        },
    });
});

function f_and_f_document_popup(emp_id) {
    $("#hidden_pop_emp_id").val(emp_id);
    $.ajax({
        type: "POST",
        url: "f_and_f_document_popup",
        data: { emp_id: emp_id },
        dataType: "JSON",
        success: function (response) {
            if (response.response == "Success") {
                $("#data").html(response.data);
                $("#f_and_f_document_popup1").click();
            }
        },
    });
}

$("#download").click(function () {
    var emp_id = $("#hidden_pop_emp_id").val();
    $.ajax({
        type: "POST",
        url: "downloadPDF",
        data: { emp_id: emp_id },
        success: function (response) {
            var host_url = response;
            window.open(host_url, "_blank").focus();
        },
    });
});

$("#date_of_joining").change(function () {
    var date_of_resignation = $(this).val();
    $("#date_of_resignation").attr("min", date_of_resignation);
});

$(".avoid_special_char").on("input", function () {
    $(this).val(
        $(this)
            .val()
            .replace(/[^a-z0-9]/gi, "")
    );
});

// Get Revert Remark
$(document).on("click", "#revert_remark_tab,#histories", function () {
    get_revert_remark("#revert_remark_table");
    get_revert_remark("#revert_remark_table1");
});

function get_revert_remark(tbl) {
    var emp_id = $("#f_f_pop_emp_id").val();
    var table = $(tbl).DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getRevertRemark",
            type: "POST",
            data: { emp_id: emp_id },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "from_sg", name: "from_sg" },
            { data: "to_sg", name: "to_sg" },
            { data: "remark", name: "remark" },
            { data: "department", name: "department" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
}

// Get CTC Master
$(document).on("click", "#ctcMasterTabViewStage2,#stage_2_prhr", function () {
    ctcMasterHistory("#ctcMasterTable");
});

function ctcMasterHistory(tbl) {
    var emp_id = $("#f_f_pop_emp_id").val();
    $(tbl).DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getCTCMasterData",
            type: "POST",
            data: { emp_id: emp_id },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "basic", name: "basic" },
            { data: "da", name: "da" },
            { data: "other_allowance", name: "other_allowance" },
            { data: "hra", name: "hra" },
            { data: "addl_hra", name: "addl_hra" },
            { data: "conveyance", name: "conveyance" },
            { data: "lta", name: "lta" },
            { data: "medical", name: "medical" },
            { data: "spl_allowance", name: "spl_allowance" },
            { data: "nps", name: "nps" },
            { data: "super_annuation", name: "super_annuation" },
            { data: "sales_incentive", name: "sales_incentive" },
            {
                data: "fixed_vehicle_allowance",
                name: "fixed_vehicle_allowance",
            },
            { data: "gross", name: "gross" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
}

// Get Hold Salary
$(document).on("click", "#holdSalaryTabViewStage2", function () {
    holdSalaryHistory("#holdSalaryTable");
});

function holdSalaryHistory(tbl) {
    var emp_id = $("#f_f_pop_emp_id").val();
    $(tbl).DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getHoldSalaryData",
            type: "POST",
            data: { emp_id: emp_id },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "month_year", name: "month_year" },
            { data: "amount", name: "amount" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
}
// Get Exit Register Run Files
$(document).on("click", "#exitRegisterRunTabViewStage2", function () {
    getTrackerFiles("#exitRegisterHistory", "history", [
        "exit_register_run",
        "manual_computation",
    ]);
});
// Get Exit Register Run Files History
$(document).on("click", "#exitRegViewTab,#profiles1-tab", function () {
    getTrackerFiles("#exitRegisterView", "view", [
        "exit_register_run",
        "manual_computation",
    ]);
});
// Get Quality Check Files History
$(document).on("click", "#stage_3_qc", function () {
    getTrackerFiles("#qualityCheckHistory", "history", ["quality_check"]);
});
// Get Quality Check Files
$(document).on("click", "#qualityCheckTab", function () {
    getTrackerFiles("#qualityCheckView", "view", ["quality_check"]);
});
// Get Finance Files(Payout)
$(document).on("click", "#stage5Finance", function () {
    getTrackerFilesFinance("#financeView2", "view", ["payout_complete"]);
});
// Get Tracker Files
function getTrackerFiles(tbl, type, doc_type) {
    var emp_id = $("#f_f_pop_emp_id").val();
    $(tbl).DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getTrackerRegisterRunFiles",
            type: "POST",
            data: { emp_id: emp_id, type: type, doc_type: doc_type },
        },
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "document", name: "document" },
            { data: "remark", name: "remark" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
}

function getTrackerFilesFinance(tbl, type, doc_type) {
    var emp_id = $("#f_f_pop_emp_id").val();
    $(tbl).DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getTrackerRegisterRunFilesFinance",
            type: "POST",
            data: { emp_id: emp_id, type: type, doc_type: doc_type },
        },
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "document", name: "document" },
            { data: "amount", name: "amount" },
            { data: "remark", name: "remark" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
}

// Get Hold Salary
$(document).on("click", "#hold_tab", function () {
    var emp_id = $("#f_f_pop_emp_id").val();
    $.ajax({
        type: "POST",
        url: "getHoldSalaryDataEdit",
        data: { emp_id: emp_id },
        dataType: "JSON",
        success: function (data) {
            if (data.checkRevertOrNot == "yes") {
                $(".show_hold").html(data.hold_salary);
            }
        },
    });
});

// Delete Hold Salary
function deleteHoldSalary(id) {
    $.ajax({
        type: "POST",
        url: "deleteHoldSalary",
        data: { id: id },
        dataType: "JSON",
        success: function (data) {
            if (data.res == "success") {
                $("#hold_tab").click();
            }
        },
    });
}

// Get Finance Details
$(document).on("click", "#stage4Finance", function () {
    var table = $("#financeView").DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getFinanceDetails",
            type: "POST",
            data: { emp_id: $("#f_f_pop_emp_id").val() },
        },
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "sap_doc_no", name: "sap_doc_no" },
            { data: "posting_date", name: "posting_date" },
            { data: "pay_rec", name: "pay_rec" },
            { data: "ff_amount", name: "ff_amount" },
            { data: "document", name: "document" },
            { data: "remark", name: "remark" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
});

// Get Leave Balance History
$(document).on("click", "#leave_balance_history_tab", function () {
    var table = $("#leaveBalanceHistory").DataTable({
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
        ],
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        bDestroy: true,
        bAutoWidth: false,
        iDisplayLength: 5,
        bLengthChange: false,
        ajax: {
            url: "getLeaveBalanceHistory",
            type: "POST",
            data: { emp_id: $("#f_f_pop_emp_id").val() },
        },
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "leave_balance_cl", name: "leave_balance_cl" },
            { data: "leave_balance_sl", name: "leave_balance_sl" },
            { data: "leave_balance_pl", name: "leave_balance_pl" },
            { data: "is_probation_completed", name: "is_probation_completed" },
            { data: "created_at", name: "created_at" },
        ],
        order: [[0, "desc"]],
    });
});

$(document).on("click", "#home1-tab", function () {
    checkChecklistStatus();
});

function checkChecklistStatus() {
    var emp_id = $("#f_f_pop_emp_id").val();
    $.ajax({
        type: "POST",
        url: "checkChecklistStatus",
        data: { emp_id: emp_id },
        dataType: "JSON",
        success: function (data) {
            if (data.status == "success") {
                if (
                    $("#user_type").val() == "Payroll_Finance" &&
                    $("#c_c_s_g_").val() == 5
                ) {
                    if (
                        data.getExitChecklistData[0].cl_c_p == "Fresh" ||
                        data.getExitChecklistData[0].fn_c_p == "Fresh" ||
                        data.getExitChecklistData[0].hr_ld_c_p == "Fresh" ||
                        data.getExitChecklistData[0].it_c_p == "Fresh" ||
                        data.getExitChecklistData[0].it_inf_c_p == "Fresh" ||
                        data.getExitChecklistData[0].cl_c_p == "InProgress" ||
                        data.getExitChecklistData[0].fn_c_p == "InProgress" ||
                        data.getExitChecklistData[0].hr_ld_c_p ==
                            "InProgress" ||
                        data.getExitChecklistData[0].it_c_p == "InProgress" 
                        // ||
                        // data.getExitChecklistData[0].it_inf_c_p == "InProgress"
                    ) {
                        $("#heading_dep").html(
                            "These are the pending departments for this employee. Kindly complete the pending departments to proceed further."
                        );
                        $("#show_pending_dep").attr("hidden", false);
                        $("#f_f_pop_submit").attr("disabled", true);
                    } else {
                        $("#f_f_pop_submit").attr("disabled", false);
                        $("#show_pending_dep").attr("hidden", true);
                    }
                } else {
                    $(".checkpointPending").html("");
                }

                var colArray = [
                    "cl_c_p",
                    "fn_c_p",
                    "hr_ld_c_p",
                    // "it_c_p",
                    // "it_inf_c_p",
                ];
                var depName = [
                    "Claims",
                    "Payroll Finance",
                    "HR Lead",
                    // "IT",
                    // "IT Infra",
                ];
                console.log(colArray);
                console.log(depName);
                console.log(data.getExitChecklistData[0]);

                for (var i = 0; i < colArray.length; i++) {
                    if (data.getExitChecklistData[0][colArray[i]] == "Fresh") {
                        $("#q_dep_" + i).html(depName[i]);
                    } else if (
                        data.getExitChecklistData[0][colArray[i]] ==
                        "InProgress"
                    ) {
                        $("#q_dep_" + i).html(depName[i]);
                    } else {
                        $("#q_dep_" + i).html("");
                    }
                }
            }
        },
    });
}
if (
    $("#user_type").val() == "F_F_HR" ||
    $("#user_type").val() == "Payroll_HR"
) {
    $("#profile2-tab,#history_tab1").on("click", function () {
        $("#check_tab1").click();
        $("#footer_id").hide();
    });
    $(".show_click").on("click", function () {
        $("#footer_id").show();
    });
    function swap() {
        var process_SSF = $("#f_f_process_s_g").val();
        if (process_SSF != "7" && process_SSF != "") {
            $("#s_g_3_field").hide();
            $("#myTabContent0").show();
            $("#show_er").html("");
            $("#revert").attr("hidden", false);
            $("#f_f_pop_submit").attr("hidden", true);
        }
    }
    $("#home1-tab").on("click", function () {
        var process_SSF = $("#f_f_process_s_g").val();
        if (process_SSF != "7" && process_SSF != "") {
            $("#s_g_3_field").show();
            $("#myTabContent0").show();
            $("#mytab").show();
            $("#revert").attr("hidden", true);
            // $("#f_f_pop_submit").attr("hidden", false);
        }
        if(process_SSF == "2"){
            $("#s_g_3_field").hide();
            $("#mytab").hide();
            
        }
    });
}
if ($("#user_type").val() == "Payroll_Finance") {
    function swap() {
        // $("#s_g_6_field").hide();
        $("#myTabContent0").show();
        $("#show_er").html("");
        $("#revert").attr("hidden", false);
        $("#f_f_pop_submit").attr("hidden", true);
    }
    $("#home1-tab").on("click", function () {

       if($("#ff_sts").html() == "F&amp;F Check Point is Pending"){
            $("#f_f_pop_submit").attr("hidden", true);
        }else{
            $("#f_f_pop_submit").attr("hidden", false);
        }
        $("#myTabContent0").show();
        // $("#mytab").hide();
        $("#revert").attr("hidden", true);
        $("#f_f_pop_submit").attr("hidden", false);
    });
}


function reopen(status){
    // $("#s_g_5_field").show();
    $('#s_g_7_field').hide();
    $('#re_opened_by').attr('disabled',false);
    $("#reopenstatus").val(status);
    // $("#myTabContent0 #forward_tab").hide();

}
