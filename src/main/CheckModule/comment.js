export function leaveComment(__title, __score, __comment) {
    if (!(__title.length <= 64)) {
        console.log('__title 長度超出限制。');
        return;
    }
    else if (isNaN(parseInt(__score))) {
        console.log('__score 並非整數。');
        return;
    }
    else if (!(0 <= parseInt(__score) && parseInt(__score) <= 10)) {
        console.log('__score 只能介於 0 至 10 之間。');
        return;
    }
    else if (!(__comment.length <= 256)) {
        console.log('__comment 長度超出限制。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "CheckModule/Comment/leaveComment.php",
        dataType: "json",
        data: {
            title: __title,
            score: __score,
            comment: __comment,
        },
        success: function(response) {
            if (response.__STATUS) { // 回傳的 json 中含有 __STATUS
                if (response.__STATUS == "SUCCESS") {
                    console.log(response.__STATUS);
                    alert("你已成功發表言論");
                    createInfo(bookData[0].title,[]);
                }
                else {
                    console.log(response.__STATUS + ": " + response.errorMsg);
                    alert(response.errorMsg);
                }
            }
            else {
                console.log(response.errorMsg);
                alert(response.errorMsg);
            }
        },
        error: function(jqXHR) {
            console.log(jqXHR);
        }
    })
}