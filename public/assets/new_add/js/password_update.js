
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        } 
    }); 
}); 

$("#new_password").keyup(function(){
  $("#new_confirm_password").val("");
  document.getElementById("con_pass_res").innerHTML = "";
  
  var old_password=$("#old_password").val();
  var new_password=$("#new_password").val();
  
  if (old_password && new_password && old_password === new_password) {
      document.getElementById("new_password_error").innerHTML = "New password must be different from old password";
      $("#new_password_error").css("color","red");
      $("#password_updated").attr("disabled","true");
  } else {
      document.getElementById("new_password_error").innerHTML = "";
  }
})

$("#old_password").keyup(function(){

    var old_password=$("#old_password").val();

    ajaxRequest = $.ajax({
                   type: 'POST',
                   url: check_password,
                   data: {"old_password": old_password,},
                   // data: $("#signin_otp_send").serialize(),
                   success: function (data) {

                    if (ajaxRequest) { 
                        ajaxRequest.abort();
                    }
                       
                     if (data.logstatus=="Matching") {

                       document.getElementById("old_pass_res").innerHTML = data.logstatus;
                       $("#old_pass_res").css("color","green"); 
                       $("#new_password").removeAttr("readonly");
                       $("#new_confirm_password").removeAttr("readonly");
                       $("#old_password").attr("readonly","true");
                     }

                     else{

                       document.getElementById("old_pass_res").innerHTML = data.logstatus;
                       $("#old_pass_res").css("color","red");

                     }
                   }
           });

})


$("#new_password").keyup(function(){
    var old_password=$("#old_password").val();
    var new_password=$("#new_password").val();
    
    if (old_password && new_password && old_password === new_password) {
        document.getElementById("new_password_error").innerHTML = "New password must be different from old password";
        $("#new_password_error").css("color","red");
        $("#password_updated").attr("disabled","true");
        return;
    } else {
        document.getElementById("new_password_error").innerHTML = "";
    }
    
    // Check confirm password match if it has value
    var new_confirm_password=$("#new_confirm_password").val();
    if (new_confirm_password) {
        validatePasswordMatch();
    }
})

$("#new_confirm_password").keyup(function(){
    validatePasswordMatch();
})

function validatePasswordMatch() {
    var old_password=$("#old_password").val();
    var new_password=$("#new_password").val();
    var new_confirm_password=$("#new_confirm_password").val();
    
    // Check if new password is same as old password
    if (old_password && new_password && old_password === new_password) {
        document.getElementById("new_password_error").innerHTML = "New password must be different from old password";
        $("#new_password_error").css("color","red");
        $("#password_updated").attr("disabled","true");
        return;
    }
    
    if (new_password==new_confirm_password) {
     document.getElementById("con_pass_res").innerHTML = "Password Matching Successfully";
     $("#con_pass_res").css("color","green");
     $("#password_updated").removeAttr("disabled");
    }
    else{
     document.getElementById("con_pass_res").innerHTML = "Password Not Matching";
     $("#con_pass_res").css("color","red");
     $("#password_updated").attr("disabled","true");
    }
}



$("#password_updated").click(function(){
  var new_confirm_password=$("#new_confirm_password").val();
  var new_password=$("#new_password").val();
  var old_password=$("#old_password").val();
   $.ajax({
                    type: 'POST',
                    url: update_pass,
                    data: {"new_confirm_password": new_confirm_password,"new_password":new_password,"old_password":old_password},
                    success: function (data) {
                      if (data.logstatus=="success") {
                         $("#suc_img").css("display","block");
                         $("#suc_img").delay(3000).fadeOut(500);
                         
                         // Check if this is first-time login
                         if (typeof isFirstLogin !== 'undefined' && isFirstLogin) {
                             // For first-time login, redirect to login page
                             setTimeout(function() {
                                 window.location.href = '/login';
                             }, 2000);
                         } else {
                             // For regular users, logout and reload
                             $.ajax({
                                 type: "get",
                                 url: logout,
                                 success: function () {
                                     window.location.reload();
                                 }
                               });
                         }
                      }
                     else if(data.status == 0){
                       $.each(data.error, function(i,val){
                           $('span.'+i+'_error').text(val[0]);
                           // $('#'+i).css('border-color','red');
                       })
                   }
                      else{
                         $("#fai_img").css("display","block");
                         $("#fai_img").delay(3000).fadeOut(500);
                         return false;
                      }
                    },
 
            });
 
 })


 $('#new_password').keypress(function() {

  $('#new_password_error').text("");

});

$('#new_confirm_password').keypress(function() {

  $('#new_confirm_password_error').text("");

});

$('#old_password').keypress(function() {

  $('#old_password_error').text("");

});