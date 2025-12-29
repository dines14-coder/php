$(document).ready(function(){
    pending_table();
    
})

function pending_table(){
    $('#bank_account_table').DataTable().destroy();
    $("#bank_account_table").DataTable({
        lengthMenu: [
            [10, 50, 100, 250, 500, -1],
            [10, 50, 100, 250, 500, "All"],
        ],
        processing: true,
        serverSide: true,
        bAutoWidth: false,
    
        // ajax: "{{ route('accountdetails') }}",
        ajax : {
            url : 'accountdetails',
            data : function(d){
                d.status = 'Pending';
            },
        },
       
        columns: [
            { data: "id", name: "id" },
            { data: "emp_id", name: "emp_id" },
            { data: "cheque", name: "cheque" },
            { data: "passbook", name: "passbook" },
            { data: "action", name: "action" },
        ],
    });
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();

   
}

function completed_table(){
    $('#bank_completed_table').DataTable().destroy();
    $("#bank_completed_table").DataTable({
        lengthMenu: [
            [10, 50, 100, 250, 500, -1],
            [10, 50, 100, 250, 500, "All"],
        ],
        processing: true,
        serverSide: true,
        bAutoWidth: false,
    
        // ajax: "{{ route('accountdetails') }}",
        ajax : {
            url : 'accountdetails',
            data : function(d){
                d.status = 'Approved';
            },
        },
       
        columns: [
            { data: "id", name: "id" },
            { data: "emp_id", name: "emp_id" },
            { data: "cheque", name: "cheque" },
            { data: "passbook", name: "passbook" },
            { data: "action", name: "action" },
        ],
    });
    
}
function rejected_table(){
    $('#bank_rejected_table').DataTable().destroy();

    $("#bank_rejected_table").DataTable({
        lengthMenu: [
            [10, 50, 100, 250, 500, -1],
            [10, 50, 100, 250, 500, "All"],
        ],
        processing: true,
        serverSide: true,
        bAutoWidth: false,
    
        // ajax: "{{ route('accountdetails') }}",
        ajax : {

            url : 'accountdetails',
            data : function(d){
                d.status = 'Rejected';
            }, 
        },
       
        columns: [
            { data: "id", name: "id" },
            { data: "emp_id", name: "emp_id" },
            { data: "cheque", name: "cheque" },
            { data: "passbook", name: "passbook" },
            { data: "action", name: "action" },
        ],
    });
    
}
function approve_bank(id) {
    
    $.ajax({
        type: "POST",
        url: "approve",
        data: {id: id }, 
        dataType: "json",
        success: function (data){
            if (data.response == "success"){
                toastr.success('Approve successfull');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
           
        }
       
    });
}
function reject_bank(id) {
   
    $.ajax({
        type: "POST",
        url: "reject",
        data: {id: id }, 
        dataType: "json",
        success: function (data){
            if (data.response == "success"){
                toastr.success('Rejected');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
           
        }
       
    });
}
