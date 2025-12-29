$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
});

$("#add_ambassador_form").submit(function(e){
    e.preventDefault();
    $("#add_ambassador_submit").attr("disabled","true");
    $("#add_ambassador_submit").html("Loading..");

    $(".input_clr").text("");
    $(".inp").css('border-color','#e4e6fc');

    $.ajax({  
        type: "POST",
        url: add_alumni_submit,  
        data: $("#add_ambassador_form").serialize(),  
        dataType: "JSON",

        success: function (data) {

            if(data.response=="Success"){
                $("#form_resp").css("display","block");
                $("#form_resp").html('<b style="color:green;">Added Successfully..!</b></b>');
                $("#form_resp").delay(3000).fadeOut(500);
                $('#add_ambassador_form')[0].reset();
            }
            else if(data.status == 0){
                $.each(data.error, function(i,val){
                    $('span.'+i+'_error').text(val[0]);
                    $('#'+i).css('border-color','red');
                })
            }
            else {
                $("#form_resp").css("display","block");
                $("#form_resp").html('<b style="color:red;">'+data.response
                +'</b></b>');
                $("#form_resp").delay(3000).fadeOut(500);
            }

            $("#add_ambassador_submit").removeAttr("disabled");
            $("#add_ambassador_submit").html('<i class="fas fa-plus"></i>&nbsp;Add Alumni');
            
        },
        error: function(data){
            $("#add_ambassador_submit").removeAttr("disabled");
            $("#add_ambassador_submit").html('<i class="fas fa-plus"></i>&nbsp;Add Alumni');
        }
    })

})

$("#bulk_upload_form").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);

    $("#bulk_upload_form_submit").attr("disabled","true");
    $("#bulk_upload_form_submit").html("Loading..");

    var import_file=$("#import_file")[0];
    formData.append('upload_file', import_file.files[0]);


    $.ajax({  
        type: "POST",
        url: amb_bulk_upl_submit,  
        data: formData,  
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
           
            if(data.response=="Success"){ 
                toastr.success("Imported Successfully.");
                $('#bulk_upload_form')[0].reset();
            }
            if(data.response=="Failed"){
                $(".error_div").attr("hidden",false);
                $(".error_div").html('<div style="background-color:#F8D7DA;overflow-y:scroll;height:200px;color:#752957;" class="alert scroll_design alert-danger alert-dismissible fade show" role="alert"  >'+data.messages+'<button type="button" class="close" title="Close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }else{
                $(".close").click();
                $(".error_div").attr("hidden",true);
                $(".error_div").html("");
            }
            $("#bulk_upload_form_submit").removeAttr("disabled");
            $("#bulk_upload_form_submit").html('<i class="fas fa-prescription-bottle-alt"></i>&nbsp;Add Alumni');
        },
        error: function(response) {
            $(".close").click();
            toastr.error('No Data Found, Please Check');
            $("#bulk_upload_form_submit").removeAttr("disabled");
            $("#bulk_upload_form_submit").html('<i class="fas fa-prescription-bottle-alt"></i>&nbsp;Add Alumni');
        }
    })

})

function checkextension(file) {
    var ext = (file.files[0].name.split('.').pop()).toLowerCase();
    if(ext != "xls" && ext != "xlsx"  && ext != "csv"){
        toastr.error("Sorry, this file format is not supported.");
        $("#import_file").val("");
        return false;
    }
} 
