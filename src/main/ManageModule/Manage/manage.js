export function uploadBook(__title, __image, __author, __publisher, __description, __publish_date, __arrive_date, __category) {
    if (!(0 < __title.length && __title.length <= 64)) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadTitleERR").offset().top }, 500);
        if (!(0 < __title.length)) $("#uploadTitleERR").html("請輸入書籍名稱。");
        else $("#uploadTitleERR").html("長度需小於 64 個字元。");
        $("#uploadTitle").focus();
        return;
    }
    else if (!(0 < __image.length && __image.length <= 256)) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadImageERR").offset().top }, 500);
        if (!(0 < __image.length)) $("#uploadImageERR").html("請輸入圖片連結。");
        else $("#uploadImageERR").html("長度需小於 256 個字元。");
        $("#uploadImage").focus();
        return;
    }
    else if (!(0 < __author.length && __author.length <= 64)) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadAuthorERR").offset().top }, 500);
        if (!(0 < __author.length)) $("#uploadAuthorERR").html("請輸入作者名稱。");
        else $("#uploadAuthorERR").html("長度需小於 64 個字元。");
        $("#uploadAuthor").focus();
        return;
    }
    else if (!(0 < __publisher.length && __publisher.length <= 64)) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadPublisherERR").offset().top }, 500);
        if (!(0 < __publisher.length)) $("#uploadPublisherERR").html("請輸入出版商名稱。");
        else $("#uploadPublisherERR").html("長度需小於 64 個字元。");
        $("#uploadPublisher").focus();
        return;
    }
    else if (!(0 < __description.length && __description.length <= 512)) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadDescriptionERR").offset().top }, 500);
        if (!(0 < __description.length)) $("#uploadDescriptionERR").html("請輸入書籍簡介。");
        else $("#uploadDescriptionERR").html("長度需小於 512 個字元。");
        $("#uploadDescription").focus();
        return;
    }
    else if (isNaN(Date.parse(__publish_date))) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadPublishDateERR").offset().top }, 500);
        $("#uploadPublishDateERR").html("日期尚未填寫。");
        $("#uploadPublishDate").focus();
        return;
    }
    else if (isNaN(Date.parse(__arrive_date))) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadArriveDateERR").offset().top }, 500);
        $("#uploadArriveDateERR").html("日期尚未填寫。");
        $("#uploadArriveDate").focus();
        return;
    }
    else if (__category.length == 0) {
        $("#modalUploadBook").animate({ scrollTop: $("#uploadCategoryERR").offset().top }, 500);
        $("#uploadCategoryERR").html("請至少勾選一項類別。");
        $("#uploadCategory").focus();
        return;
    }

    $.ajax({
        type: "POST",
        url: "ManageModule/Manage/uploadBook.php",
        dataType: "json",
        data: {
            title: __title,
            image: __image,
            author: __author,
            publisher: __publisher,
            description: __description,
            publish_date: __publish_date,
            arrive_date: __arrive_date,
            category: __category,
        },
        success: function(response) {
            if (response.book_ID) { // 回傳的 json 中含有 book_ID
                $("#btn_closeUpload").trigger("click");
                $("#btn_clearUpload").trigger("click");

                $('#searchTitle').val('');
                $("#btn_searchBook").trigger("click");
            }
            else {
                //console.log(response.errorMsg);
                $("#uploadERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#uploadERR").html("伺服器連線錯誤。");
        }
    })
}

export function deleteBook(__book_ID) {
    if (isNaN(parseInt(__book_ID))) {
        console.log('__book_ID 並非整數。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "ManageModule/Manage/deleteBook.php",
        dataType: "json",
        data: {
            book_ID: __book_ID,
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

export function editBook(__old_title, __title, __image, __author, __publisher, __description, __publish_date, __arrive_date, __category) {
    if (!(0 < __title.length && __title.length <= 64)) {
        $("#modalEditBook").animate({ scrollTop: $("#editTitleERR").offset().top }, 500);
        if (!(0 < __title.length)) $("#editTitleERR").html("請輸入書籍名稱。");
        else $("#editTitleERR").html("長度需小於 64 個字元。");
        $("#editTitle").focus();
        return;
    }
    else if (!(0 < __image.length && __image.length <= 256)) {
        $("#modalEditBook").animate({ scrollTop: $("#editImageERR").offset().top }, 500);
        if (!(0 < __image.length)) $("#editImageERR").html("請輸入圖片連結。");
        else $("#editImageERR").html("長度需小於 256 個字元。");
        $("#editImage").focus();
        return;
    }
    else if (!(0 < __author.length && __author.length <= 64)) {
        $("#modalEditBook").animate({ scrollTop: $("#editAuthorERR").offset().top }, 500);
        if (!(0 < __author.length)) $("#editAuthorERR").html("請輸入作者名稱。");
        else $("#editAuthorERR").html("長度需小於 64 個字元。");
        $("#editAuthor").focus();
        return;
    }
    else if (!(0 < __publisher.length && __publisher.length <= 64)) {
        $("#modalEditBook").animate({ scrollTop: $("#editPublisherERR").offset().top }, 500);
        if (!(0 < __publisher.length)) $("#editPublisherERR").html("請輸入出版商名稱。");
        else $("#editPublisherERR").html("長度需小於 64 個字元。");
        $("#editPublisher").focus();
        return;
    }
    else if (!(0 < __description.length && __description.length <= 512)) {
        $("#modalEditBook").animate({ scrollTop: $("#editDescriptionERR").offset().top }, 500);
        if (!(0 < __description.length)) $("#editDescriptionERR").html("請輸入書籍簡介。");
        else $("#editDescriptionERR").html("長度需小於 512 個字元。");
        $("#editDescription").focus();
        return;
    }
    else if (isNaN(Date.parse(__publish_date))) {
        $("#modalEditBook").animate({ scrollTop: $("#editPublishDateERR").offset().top }, 500);
        $("#editPublishDateERR").html("日期尚未填寫。");
        $("#editPublishDate").focus();
        return;
    }
    else if (isNaN(Date.parse(__arrive_date))) {
        $("#modalEditBook").animate({ scrollTop: $("#editArriveDateERR").offset().top }, 500);
        $("#editArriveDateERR").html("日期尚未填寫。");
        $("#editArriveDate").focus();
        return;
    }
    else if (__category.length == 0) {
        $("#modalEditBook").animate({ scrollTop: $("#editCategoryERR").offset().top }, 500);
        $("#editCategoryERR").html("請至少勾選一項類別。");
        $("#editCategory").focus();
        return;
    }

    $.ajax({
        type: "POST",
        url: "ManageModule/Manage/editBook.php",
        dataType: "json",
        data: {
            old_title: __old_title,
            title: __title,
            image: __image,
            author: __author,
            publisher: __publisher,
            description: __description,
            publish_date: __publish_date,
            arrive_date: __arrive_date,
            category: __category,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    //console.log(response.__STATUS);
                    $("#btn_closeEdit").trigger("click");
                    $("#btn_searchBook").trigger("click");
                }
                else {
                    //console.log(response.__STATUS + ": " + response.errorMsg);
                    $("#editERR").html(response.errorMsg);
                }
            }
            else {
                //console.log(response.errorMsg);
                $("#editERR").html(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            //console.log(jqXHR);
            $("#editERR").html("伺服器連線錯誤。");
        }
    })
}