export function checkInBook(__ID, __book_ID) {
    
}

export function checkOutBook(__ID, __book_ID) {
    
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