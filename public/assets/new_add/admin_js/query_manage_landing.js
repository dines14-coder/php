$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    template = Handlebars.compile($("#details-template").html());
    var append_table = "pending_query_tbl";
    get_query("Pending", append_table);
});

function checkextension(file) {
    // var acceptedFiles = [".jpg", ".jpeg", ".png", ".pdf", ".doc"];
    var ext = file.files[0].name.split(".").pop().toLowerCase();
    if (
        ext != "pdf" &&
        ext != "jpeg" &&
        ext != "doc" &&
        ext != "png" &&
        ext != "jpg"
    ) {
        toastr.error("Sorry, this file format is not supported.");
        $("#" + file.name.replace("[]", "")).val("");
        return false;
    }
    
    // Clear validation error when a valid file is selected
    var fieldId = file.id;
    $("#" + fieldId).removeClass("is-invalid");
    $("#" + fieldId).next(".file-error").remove();
}

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

function pending_tab_click() {
    append_table = "pending_query_tbl";
    get_query("Pending", append_table);
}
function inprogress_tab_click() {
    append_table = "inprogress_query_tbl";
    get_query("Approved", append_table);
}
function completed_tab_click() {
    append_table = "completed_query_tbl";
    get_query("Completed", append_table);
}
function declined_tab_click() {
    append_table = "declined_query_tbl";
    get_query("Declined", append_table);
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

function get_query(type, append_table) {
    if (type == "Completed") {
        table = $("#" + append_table).DataTable({
            lengthMenu: [
                [50, 100, 200, 300, 400, 500, 1000, -1],
                [50, 100, 200, 300, 400, 500, 1000, "All"],
            ],
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
                // {
                //     "extend": 'colvis',
                //     "text": '<i class="fa fa-eye" ></i>  Colvis',
                //     "titleAttr": 'Colvis',
                //     "action": newexportaction
                // },
            ],
            dom: "Bfrtip",
            processing: true,
            serverSide: true,
            bDestroy: true,
            bAutoWidth: false,
            scrollX: true,
            iDisplayLength: 50,
            drawCallback: function () {
                processInfo(this.api().page.info());
            },

            ajax: {
                url: get_admin_query_datatable,
                type: "POST",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.end_date = $("#end_date").val();
                    d.type = type;
                },
            },
            // ajax: "{{ route('users.index') }}",

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
                { data: "ticket_id", name: "qt.ticket_id" },
                { data: "emp_id", name: "qt.emp_id" },
                { data: "document_div", name: "document_div" },
                { data: "remark", name: "qt.remark" },
                { data: "type_of_leaving", name: "type_of_leaving" },
                { data: "created_at", name: "qt.created_at" },
                // {data: 'status', name: 'status'},
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
    }
    if (type == "Pending" || type == "Approved" || type == "Declined") {
        table = $("#" + append_table).DataTable({
            lengthMenu: [
                [50, 100, 200, 300, 400, 500, 1000, -1],
                [50, 100, 200, 300, 400, 500, 1000, "All"],
            ],
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
                // {
                //     "extend": 'colvis',
                //     "text": '<i class="fa fa-eye" ></i>  Colvis',
                //     "titleAttr": 'Colvis',
                //     // "action": newexportaction
                // },
            ],
            dom: "Bfrtip",
            processing: true,
            serverSide: true,
            bDestroy: true,
            bAutoWidth: false,
            scrollX: true,
            iDisplayLength: 50,
            drawCallback: function () {
                processInfo(this.api().page.info());
            },

            ajax: {
                url: get_admin_query_datatable,
                type: "POST",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.end_date = $("#end_date").val();
                    d.type = type;
                },
            },
            // ajax: "{{ route('users.index') }}",

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
                { data: "ticket_id", name: "qt.ticket_id" },
                { data: "emp_id", name: "qt.emp_id" },
                { data: "document_div", name: "document_div" },
                { data: "remark", name: "qt.remark" },
                { data: "type_of_leaving", name: "type_of_leaving" },
                { data: "created_at", name: "qt.created_at" },
                // {data: 'status', name: 'status'},
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

        // if($("#user_type").val() == "HR-LEAD"){
        //     table.column(8).visible( false );
        // }
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
        tr.next().find("td").addClass("no-padding bg-gray");
    }
});

function initTable(tableId, nester_tbl_u_id, data) {
    $.ajax({
        type: "POST",
        url: employee_detail,
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
                data[0].dob +
                "</td><td>" +
                data[0].mobileno +
                "</td><td>" +
                data[0].email +
                "</td></tr>";
            $("#posts-" + nester_tbl_u_id + " #inner_tbody").html(emp_details);
        },
    });
}

function update_query_click_dec_tab(ticket_id, type) {
    if (type == "Declined") {
        $("#dec_rem").css("display", "block");
    } else {
        $("#dec_rem").css("display", "none");
    }
    // pop open
    $("#exampleModalLabel").html("Are you sure want to Approved this Query?");
    $("#confirm_pop_submit").unbind("click");

    $("#confirm_pop_trigger").click();
    $("#confirm_pop_submit").click(function (e) {
        e.preventDefault();

        dec_remark = $("#dec_remark").val();
        $("#confirm_pop_submit").attr("disabled", "true");
        $("#confirm_pop_submit").addClass("btn-progress");

        $.ajax({
            type: "POST",
            url: update_query,
            data: { ticket_id: ticket_id, type: type, dec_remark: dec_remark },
            beforeSend: function () {
                $(".error-text").text("");
                $(".red_border").css("border-color", "#e4e6fc");
            },
            success: function (data) {
                $("#resp").css("display", "block");
                $("#resp").html('<b style="color:green;">Updated</b></b>');
                $("#resp").delay(3000).fadeOut(500);

                var explode = function () {
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
                    data: { ticket_id: data.ticket_id },
                    success: function (data) {
                        if (data.response == "success") {
                        }
                    },
                });
                // send mail end
            },
        });
    });
}

function update_query_click(ticket_id, type, doc_id) {
    // pop open

    $("#dec_remark").val("");

    $(".error-text").text("");
    $(".red_border").css("border-color", "#e4e6fc");

    if (type == "Declined") {
        $("#dec_rem").css("display", "block");
        $("#exampleModalLabel").html(
            "Are you sure want to Declined this Query?"
        );
    } else {
        $("#dec_rem").css("display", "none");
        $("#exampleModalLabel").html(
            "Are you sure want to Approved this Query?"
        );
    }

    $("#confirm_pop_submit").unbind("click");

    $("#confirm_pop_trigger").click();
    $("#confirm_pop_submit").click(function (e) {
        e.preventDefault();

        dec_remark = $("#dec_remark").val();

        $("#confirm_pop_submit").html("Loading..");
        $("#confirm_pop_submit").attr("disabled", "true");

        $.ajax({
            type: "POST",
            url: update_query,
            data: {
                ticket_id: ticket_id,
                type: type,
                doc_id: doc_id,
                dec_remark: dec_remark,
            },
            beforeSend: function () {
                $(".error-text").text("");
                $(".red_border").css("border-color", "#e4e6fc");
            },
            success: function (data) {
                if (data.status == 0) {
                    $.each(data.error, function (i, val) {
                        $("." + i + "_error").text(val[0]);
                        $("#" + i).css("border-color", "red");
                    });
                    $("#confirm_pop_submit").removeAttr("disabled");
                    $("#confirm_pop_submit").html("Confirm");
                }

                if (data.response == "success") {
                    $("#resp").css("display", "block");
                    $("#resp").html('<b style="color:green;">Updated</b></b>');
                    $("#resp").delay(3000).fadeOut(500);

                    var explode = function () {
                        $("#confirm_pop_submit").removeAttr("disabled");

                        // Refresh the appropriate tab based on the action
                        if (type == "Approved") {
                            // Switch to In Progress tab and refresh it
                            $('#profile-tab').tab('show');
                            inprogress_tab_click();
                        } else if (type == "Declined") {
                            // Switch to Declined tab and refresh it
                            $('#declined-tab').tab('show');
                            declined_tab_click();
                        } else {
                            // Default: refresh pending tab
                            pending_tab_click();
                        }
                        $(".close").click();
                        $("#confirm_pop_submit").html("Confirm");
                    };
                    setTimeout(explode, 3000);

                    // send mail tigger
                    $.ajax({
                        type: "POST",
                        url: send_qry_stats_mail,
                        data: {
                            ticket_id: data.ticket_id,
                            dec_remark: dec_remark,
                        },
                        success: function (data) {
                            if (data.response == "success") {
                            }
                        },
                    });
                    // send mail end
                }
            },
        });
    });
}
function upload_document(ticket_id, emp_name, emp_id, document_string) {
    var remark = "";

    // Clear any previous validation errors
    $(".file-error").remove();
    $(".form-control").removeClass("is-invalid");
    $("#doc_resp").css("display", "none");

    $(".doc_upl").css("display", "none");

    $("#pop_ticket_id").html(ticket_id);
    $("#pop_emp_name").html(emp_name);
    $("#pop_emp_name").html(emp_name);
    $("#pop_emp_id").html(emp_id);
    $("#emp_id").val(emp_id);
    $("#pop_emp_remark").html(remark);
    $("#pop_document").val(document_string);
    $("#ticket_id_hidden").val(ticket_id);

    if (document_string.includes("Pay Slips")) {
        $("#pay_slip_input").css("display", "block");
    }
    if (document_string.includes("F&F Statement")) {
        $("#ff_statement_input").css("display", "block");
    }
    if (document_string.includes("Form 16") || document_string.includes("Form 16 Part A") || document_string.includes("Form 16 Part B")) {
        $("#form_16_input").css("display", "block");
    }
    if (document_string.includes("Relieving Letter")) {
        $("#rel_letter_input").css("display", "block");
    }
    if (document_string.includes("Service Letter")) {
        $("#ser_letter_input").css("display", "block");
    }

    if (document_string.includes("Bonus")) {
        $("#bonus_input").css("display", "block");
    }
    if (document_string.includes("Performance Incentive")) {
        $("#performance_incentive_input").css("display", "block");
    }
    if (document_string.includes("Sales Travel claim")) {
        $("#sales_travel_claim_input").css("display", "block");
    }
    if (document_string.includes("Parental medical reimbursement")) {
        $("#parental_medical_reimbursement_input").css("display", "block");
    }
    // type2
    if (document_string.includes("PF")) {
        $("#pf_input").css("display", "block");
    }
    if (document_string.includes("Gratuity")) {
        $("#gratuity_input").css("display", "block");
    }
    // end type 2

    if (document_string.includes("Others")) {
        $("#others_input").css("display", "block");
    }

    $("#upl_doc_pop_trigger").click();
}

$("#doc_upload_form").submit(function (e) {
    e.preventDefault();

    // Clear previous validation errors
    $(".file-error").remove();
    $(".form-control").removeClass("is-invalid");

    // Get the documents that should be displayed for this query
    var pop_document = $("#pop_document").val();
    var hasAtLeastOneFile = false;

    // Proceed with upload (file validation removed)
    {
        $("#document_upload_submit").attr("disabled", "true");
        var formData = new FormData(this);

        // var pay_slip=$("#pay_slip_doc")[0];

        // Read selected files

        var ff_statement = $("#ff_statement_doc")[0];
        // var form16=$("#form_16_doc")[0];
        var rel_letter = $("#rel_letter_doc")[0];
        var ser_letter = $("#ser_letter_doc")[0];

        var bonus = $("#bonus_doc")[0];
        var performance_incentive = $("#performance_incentive_doc")[0];
        var sales_travel_claim = $("#sales_travel_claim_doc")[0];
        var parental_medical_reimbursement = $(
            "#parental_medical_reimbursement_doc"
        )[0];
        // type2
        var pf = $("#pf_doc")[0];
        var gratuity = $("#gratuity_doc")[0];
        // end type2

        var others_doc = $("#others_doc")[0];
        var pop_document = $("#pop_document").val();
        var emp_id = $("#emp_id").val();
        var ticket_id = $("#ticket_id_hidden").val();
        formData.append("emp_id", emp_id);
        formData.append("ticket_id", ticket_id);

        if (pop_document.includes("Pay Slips")) {
            if ($("#pay_slip_doc").val() !== "") {
                var totalfiles =
                    document.getElementById("pay_slip_doc").files.length;
                for (var index = 0; index < totalfiles; index++) {
                    formData.append(
                        "pay_slip[]",
                        document.getElementById("pay_slip_doc").files[index]
                    );
                }
            } else if (
                $("#pay_slip_doc").val() == "" &&
                $("#pay_slip_remark").val() !== ""
            ) {
                formData.append("pay_slip", "dummy_img");
            }
        }
        if (pop_document.includes("F&F Statement")) {
            if ($("#ff_statement_doc").val() !== "") {
                formData.append("ff_statement", ff_statement.files[0]);
            } else if (
                $("#ff_statement_doc").val() == "" &&
                $("#ff_statement_remark").val() !== ""
            ) {
                formData.append("ff_statement", "dummy_img");
            }
        }
        if (pop_document.includes("Form 16") || pop_document.includes("Form 16 Part A") || pop_document.includes("Form 16 Part B")) {
            // Handle Form 16 Part A
            if ($("#form_16_part_a").val() !== "") {
                formData.append("form_16_part_a", document.getElementById("form_16_part_a").files[0]);
            } else if (
                $("#form_16_part_a").val() == "" &&
                $("#form_16_part_a_remark").val() !== ""
            ) {
                formData.append("form_16_part_a", "dummy_img");
            }
            
            // Handle Form 16 Part B
            if ($("#form_16_part_b").val() !== "") {
                formData.append("form_16_part_b", document.getElementById("form_16_part_b").files[0]);
            } else if (
                $("#form_16_part_b").val() == "" &&
                $("#form_16_part_b_remark").val() !== ""
            ) {
                formData.append("form_16_part_b", "dummy_img");
            }
        }
        if (pop_document.includes("Relieving Letter")) {
            if ($("#rel_letter_doc").val() !== "") {
                formData.append("rel_letter", rel_letter.files[0]);
            } else if (
                $("#rel_letter_doc").val() == "" &&
                $("#rel_letter_remark").val() !== ""
            ) {
                formData.append("rel_letter", "dummy_img");
            }
        }
        if (pop_document.includes("Service Letter")) {
            if ($("#ser_letter_doc").val() !== "") {
                formData.append("ser_letter", ser_letter.files[0]);
            } else if (
                $("#ser_letter_doc").val() == "" &&
                $("#ser_letter_remark").val() !== ""
            ) {
                formData.append("ser_letter", "dummy_img");
            }
        }
        if (pop_document.includes("Bonus")) {
            if ($("#bonus_doc").val() !== "") {
                formData.append("bonus", bonus.files[0]);
            } else if (
                $("#bonus_doc").val() == "" &&
                $("#bonus_remark").val() !== ""
            ) {
                formData.append("bonus", "dummy_img");
            }
        }
        if (pop_document.includes("Performance Incentive")) {
            if ($("#performance_incentive_doc").val() !== "") {
                formData.append(
                    "performance_incentive",
                    performance_incentive.files[0]
                );
            } else if (
                $("#performance_incentive_doc").val() == "" &&
                $("#performance_incentive_remark").val() !== ""
            ) {
                formData.append("performance_incentive", "dummy_img");
            }
        }
        if (pop_document.includes("Sales Travel claim")) {
            if ($("#sales_travel_claim_doc").val() !== "") {
                formData.append(
                    "sales_travel_claim",
                    sales_travel_claim.files[0]
                );
            } else if (
                $("#sales_travel_claim_doc").val() == "" &&
                $("#sales_travel_claim_remark").val() !== ""
            ) {
                formData.append("sales_travel_claim", "dummy_img");
            }
        }
        if (pop_document.includes("Parental medical reimbursement")) {
            if ($("#parental_medical_reimbursement_doc").val() !== "") {
                formData.append(
                    "parental_medical_reimbursement",
                    parental_medical_reimbursement.files[0]
                );
            } else if (
                $("#parental_medical_reimbursement_doc").val() == "" &&
                $("#parental_medical_reimbursement_remark").val() !== ""
            ) {
                formData.append("parental_medical_reimbursement", "dummy_img");
            }
        }
        // type2
        if (pop_document.includes("PF")) {
            if ($("#pf_doc").val() !== "") {
                formData.append("pf", pf.files[0]);
            } else if (
                $("#pf_doc").val() == "" &&
                $("#pf_remark").val() !== ""
            ) {
                formData.append("pf", "dummy_img");
            }
        }
        if (pop_document.includes("Gratuity")) {
            if ($("#gratuity_doc").val() !== "") {
                formData.append("gratuity", gratuity.files[0]);
            } else if (
                $("#gratuity_doc").val() == "" &&
                $("#gratuity_remark").val() !== ""
            ) {
                formData.append("gratuity", "dummy_img");
            }
        }
        // end type2

        if (pop_document.includes("Others")) {
            if ($("#others_doc").val() !== "") {
                formData.append("others_doc", others_doc.files[0]);
            } else if (
                $("#others_doc").val() == "" &&
                $("#others_remark").val() !== ""
            ) {
                formData.append("others_doc", "dummy_img");
            }
        }

        $("#document_upload_submit").html("Loading..");
        // mail send cnt
        var send_mail_cnt = parseInt($("#send_mail_cnt").html());
        var new_count = send_mail_cnt + 1;
        $("#send_mail_cnt").html(new_count);
        // mail send cnt end

        $.ajax({
            type: "POST",
            url: doc_upload_admin_submit,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.response == "success") {
                    // Show success message only when upload is actually successful
                    $("#doc_resp").css("display", "block");
                    $("#doc_resp").html(
                        '<b style="color:green;">Update Successful</b>'
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
                    send_mail_cnt = parseInt($("#send_mail_cnt").html());
                    new_count = send_mail_cnt - 1;
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
        });
    }
});

function doc_detail(ticket_id, emp_id, emp_name) {
    $.ajax({
        type: "POST",
        url: doc_updated_detail,
        data: { ticket_id: ticket_id, emp_id: emp_id },
        success: function (data) {
            if (data.response == "success") {
                $("#doc_s_ticket_id").html(ticket_id);
                $("#doc_s_emp_name").html(emp_name);
                $("#doc_s_emp_id").html(emp_id);
                // $("#doc_s_emp_remark").html(admin_remark);
                $("#doc_show_div").html(data.show_div);
                $("#show_doc_pop_trigger").click();
            }
        },
    });
}
//reassign
function reassign_query_click(ticket_id, emp_id) {
    // $("#update_reassign_form")[0].reset();
    $("#assign_to")
        .children('option[style="display: none;"]')
        .removeAttr("style");
    // pop open
    $.ajax({
        type: "POST",
        url: reassign_query,
        data: { ticket_id: ticket_id, emp_id: emp_id },
        dataType: "json",
        success: function (data) {
            $("#reas_edit_pop").click();
            $("#emp_id_u").html(data.emp_id);
            $("#u_emp_id").val(emp_id);
            $("#ticket_id").val(data.ticket_id);
            $("#from_docu").val(data.document);
            // var ar=data.document;
            // var doc_arr = ar.split(',');
            // alert(doc_arr);
            // d_i=0;

            // while(d_i<count(doc_arr)){
            if (data.document.includes("Sales Travel claim")) {
                $("#assign_from").val("CL001");
                //   $('#assign_to').children('option[value="Claims"]').hide();
            } else if (data.document.includes("Form 16")) {
                $("#assign_from").val("PRFN001");
                //   $('#assign_to').children('option[value="Payroll_Finance"]').hide();
            } else if (
                data.document.includes("F&F Statement") ||
                data.document.includes("Service Letter") ||
                data.document.includes("PF") ||
                data.document.includes("Others")
            ) {
                $("#assign_from").val("HR001");
                //  $('#assign_to').children('option[value="F_F_HR"]').hide();
            } else if (
                data.document.includes("Pay Slips") ||
                data.document.includes("Bonus") ||
                data.document.includes("Performance Incentive") ||
                data.document.includes("Bonus") ||
                data.document.includes("Parental medical reimbursement") ||
                data.document.includes("Gratuity")
            ) {
                $("#assign_from").val("PRHR001");
                //  $('#assign_to').children('option[value="Payroll_HR"]').hide();
            }

            //     d_i++;
            // }
        },
    });
}
$("#update_reassign").click(function () {
    $("#assign_to")
        .children('option[style="display: none;"]')
        .removeAttr("style");
    $("#update_reassign").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: reassign_form,
        data: $("#update_reassign_form").serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $(".error-text").text("");
            $(".red_border").css("border-color", "#e4e6fc");
        },
        success: function (data) {
            if (data.res == "success") {
                $("#up_amb").css("display", "block");
                $("#up_amb").html(
                    '<b style="color:green;">Updated Successfully..!</b></b>'
                );
                $("#up_amb").delay(3000).fadeOut(500);
                var append_table = "pending_query_tbl";
                get_query("Pending", append_table);
                var explode = function () {
                    $(".close").click();
                    $("#update_reassign_form")[0].reset();
                    $("#update_reassign").attr("disabled", false);
                };
                setTimeout(explode, 2000);
            }
            if (data.status == 0) {
                $.each(data.error, function (i, val) {
                    $("#" + i + "_error").text(val[0]);
                    $("#" + i).css("border-color", "red");
                });
                $("#update_reassign").attr("disabled", false);
            }
        },
    });
});
$("#reas_edit_pop1").on("hidden.bs.modal", function () {
    $("#update_reassign_form")[0].reset();
    $("#assign_to")
        .children('option[style="display: none;"]')
        .removeAttr("style");
});

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
