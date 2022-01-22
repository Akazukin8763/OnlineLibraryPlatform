export function register(__username, __email, __password) {
    if (!(__username.length <= 16)) {
        console.log('__username 長度超出限制。');
        return;
    }
    else if (!(__email.length <= 64)) {
        console.log('__email 長度超出限制。');
        return;
    }
    else if (!(6 <= __password.length && __password.length <= 16)) {
        console.log('__password 長度超出限制。');
        return;
    }

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

export function login(__email, __password) {
    if (!(__email.length <= 64)) {
        console.log('__email 長度超出限制。');
        return;
    }
    else if (!(6 <= __password.length && __password.length <= 16)) {
        console.log('__password 長度超出限制。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "AccountModule/Account/login.php",
        dataType: "json",
        data: {
            email: __email,
            password: __password,
        },
        success: function(response) {
            if (response.username) { // 回傳的 json 中含有 (ID 可能為 0，表示為遊客), username, (is_admin 可能為 0，表示非管理員)
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

export function logout() {
    $.ajax({
        type: "POST",
        url: "AccountModule/Account/logout.php",
        dataType: "json",
        data: {},
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS = 'SUCCESS') {
                    window.location.href = "index.php"; // 導向登入畫面
                }
                else {
                    //console.log("化成無法登出！");
                    console.log(response.errorMsg);
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

export function forgotPassword(__username, __email) {
    if (!(__username.length <= 16)) {
        console.log('__username 長度超出限制。');
        return;
    }
    else if (!(__email.length <= 64)) {
        console.log('__email 長度超出限制。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "AccountModule/Account/forgotPassword.php",
        dataType: "json",
        data: {
            username: __username,
            email: __email,
        },
        success: function(response) {
            if (response.old_password && response.new_password) { // 回傳的 json 中含有 old_password, new_password
                console.log('Old: [' + response.old_password + '], New: [' + response.new_password + ']');
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