
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
}); 

$("#login_form").submit(function(){

    $("#login_form_submit").attr("disabled","true");

    $.ajax({  
        type: "POST",
        url: admin_login_check, 
        data: $("#login_form").serialize(), 
        dataType: "JSON",

        success: function (data) {

            if (data.response == "success") {  
                window.location = data.url;
            } 
            else {
                $(".login_resp").css("display","block");
                if($("#emp_id").val() !="" && $("#password").val() !=""){
                    $(".login_resp").html('please check your Email Id & Password..!');
                }else{
                    $(".login_resp").html('');
                }
                $(".login_resp").delay(3000).fadeOut(500);
            }

            $("#login_form_submit").removeAttr("disabled");
        }
    })
})