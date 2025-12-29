$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    check_theme();
    check_theme_sidebar();
}); 
function check_theme(){
    $.ajax({  
        type: "POST",
        url: check_theme_clr, 
        data: {}, 
        dataType: "JSON",

        success: function (data) {
            if(data.have=="have"){
                if(data.theme=="light"){
                    $("#light_theme_click").click();
                }
                else{
                    $("#dark_theme_click").click();
                }
            }
        }
    })
}
function check_theme_sidebar(){
    $.ajax({  
        type: "POST",
        url: check_theme_sidebar_clr, 
        data: {}, 
        dataType: "JSON",

        success: function (data) {
            if(data.have=="have"){
                if(data.theme=="light"){
                    $("#light_sidebar").click();
                }
                else{
                    $("#dark_sidebar").click();
                }
            }
        }
    })
}
function theme_color(theme_clr){
    $.ajax({  
        type: "POST",
        url: theme_change, 
        data: {'theme_clr':theme_clr,}, 
        dataType: "JSON",

        success: function (data) {

        }
    })
}

function theme_sidebar_color(theme_clr){
    $.ajax({  
        type: "POST",
        url: theme_sidebar_change, 
        data: {'theme_clr':theme_clr,}, 
        dataType: "JSON",

        success: function (data) {

        }
    })
}
