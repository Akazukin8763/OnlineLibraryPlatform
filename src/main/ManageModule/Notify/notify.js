export function notifyAnnouncement(__content) {
    if (!(0 < __content.length && __content.length <= 256)) {
        if (!(0 < __content.length)) $("#announcementERR").html("請輸入公告內容。");
        else $("#announcementERR").html("長度需小於 256 個字元。");
        $("#announcement").focus();
        return;
    }

    $.ajax({
        type: "POST",
        url: "ManageModule/Notify/notifyAnnouncement.php",
        dataType: "json",
        data: {
            content: __content,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    $("#btn_clear").trigger("click");
                }
                else {
                    //console.log(response.__STATUS + ": " + response.errorMsg);
                    $("#submitERR").html(response.errorMsg);
                }
            }
            else {
                //console.log(response.errorMsg);
                $("#submitERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#submitERR").html("伺服器連線錯誤。");
        }
    })
}