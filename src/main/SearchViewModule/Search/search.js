export function searchBook(__title, __category) {
    if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
        return;
    }
    else if (!Array.isArray(__category)) {
        console.log('__category 並非陣列。');
        return;
    }

    if (__title == '') { // 至少一個字元
        __title = '_';
    }
    if (__category.length == 0) { // 都沒有時則全選
        __category = ["action_and_adventure", "alternate_history", "anthology", "chick_lit", "children",
                        "classic", "comic_book", "coming_of_age", "crime", "drama",
                        "fairytale", "fantasy", "graphic_novel", "historical_fiction", "horror",
                        "mystery", "paranormal_romance", "picture_book", "poetry", "political_thriller",
                        "romance", "satire", "science_fiction", "short_story", "suspense",
                        "thriller", "western", "young_adult"];
    }

    $.ajax({
        type: "GET",
        url: "SearchViewModule/Search/searchBook.php",
        dataType: "json",
        data: {
            title: __title,
            category: __category,
        },
        success: function(response) {
            if (response.result) { // 回傳的 json 中含有 result
                response.result.forEach(book => {
                    console.log("title: " + book.title + ", category: " + book.category);
                    console.log("author: " + book.author + ", publisher: " + book.publisher + ", description: " + book.description);
                    console.log("publish_date: " + book.arrive_date + ", arrive_date: " + book.arrive_date);

                    book.books.forEach(bs => {
                        console.log("book_ID: " + bs.book_ID + ", book_status: " + bs.book_status);
                    });
                    book.comments.forEach(bc => {
                        console.log("username: " + bc.username + ", score: " + bc.score + ", comment: " + bc.comment + ", comment_date: " + bc.comment_date + ", times: " + bc.times);
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