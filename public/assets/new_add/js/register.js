$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    // $("#reg_form_submit").attr("disabled","true");
}); 

$("#reg_form").submit(function(){

    $("#reg_form_submit").attr("disabled","true");
    $("#reg_form_submit").html("Loading..");

    $.ajax({  
        type: "POST",
        url: register_click,  
        data: $("#reg_form").serialize(),  
        dataType: "JSON",
        beforeSend:function(){
            $('.error-text').text('');
            $('.red_border').css('border-color','#e4e6fc');
        },
        success: function (data) {


            if(data.response=="Created_wait_for_verify"){
                $( "#error_pop_trigger").click();
                $("#reg_form")[0].reset();
                setTimeout(function () {
                    window.location = "login";
                }, 2000);
            }
            if(data.status == 0){
                $.each(data.error, function(i,val){
                    $('span.'+i+'_error').text(val[0]);
                    $('#'+i).css('border-color','red');
                })
            }
            

            $("#reg_form_submit").removeAttr("disabled");
            $("#reg_form_submit").html("Register");

            
        }
    })
})

$("#password2").keyup(function(){
    var pass=$("#password").val();
    var pass2=$("#password2").val();
    if(pass!==pass2){
        $(".bar").html("Not Matching");
        $("#reg_form_submit").attr("disabled","true");
    }
    else if(pass==pass2){
        $(".bar").html("Matched");
        $("#reg_form_submit").removeAttr("disabled");
    }
})
$("#password").keyup(function(){
    $(".bar").html("");
    $("#password2").val("");
    $("#reg_form_submit").attr("disabled","true");
})

$("#otp_submit").click(function(){
    var otp=$("#mail_otp").val();

    if(otp!==""){

        var name=$("#name").val();
        var emp_id=$("#employee_id").val();
        var pan_num=$("#pan_number").val();
        var dob=$("#dob").val();
        var mobileno=$("#contact_number").val();
        var email=$("#email").val();
        var password=$("#password2").val();
        
        $.ajax({  
            type: "POST",
            url: otp_submit, 
            data: {  'name': name, 'emp_id': emp_id,'pan_num':pan_num,'dob':dob,'mobileno':mobileno,'email':email,'password':password,'otp':otp, },
            dataType: "JSON",

            success: function (data) {
                if (data.response == "success") {
                    window.location = data.url;
                } 
                else {
                    $("#otp_resp").css("display","block");
                    $("#otp_resp").html('<b style="color:red;">Invalid OTP..!</b></b>');
                    $("#otp_resp").delay(3000).fadeOut(500);
                }
            }
        })


    }else{
       return false(); 
    }
})
