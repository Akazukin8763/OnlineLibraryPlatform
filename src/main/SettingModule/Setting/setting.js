export function changePassword(__password) {
    if (!(6 <= __password.length && __password.length <= 16)) {
        console.log('__password 長度超出限制。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "SettingModule/Setting/changePassword.php",
        dataType: "json",
        data: {
            password: __password,
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

export function changeUsername(__username) {
    if (!(__username.length <= 16)) {
        console.log('__username 長度超出限制。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "SettingModule/Setting/changeUsername.php",
        dataType: "json",
        data: {
            username: __username,
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