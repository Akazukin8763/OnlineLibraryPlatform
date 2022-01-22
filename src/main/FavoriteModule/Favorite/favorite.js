export function insertFavorite(__title) {
    if (!(0 < __title.length && __title.length <= 64)) {
        if (!(0 < __title.length)) $("#favoriteBookERR").html("請輸入書籍名稱。");
        else $("#favoriteBookERR").html("長度需小於 64 個字元。");
        return;
    }

    $.ajax({
        type: "POST",
        url: "FavoriteModule/Favorite/insertFavorite.php",
        dataType: "json",
        data: {
            title: __title,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    //console.log(response.__STATUS);
                    $("#btn_searchBook").trigger("click");
                }
                else {
                    //console.log(response.__STATUS + ": " + response.errorMsg);
                    $("#favoriteBookERR").html(response.errorMsg);
                }
            }
            else {
                //console.log(response.errorMsg);
                $("#favoriteBookERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#favoriteBookERR").html("伺服器連線錯誤。");
        }
    })
}

export function removeFavorite(__title) {
    // 基本上不太可能跳出這錯誤
    if (!(0 < __title.length && __title.length <= 64)) {
        if (!(0 < __title.length)) $("#editListERR").html("請輸入書籍名稱。");
        else $("#editListERR").html("長度需小於 64 個字元。");
        return;
    }

    $.ajax({
        type: "POST",
        url: "FavoriteModule/Favorite/removeFavorite.php",
        dataType: "json",
        data: {
            title: __title,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    //console.log(response.__STATUS);
                    $("#btn_searchBook").trigger("click");
                    $("#modalEditList").modal("hide");
                }
                else {
                    //console.log(response.__STATUS + ": " + response.errorMsg);
                    $("#editListERR").html(response.errorMsg);
                }
            }
            else {
                //console.log(response.errorMsg);
                $("#editListERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#editListERR").html("伺服器連線錯誤。");
        }
    })
}

export function createFavoriteList(__folder) {
    
}

export function deleteFavoriteList(__folder) {
    // 基本上不太可能跳出這錯誤
    if (!(0 < __folder.length && __folder.length <= 64)) {
        if (!(0 < __folder.length)) $("#editListERR").html("請輸入資料夾名稱。");
        else $("#editListERR").html("長度需小於 64 個字元。");
        return;
    }

    $.ajax({
        type: "POST",
        url: "FavoriteModule/Favorite/deleteFavoriteList.php",
        dataType: "json",
        data: {
            folder: __folder,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    //console.log(response.__STATUS);
                    $("#btn_searchBook").trigger("click");
                    $("#modalEditList").modal("hide");
                }
                else {
                    //console.log(response.__STATUS + ": " + response.errorMsg);
                    $("#editListERR").html(response.errorMsg);
                }
            }
            else {
                //console.log(response.errorMsg);
                $("#editListERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#editListERR").html("伺服器連線錯誤。");
        }
    })
}

export function updateFavorite(__title, __folder) {
    // 基本上不太可能跳出這錯誤
    if (!(0 < __title.length && __title.length <= 64)) {
        if (!(0 < __title.length)) $("#editListERR").html("請輸入書籍名稱。");
        else $("#editListERR").html("長度需小於 64 個字元。");
        return;
    }
    if (!(0 < __folder.length && __folder.length <= 64)) {
        if (!(0 < __folder.length)) $("#editListERR").html("請輸入資料夾名稱。");
        else $("#editListERR").html("長度需小於 64 個字元。");
        return;
    }

    $.ajax({
        type: "POST",
        url: "FavoriteModule/Favorite/updateFavorite.php",
        dataType: "json",
        data: {
            title: __title,
            folder: __folder,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    //console.log(response.__STATUS);
                    $("#btn_searchBook").trigger("click");
                    $("#modalEditList").modal("hide");
                }
                else {
                    //console.log(response.__STATUS + ": " + response.errorMsg);
                    $("#editListERR").html(response.errorMsg);
                }
            }
            else {
                //console.log(response.errorMsg);
                $("#editListERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#editListERR").html("伺服器連線錯誤。");
        }
    })
}