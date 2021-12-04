export function uploadBook(__title, __image, __author, __publisher, __description, __publish_date, __arrive_date, __category) {
    if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
        return;
    }
    else if (!(__image.length <= 256)) {
        console.log('__image 長度超出限制。');
        return;
    }
    else if (!(__author.length <= 16)) {
        console.log('__author 長度超出限制。');
        return;
    }
    else if (!(__publisher.length <= 16)) {
        console.log('__publisher 長度超出限制。');
        return;
    }
    else if (!(__description.length <= 256)) {
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