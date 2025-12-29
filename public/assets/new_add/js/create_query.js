
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    check_valid_doc();
}); 
function check_valid_doc(){
    $.ajax({  
        type: "POST",
        url: check_valid_doc_upl,   
        data: {}, 
        dataType: "JSON",

        success: function (data) {
            if(data.hide_div.includes("Pay Slips")){
                $("#pay_slip_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("F&F Statement")){
                $("#ff_statement_check input").prop("disabled",true);
                
            }
            if(data.hide_div.includes("Form 16")){
                $("#form_16_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("Relieving Letter")){
                $("#rel_letter_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("Service Letter")){
                $("#ser_letter_check input").prop("disabled",true);
            }

            // 
            if(data.hide_div.includes("Bonus")){
                $("#bonus_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("Performance Incentive")){
                $("#performance_incentive_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("Sales Travel claim")){
                $("#sales_travel_claim_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("Parental medical reimbursement")){
                $("#parental_medical_reimbursement_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("PF")){
                $("#pf_check input").prop("disabled",true);
            }
            if(data.hide_div.includes("Gratuity")){
                $("#gratuity_check input").prop("disabled",true);
            }
            // 
            if(data.hide_div.includes("Others")){
                $("#others_check input").prop("disabled",true);
            }
            
        }
    })

}

// $(".f_type_1").click(function(){
//     $(".f_type_2").prop("checked", false);
// })
// $(".f_type_2").click(function(){
//     $(".f_type_1").prop("checked", false);
// })




    $("#bank_submit").on("click",function(){
       
        $("#bank_account_form").submit(function(){
            $("#bank_submit").attr("disabled","true");
            

    // alert(1);
    // console.log($("#bank_account_form").serialize());
    // console.log($("#cheque").val());
    var formData = new FormData(this);
    $.ajax({
        type: "POST",
        url: bank_account_form,   
        data: formData, 
        contentType: false, // Set content type to false for FormData
        processData: false, 
        // dataType: "JSON",

        success: function (data){
            if (data.response == "success"){
                toastr.success('submitted successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
                $("#cheque").attr("disabled","true");
            $("#passbook").attr("disabled","true");
            }
            else{
                toastr.error('error with file submission');
            }
        }
    })
});
})
$("#query_form").submit(function(){

    $("#query_form_submit").attr("disabled","true");  

    $.ajax({  
        type: "POST",
        url: query_form_submit,   
        data: $("#query_form").serialize(), 
        dataType: "JSON",

        success: function (data) {

            if (data.response == "success") {
                $("#pop_msg").html('Your Ticket ID : '+data.ticket_id+''); 
                $("#qry_suc_pop_trigger").click(); 
                var explode = function(){
                    window.location.reload();
                };
                setTimeout(explode, 3000);
            } 
            if (data.response == "need remark") {
                $("#query_resp").css("display","block");
                $("#query_resp").html('<b style="color:red;">Need Remark for Other Document..!</b>');
                $("#query_resp").delay(3000).fadeOut(500);
            }
            if (data.response == "no document choosed") {
                $("#query_resp").css("display","block");
                $("#query_resp").html('<b style="color:red;">Kindly Choose Document..!</b>');
                $("#query_resp").delay(3000).fadeOut(500);
            }

            $("#query_form_submit").removeAttr("disabled");
        }
    })
})