export function listUser(admin) {
    $.ajax({
        type: "POST",
        url: "user_func/listUser.php",
        dataType: "json",
        data: {
            is_admin: admin, // 表單欄位 ID is_admin
        },
        success: function(response) {
            console.log(JSON.stringify(response));
        },
        error: function(jqXHR) {
            console.log(jqXHR);
        }
    })
}