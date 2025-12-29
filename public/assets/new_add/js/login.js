$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
}); 

function forgot_pass_pop(){
    $("#log_f_p_pop_trigger").click();
}


$("#pass_reset_form").submit(function(){

    if($("#password").val() == "" && $("#c_password").val() == ""){
        return false;
    }

    $("#pass_reset_form_submit").attr("disabled","true");

    $.ajax({  
        type: "POST",
        url: password_change_submit, 
        data: $("#pass_reset_form").serialize(), 
        dataType: "JSON",

        success: function (data) {

            if (data.response == "success") {
                $(".login_resp").css({"display":"block",'color':'green'});
                $(".login_resp").html('Updated Successfully..!');
                $(".login_resp").delay(3000).fadeOut(500);
                window.location = "login";
            } 
            else{
                if($("#password").val() != "" && $("#c_password").val() != ""){
                    $(".login_resp").css({"display":"block",'color':'red'});
                    $(".login_resp").html('Password mismatch..!');
                    $(".login_resp").delay(3000).fadeOut(500);
                }
            }
            
            $("#pass_reset_form_submit").removeAttr("disabled");
        }
    })

})

$("#f_p_login_form").submit(function(){

    $("#f_p_form_submit").attr("disabled",true);

    $.ajax({  
        type: "POST",
        url: f_p_submit, 
        data: $("#f_p_login_form").serialize(), 
        dataType: "JSON",

        success: function (data) {
            $("#f_p_form_submit").attr("disabled",false);
            if (data.response == "success") {
                $("#f_p_login_form")[0].reset();
                toastr.success("Mail sent successfully.");
                $(".needs-validation").removeClass("was-validated");
                $(".cls_btn").click();
            } 
            else{
                if($("#f_p_mailid").val() !=""){
                    $(".login_resp").css("display","block");
                    $(".login_resp").html('Invalid Email..!');
                    $(".login_resp").delay(3000).fadeOut(500);
                }
            }
        },
        

    })
})

$("#login_form").submit(function(){


    $("#login_form_submit").attr("disabled","true");

    $.ajax({  
        type: "POST",
        url: login_check, 
        data: $("#login_form").serialize(), 
        dataType: "JSON",

        success: function (data) { 

            if (data.response == "success") {
                window.location = data.url;
            } 
            if (data.response == "first_login") {
                window.location = data.url;
            } 
            if (data.response == "hold") {
                $(".login_resp").css("display","block");
                $(".login_resp").html('Under Profile Validation..!');
                $(".login_resp").delay(3000).fadeOut(500);
            } 
            if(data.response =="not valid") {
                $(".login_resp").css("display","block");
                if($("#emp_id").val() !="" && $("#password").val() !=""){
                    $(".login_resp").html('Please check your Email Id & Password..!');
                }else{
                    $(".login_resp").html('');
                }
                $(".login_resp").delay(3000).fadeOut(500);
            }

            $("#login_form_submit").removeAttr("disabled");
        }
    })
})