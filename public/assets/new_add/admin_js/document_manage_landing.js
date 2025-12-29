$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    template = Handlebars.compile($("#details-template").html());
    var append_table = "fresh_amb_tbl";
    get_ambassadors("Fresh", append_table);
});

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

function fresh_amb_tab_click() {
    append_table = "fresh_amb_tbl";
    get_ambassadors("Fresh", append_table);
}
function pending_amb_tab_click() {
    append_table = "pending_amb_tbl";
    get_ambassadors("Pending", append_table);
}
function completed_amb_tab_click() {
    append_table = "completed_amb_tbl";
    get_ambassadors("Completed", append_table);
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

function get_ambassadors(type, append_table) {
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
                //     // "action": newexportaction
                // },
            ],
            dom: "Bfrtip",
            processing: true,
            serverSide: true,
            bDestroy: true,
            autoWidth: false,
            scrollX: true,
            iDisplayLength: 50,
            drawCallback: function () {
                processInfo(this.api().page.info());
            },

            ajax: {
                url: get_admin_alumni_datatable,
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
                { data: "emp_id", name: "emp_id" },
                { data: "emp_name", name: "emp_name" },
                // {data: 'document_div', name: 'document_div'},
                { data: "remark", name: "remark" },
                { data: "type_of_leaving", name: "type_of_leaving" },
                { data: "created_at", name: "created_at" },
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
    if (type == "Pending") {
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
            autoWidth: false,
            scrollX: true,
            iDisplayLength: 50,
            drawCallback: function () {
                processInfo(this.api().page.info());
            },
            ajax: {
                url: get_admin_alumni_datatable,
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
                { data: "emp_id", name: "emp_id" },
                { data: "emp_name", name: "emp_name" },
                { data: "document_div", name: "document_div" },
                { data: "remark", name: "remark" },
                { data: "type_of_leaving", name: "type_of_leaving" },
                { data: "created_at", name: "created_at" },
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
    if (type == "Fresh") {
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
            autoWidth: false,
            scrollX: true,
            iDisplayLength: 50,
            drawCallback: function () {
                processInfo(this.api().page.info());
            },

            ajax: {
                url: get_admin_alumni_datatable,
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
                { data: "emp_id", name: "emp_id" },
                { data: "emp_name", name: "emp_name" },
                { data: "remark", name: "remark" },
                { data: "type_of_leaving", name: "type_of_leaving" },
                { data: "created_at", name: "created_at" },
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

function upload_document(emp_id, emp_name, document_string, type) {
    // get remark from controller

    // end get remark from controller

    $(".doc_upl").css("display", "none");

    $("#pop_emp_name").html(emp_name);
    $("#pop_emp_id").html(emp_id);
    $("#emp_id").val(emp_id);
    // $("#pop_emp_remark").html(remark);
    $("#pop_document").val(document_string);
    // $("#remark").val(remark);
    $("#type").val(type);

    if (document_string.includes("Pay Slips")) {
        $("#pay_slip_input").css("display", "block");
    }
    if (document_string.includes("F&F Statement")) {
        $("#ff_statement_input").css("display", "block");
    }
    if (document_string.includes("Form 16")) {
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
    // bonus field
    if (document_string.includes("F&F Document")) {
        $("#f_and_f_input").css("display", "block");
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

function checkextension(file) {
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
}

$("#doc_upload_form").submit(function (e) {
    e.preventDefault();

    if (
        $("#pay_slip_doc").val() != "" ||
        $("#ff_statement_doc").val() != "" ||
        $("#form_16_doc").val() != "" ||
        $("#rel_letter_doc").val() != "" ||
        $("#ser_letter_doc").val() != "" ||
        $("#bonus_doc").val() != "" ||
        $("#performance_incentive_doc").val() != "" ||
        $("#sales_travel_claim_doc").val() != "" ||
        $("#parental_medical_reimbursement_doc").val() != "" ||
        $("#pf_doc").val() != "" ||
        $("#gratuity_doc").val() != ""
    ) {
        $("#document_upload_submit").attr("disabled", "true");

        var formData = new FormData(this);

        // var pay_slip=$("#pay_slip_doc")[0];
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
        // bonus doc from hr only
        var f_and_f = $("#f_and_f_document")[0];
        // type2
        var pf = $("#pf_doc")[0];
        var gratuity = $("#gratuity_doc")[0];
        // end type2
        var pop_document = $("#pop_document").val();
        var emp_id = $("#emp_id").val();
        var type = $("#type").val();

        formData.append("emp_id", emp_id);

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

                // formData.append('pay_slip', pay_slip.files[0]);
            } else {
                formData.append("pay_slip", "");
            }
        }
        if (pop_document.includes("F&F Statement")) {
            if ($("#ff_statement_doc").val() !== "") {
                formData.append("ff_statement", ff_statement.files[0]);
            } else {
                formData.append("ff_statement", "");
            }
        }
        if (pop_document.includes("Form 16")) {
            if ($("#form_16_doc").val() !== "") {
                var totalfiles =
                    document.getElementById("form_16_doc").files.length;
                for (var index = 0; index < totalfiles; index++) {
                    formData.append(
                        "form16[]",
                        document.getElementById("form_16_doc").files[index]
                    );
                }
            } else {
                formData.append("form16", "");
            }
        }
        if (pop_document.includes("Relieving Letter")) {
            if ($("#rel_letter_doc").val() !== "") {
                formData.append("rel_letter", rel_letter.files[0]);
            } else {
                formData.append("rel_letter", "");
            }
        }
        if (pop_document.includes("Service Letter")) {
            if ($("#ser_letter_doc").val() !== "") {
                formData.append("ser_letter", ser_letter.files[0]);
            } else {
                formData.append("ser_letter", "");
            }
        }

        if (pop_document.includes("Bonus")) {
            if ($("#bonus_doc").val() !== "") {
                formData.append("bonus", bonus.files[0]);
            } else {
                formData.append("bonus", "");
            }
        }
        if (pop_document.includes("Performance Incentive")) {
            if ($("#performance_incentive_doc").val() !== "") {
                formData.append(
                    "performance_incentive",
                    performance_incentive.files[0]
                );
            } else {
                formData.append("performance_incentive", "");
            }
        }
        if (pop_document.includes("Sales Travel claim")) {
            if ($("#sales_travel_claim_doc").val() !== "") {
                formData.append(
                    "sales_travel_claim",
                    sales_travel_claim.files[0]
                );
            } else {
                formData.append("sales_travel_claim", "");
            }
        }
        if (pop_document.includes("Parental medical reimbursement")) {
            if ($("#parental_medical_reimbursement_doc").val() !== "") {
                formData.append(
                    "parental_medical_reimbursement",
                    parental_medical_reimbursement.files[0]
                );
            } else {
                formData.append("parental_medical_reimbursement", "");
            }
        }
        // bonus doc from hr
        if (pop_document.includes("F&F Document")) {
            if ($("#f_and_f_document").val() !== "") {
                formData.append("f_and_f_document", f_and_f.files[0]);
            } else {
                formData.append("f_and_f_document", "");
            }
        }

        // type 2
        if (pop_document.includes("PF")) {
            if ($("#pf_doc").val() !== "") {
                formData.append("pf", pf.files[0]);
            } else {
                formData.append("pf", "");
            }
        }
        if (pop_document.includes("Gratuity")) {
            if ($("#gratuity_doc").val() !== "") {
                formData.append("gratuity", gratuity.files[0]);
            } else {
                formData.append("gratuity", "");
            }
        }
        // end type 2

        $("#document_upload_submit").html("Loading..");

        // pre resp- for slow mail resp

        // pre response end
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
                    $("#doc_resp").css("display", "block");
                    $("#doc_resp").html(
                        '<b style="color:green;">Updated Successfully..!</b></b>'
                    );
                    $("#doc_resp").delay(3000).fadeOut(500);
                    if (type == "Fresh") {
                        fresh_amb_tab_click();
                    }
                    if (type == "Pending") {
                        pending_amb_tab_click();
                    }
                    $(".close").click();
                    $("#document_upload_submit").removeAttr("disabled");
                    $("#doc_upload_form")[0].reset();
                    $("#document_upload_submit").html("Submit");

                    // mail send cnt
                    send_mail_cnt = parseInt($("#send_mail_cnt").html());
                    new_count = send_mail_cnt - 1;
                    $("#send_mail_cnt").html(new_count);
                    // mail send cnt end
                }
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    } else {
        toastr.error("Please fill atleast single field.");
    }
});

function doc_detail(emp_id, emp_name, remark) {
    $.ajax({
        type: "POST",
        url: doc_updated_detail,
        data: { emp_id: emp_id },
        success: function (data) {
            if (data.response == "success") {
                $("#doc_s_emp_name").html(emp_name);
                $("#doc_s_emp_id").html(emp_id);
                $("#doc_s_emp_remark").html(remark);

                $("#doc_show_div").html(data.show_div);
                $("#show_doc_pop_trigger").click();
            }
        },
    });
}

function f_and_f_document_popup(emp_id) {
    $("#hidden_pop_emp_id").val(emp_id);

    $.ajax({
        type: "POST",
        url: f_and_f_document_pop,
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
        url: downloadPDF,
        data: { emp_id: emp_id },
        success: function (response) {
            // var host = window.location.origin+'/CK_Alumni/public';
            // var host = window.location.origin;
            // var host_url = host+"/"+response;
            var host_url = response;

            window.open(host_url, "_blank").focus();
        },
    });
});
