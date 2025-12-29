$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $('input[name="daterange"]').daterangepicker({
        opens: "left",
    });
});
function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one("preXhr", function (e, s, data) {
        data.start = 0;
        data.length = 2147483647;
        dt.one("preDraw", function (e, settings) {
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
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            setTimeout(dt.ajax.reload, 0);
            return false;
        });
    });
    dt.ajax.reload();
}
var table;
function get_revert_remark(tbl) {
    table = $(tbl).DataTable({
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-file-excel" ></i>  Excel',
                titleAttr: "Excel",
                exportOptions: {
                    body: function (data, row, column, node) {
                        console.log(column);
                        console.log(data);
                        // If column index is 6 (qc_remarks), replace HTML line breaks with a combination of <br> and Excel-compatible line breaks
                        return column === 5 ? data.replace(/=>/g, '\n') : data;
                    },
                    columns: [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14],
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
            url: "getQcMisReport",
            type: "POST",
            data: function (d) {
                // var dateRange = $('input[name="daterange"]').data('daterangepicker');
                // d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                // d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                var dateRange = $('input[name="daterange"]').data('daterangepicker');
                
                if (dateRange && $('input[name="daterange"]').val()) {
                    d.from_date = dateRange.startDate.format('YYYY-MM-DD');
                    d.to_date = dateRange.endDate.format('YYYY-MM-DD');
                } 
            }
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "emp_id", name: "emp_id" },
            { data: "emp_name", name: "emp_name" },
            { data: "received_date", name: "received_date" },
            { data: "received_time", name: "received_time" },
            { data: "qc_remark", name: "qc_remark" },
            { data: "qc_remarks", name: "qc_remarks" , visible: false},
            { data: "send_to_hr_date", name: "send_to_hr_date" },
            { data: "send_to_hr_time", name: "send_to_hr_time" },
            { data: "received_from_hr_date", name: "received_from_hr_date" },
            { data: "received_from_hr_time", name: "received_from_hr_time" },
            { data: "amount_remarks", name: "amount_remarks" },
            { data: "send_to_finance", name: "send_to_finance" },
            { data: "date", name: "date" ,width : '5%'},
            { data: "time", name: "time" },
            { data: "status", name: "status" },
        ],
        order: [[0, "desc"]],
        columnDefs: [
            {
                targets: [3, 6],
                type: "date-range",
            },
        ],
    });
    $(".dataTables_filter").append(
        '&nbsp&nbsp&nbsp<label>Date Range: <input type="text" name="daterange" id="daterange"  class ="form-control form-control-sm"  value="" /></label>'
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
        console.log($(this).val())
        
        if($(this).val()){
            $('.applyBtn').removeAttr("disabled",'');
        }else{
            $('.applyBtn').attr("disabled",'');
        }
     });
}

$(document).ready(function () {
    get_revert_remark("#mis_table");
});
