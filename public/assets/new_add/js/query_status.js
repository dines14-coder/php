
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 

    get_emp_all_query();
}); 

function get_emp_all_query(){
    $.ajax({
        type: "POST", 
        url: get_emp_all_quer_data,  
        data: {  },
        success:function(data){ 

            var querys=data.listing_querys;

            $("#qmp_qry_rows").html("");
            $("#qmp_qry_rows").html(querys);

        }
    })
}

function view_doc_emp(ticket_id,emp_id,admin_remark){

    $.ajax({ 
        type: "POST",
        url: doc_updated_detail_emp,
        data: { 'ticket_id':ticket_id,'emp_id':emp_id,},
        success: function (data) {

            if (data.response == "success") {

                $("#doc_s_ticket_id").html(ticket_id);
                $("#doc_s_emp_remark").html(admin_remark);

                $("#doc_show_div").html(data.show_div);
                $("#show_doc_pop_trigger").click();

            } 
            
            
        }
    })
}