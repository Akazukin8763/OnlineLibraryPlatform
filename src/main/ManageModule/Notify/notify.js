export function notifyAnnouncement(__content) {
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
                    console.log(response.__STATUS);
                }
                else {
                    console.log(response.__STATUS + ": " + response.errorMsg);
                }
            }
            else {
                console.log(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            console.log(jqXHR);
        }
    })
}