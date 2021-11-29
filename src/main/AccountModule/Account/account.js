export function register(__username, __email, __password) {
    if (__username.length > 16) {
        console.log('__username 長度超出限制。');
        return;
    }
    else if (__email.length > 64) {
        console.log('__email 長度超出限制。');
        return;
    }
    else if (__password.length > 16) {
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

export function login(__username, __password) {
    if (__username.length > 16) {
        console.log('__username 長度超出限制。');
        return;
    }
    else if (__password.length > 16) {
        console.log('__password 長度超出限制。');
        return;
    }

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

export function forgotPassword(__username, __email) {
    if (__username.length > 16) {
        console.log('__username 長度超出限制。');
        return;
    }
    else if (__email.length > 64) {
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