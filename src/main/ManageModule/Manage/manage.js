export function uploadBook(__title, __image, __author, __publisher, __description, __publish_date, __arrive_date, __category) {
    if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
        return;
    }
    else if (!(__image.length <= 256)) {
        console.log('__image 長度超出限制。');
        return;
    }
    else if (!(__author.length <= 64)) {
        console.log('__author 長度超出限制。');
        return;
    }
    else if (!(__publisher.length <= 64)) {
        console.log('__publisher 長度超出限制。');
        return;
    }
    else if (!(__description.length <= 512)) {
        console.log('__description 長度超出限制。');
        return;
    }
    else if (isNaN(Date.parse(__publish_date))) {
        console.log('__publish_date 並非標準日期和時間格式字串。');
        return;
    }
    else if (isNaN(Date.parse(__arrive_date))) {
        console.log('__arrive_date 並非標準日期和時間格式字串。');
        return;
    }
    else if (!Array.isArray(__category)) {
        console.log('__category 並非陣列。');
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
                console.log(response.book_ID);
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
    if (!(__old_title.length <= 64)) {
        console.log('__old_title 長度超出限制。');
        return;
    }
    else if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
        return;
    }
    else if (!(__image.length <= 256)) {
        console.log('__image 長度超出限制。');
        return;
    }
    else if (!(__author.length <= 64)) {
        console.log('__author 長度超出限制。');
        return;
    }
    else if (!(__publisher.length <= 64)) {
        console.log('__publisher 長度超出限制。');
        return;
    }
    else if (!(__description.length <= 512)) {
        console.log('__description 長度超出限制。');
        return;
    }
    else if (isNaN(Date.parse(__publish_date))) {
        console.log('__publish_date 並非標準日期和時間格式字串。');
        return;
    }
    else if (isNaN(Date.parse(__arrive_date))) {
        console.log('__arrive_date 並非標準日期和時間格式字串。');
        return;
    }
    else if (!Array.isArray(__category)) {
        console.log('__category 並非陣列。');
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