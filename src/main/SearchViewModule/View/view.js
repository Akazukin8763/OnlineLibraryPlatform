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
                var favorite = $("#favoriteFolder");
                favorite.empty();

                var folderNums = 0;
                var f = $("#currentList");
                var l = $('<input id="currentListLength"></input>');
                f.empty();
                l.appendTo(f);

                response.result.forEach(folders => {
                    // thumbnail
                    var thumbnail = $('<div class="thumbnail"></div>');
                    
                    // folder
                    var fold = $('<label class="glyphicon glyphicon-minus" style="color: #9C9C9C;"></label>');
                    var folderName = $('<label></label>');
                    folderName.html("&nbsp;" + folders.folder);

                    var v = $('<input id="currentList'+ folderNums++ +'"></input>');
                    v.val(folders.folder);
                    v.appendTo(f);
                    l.val(folderNums);

                    // detail
                    var ul = $('<ul class="nav"></ul>');
                    folders.content.forEach(content => {
                        var li = $('<li style="padding-left: 2%;"></li>');
                        var span = $('<span></span>');

                        var edit = $('<label class="glyphicon glyphicon-cog" style="color: #9C9C9C;"></label>');
                        var title = $('<label></label>');
                        title.html("&nbsp;" + content.title);

                        edit.click(function() {
                            $("#modalEditList").modal("show");

                            $("#editListHeader").html("Edit：" + content.title);
                            $("#selectEdit").empty();
                            $("#editListERR").html("");

                            $("#editBookTitle").val(content.title);
                            $("#editBookFolder").val(folders.folder);

                            // Set Content
                            var status = $("#editStatus").val();
                            if (status == "update") {
                                var edit = $("#selectEdit");
                                edit.empty();

                                $('<label>Move \"' + $("#editBookTitle").val() + '\" from \"' + $("#editBookFolder").val() + '\" to&nbsp;</label>').appendTo(edit);
                
                                var length = $("#currentListLength").val();
                                var selection = $('<select id="selectListName"></select>');
                                for (let i = 0; i < length; i++) {
                                    var v = $('#currentList' + i + '').val();
                                    $('<option value="' + v + '">' + v + '</option>').appendTo(selection);
                                }
                                selection.appendTo(edit);
                            }
                            else if (status == "remove") {
                                var edit = $("#selectEdit");
                                edit.empty();
                                $('<label>Remove \"' + content.title + '\" from \"' + folders.folder + '\"</label>').appendTo(edit);
                            }
                            else if (status == "delete") {
                                var edit = $("#selectEdit");
                                edit.empty();
                                $('<label>Delete folder \"' + folders.folder + '\" and move all book to "DEFAULT"</label>').appendTo(edit);
                            }
                        });

                        edit.appendTo(span);
                        title.appendTo(span);
                        span.appendTo(li);
                        li.appendTo(ul);
                    });

                    // combine
                    fold.appendTo(thumbnail);
                    folderName.appendTo(thumbnail);
                    ul.appendTo(thumbnail);
                    thumbnail.appendTo(favorite);

                    let flag = true;
                    fold.click(function() {
                        if (flag) {
                            fold.removeClass("glyphicon-minus");
                            fold.addClass("glyphicon-plus");
                            ul.css({ "display": "none" });
                        }
                        else {
                            fold.removeClass("glyphicon-plus");
                            fold.addClass("glyphicon-minus");
                            ul.css({ "display": "block" });
                        }
                        flag = !flag;
                    });

                    /*console.log("folders: " + folders.folder);

                    folders.content.forEach(content => {
                        console.log("title: " + content.title + ", image: " + content.image + ", score: " + content.score + ", comment: " + content.comment);
                    });*/
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