export function insertFavorite(__title) {
    if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
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

export function removeFavorite(__title) {
    if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
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

export function createFavoriteList(__folder) {
    
}

export function deleteFavoriteList(__folder) {
    if (!(__folder.length <= 16)) {
        console.log('__folder 長度超出限制。');
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

export function updateFavorite(__title, __folder) {

}