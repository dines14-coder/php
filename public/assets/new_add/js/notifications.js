$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    get_notify();
});

setInterval(function () {
    get_notify();
}, 20000);

function get_notify() {
    $(
        '<audio id="chatAudio"><source src="../assets/notification_sound/2.mp3" type="audio/mpeg"></audio>'
    ).appendTo("body");
    $.ajax({
        type: "POST",
        url: get_notification,
        dataType: "JSON",
        success: function (data) {
            $("#notifi_count").html(data.res);
            $("#notify_users").html(data.users);
            if (!localStorage.getItem("notify")) {
                if (data.res > 0) {
                    localStorage.setItem("notify", data.res);
                    $("#chatAudio")[0].play();
                }
            } else {
                if (
                    localStorage.getItem("notify") != data.res &&
                    data.res > 0
                ) {
                    $("#chatAudio")[0].play();
                    localStorage.clear();
                    localStorage.setItem("notify", data.res);
                }
            }
        },
    });
}

$("#notification_view").on("click", function () {
    $.ajax({
        type: "POST",
        url: notify_viewed_update,
        dataType: "JSON",
        success: function (data) {
            if (data.res == "success") {
                get_notify();
                localStorage.clear();
            }
        },
    });
});
