export function checkInBook(__ID, __book_ID) {
    if (isNaN(parseInt(__ID))) {
        console.log('__ID 並非整數。');
        return;
    }
    else if (isNaN(parseInt(__book_ID))) {
        console.log('__book_ID 並非整數。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "CheckModule/Check/checkInBook.php",
        dataType: "json",
        data: {
            ID: __ID,
            book_ID: __book_ID,
        },
        success: function(response) {
            if (response.start_date && response.deadline) { // 回傳的 json 中含有 start_date, deadline
                console.log(response.start_date + ": " + response.deadline);
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

export function checkOutBook(__ID, __book_ID) {
    if (isNaN(parseInt(__ID))) {
        console.log('__ID 並非整數。');
        return;
    }
    else if (isNaN(parseInt(__book_ID))) {
        console.log('__book_ID 並非整數。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "CheckModule/Check/checkOutBook.php",
        dataType: "json",
        data: {
            ID: __ID,
            book_ID: __book_ID,
        },
        success: function(response) {
            if (response.start_date && response.end_date && response.deadline) { // 回傳的 json 中含有 start_date, end_date, deadline, (punish_date 可能為 null，表示沒有逾期)
                console.log(response.start_date);
                console.log(response.end_date);
                console.log(response.deadline);
                console.log(response.punish_date);
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

export function reserveBook(__book_ID) {
    if (isNaN(parseInt(__book_ID))) {
        console.log('__book_ID 並非整數。');
        return;
    }

    $.ajax({
        type: "POST",
        url: "CheckModule/Check/reserveBook.php",
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