export function viewStatus() {

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

}

export function viewNotification() {

}

export function traceBook() {

}