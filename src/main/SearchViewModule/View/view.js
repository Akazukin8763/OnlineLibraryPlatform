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
                        console.log("title: " + content.title + ", image: " + content.image + ", score: " + content.score + ", comment: " + content.comment + ", times: " + content.times);
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

export function traceBook() {

}