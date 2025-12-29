
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
        url: get_emp_all_doc_data, 
        data: {  },
        success:function(data){ 
            var doc=data.listing_doc;
            $("#qmp_doc_rows").html("");
            $("#qmp_doc_rows").html(doc);

        }
    })
}

function view_doc_emp(emp_id,admin_remark,document,file_name){

    $.ajax({
        type: "POST",
        url: doc_updated_detail_emp,
        data: { 'emp_id':emp_id,'document':document,'file_name':file_name},
        success: function (data) {

            if (data.response == "success") {

                $("#doc_s_emp_remark").html(admin_remark);

                $("#doc_show_div").html(data.show_div);
                $("#show_doc_pop_trigger").click();

            } 
            
            
        }
    })
}