export function register(__username, __email, __password) {
    $.ajax({
        type: "POST",
        url: "AccountModule/Account/register.php",
        dataType: "json",
        data: {
            username: __username,
            email: __email,
            password: __password,
        },
        success: function(response) {
            if (response.ID) { // 回傳的 json 中含有 ID
                //console.log(response.ID);
                window.location.href = "index.php"; // 導向登入畫面
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

export function login(__username, __password) {
    $.ajax({
        type: "POST",
        url: "AccountModule/Account/login.php",
        dataType: "json",
        data: {
            username: __username,
            password: __password,
        },
        success: function(response) {
            if (response.ID && response.is_admin) { // 回傳的 json 中含有 ID, is_admin
                //console.log(response.ID);
                window.location.href = "welcome.php"; // 導向首頁
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

export function logout(__ID) {
    $.ajax({
        type: "POST",
        url: "AccountModule/Account/logout.php",
        dataType: "json",
        data: {
            ID: __ID,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS = 'SUCCESS') {
                    window.location.href = "index.php"; // 導向登入畫面
                }
                else {
                    console.log("化成無法登出！");
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

export function forgotPassword() {

}