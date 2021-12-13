export function viewStatus() {
    $.ajax({
        type: "POST",
        url: "SearchViewModule/View/viewStatus.php",
        dataType: "json",
        data: {},
        success: function(response) {
            if (response.result) { // 回傳的 json 中含有 result
                response.result.forEach(book => {
                    console.log("book_ID: " + book.book_ID + ", title: " + book.title + ", image: " + book.image + ", book_status: " + book.book_status + ", order: " + book.order + ", deadline: " + book.deadline);
                });
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

export function viewHistory() {
    $.ajax({
        type: "POST",
        url: "SearchViewModule/View/viewHistory.php",
        dataType: "json",
        data: {},
        success: function(response) {
            if (response.result) { // 回傳的 json 中含有 result
                response.result.forEach(book => {
                    console.log("book_ID: " + book.book_ID + ", title: " + book.title + ", image: " + book.image + ", start_date: " + book.start_date + ", end_date: " + book.end_date);
                });
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

export function viewFavorite() {
    $.ajax({
        type: "POST",
        url: "SearchViewModule/View/viewFavorite.php",
        dataType: "json",
        data: {},
        success: function(response) {
            if (response.result) { // 回傳的 json 中含有 result
                response.result.forEach(folders => {
                    console.log("folders: " + folders.folder);

                    folders.content.forEach(content => {
                        console.log("title: " + content.title + ", image: " + content.image + ", score: " + content.score + ", comment: " + content.comment);
                    });
                });
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

export function viewNotification() {
    $.ajax({
        type: "POST",
        url: "SearchViewModule/View/viewNotification.php",
        dataType: "json",
        data: {},
        success: function(response) {
            if (response.result) { // 回傳的 json 中含有 result
                response.result.forEach(book => {
                    console.log("notify_date: " + book.notify_date + ", content: " + book.content);
                });
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

export function viewTrace() {
    $.ajax({
        type: "POST",
        url: "SearchViewModule/View/viewTrace.php",
        dataType: "json",
        data: {},
        success: function(response) {
            if (response.result) { // 回傳的 json 中含有 result
                response.result.forEach(book => {
                    console.log("title: " + book.title + ", book_status: " + book.book_status);
                });
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

export function traceBook(__title) {
    if (!(0 < __title.length && __title.length <= 64)) {
        console.log('__title 長度超出限制。');
        return;
    }
    
    $.ajax({
        type: "POST",
        url: "SearchViewModule/View/traceBook.php",
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