<?php
    // 確認是否預約被通知後超過 3 日
    // X~~ < 以下方式邏輯正確，但已改為其他方式，留此作紀錄 > ~~X
    // X~~ 需確認兩件事： ~~X
    // X~~ 1. 書籍狀態是否是 RESERVE，這代表現在可能是這名使用者預約導致的狀況，若為 BORROW 理應不該有超過期限的限制 ~~X
    // X~~ 2. 使用者是不是目前最早預約的人，若不是則代表有 3 日限制的使用者不是自己 ~~X
    function check_reserve($conn, $ID) {
        $today = date("Y-m-d H:i:s");
        $over_notify_date = date("Y-m-d H:i:s", strtotime("-3 day")); // 通知的那天距離現在超過 3 日
        $notify_date = date("Y-m-d H:i:s", strtotime("+3 day")); // 通知下一位預約者的日期

        $sql_reserve = "SELECT *
                        FROM Book_Trace
                        WHERE ID = ? AND deadline IS NULL AND notify_date IS NOT NULL AND notify_date < ?";
        $stmt_reserve = $conn->prepare($sql_reserve); 
        $stmt_reserve->bind_param("is", $ID, $over_notify_date);
        $stmt_reserve->execute();
        $result_reserve = $stmt_reserve->get_result();
        $rows_reserve = $result_reserve->fetch_all(MYSQLI_ASSOC);

        if (count($rows_reserve) != 0) { // 表示有被通知去借書，但是超過 3 日
            // 刪除預約
            $sql_delete_reserve = "DELETE FROM Book_Trace
                                    WHERE ID = ? AND book_ID = ? AND deadline IS NULL";
            $stmt_delete_reserve = $conn->prepare($sql_delete_reserve); 

            // 通知使用者預約超時未借閱
            $sql_over_time = "INSERT INTO Notification (ID, notify_date, content)
                                VALUES (?, ?, ?)";
            $stmt_over_time = $conn->prepare($sql_over_time);

            // 通知下一位預約者
            $sql_next_reserve = "SELECT *
                                    FROM Book_Trace
                                    WHERE book_ID = ? AND deadline IS NULL
                                    ORDER BY start_date ASC";
            $stmt_next_reserve = $conn->prepare($sql_next_reserve); 

            foreach($rows_reserve as $row_reserve) {
                $book_ID = $row_reserve["book_ID"];
                $title;

                // 查詢書籍名稱
                $sql_title = "SELECT title
                                FROM Book
                                WHERE book_ID = ?";
                $stmt_title = $conn->prepare($sql_title); 
                $stmt_title->bind_param("i", $book_ID);
                $stmt_title->execute();
                $result_title = $stmt_title->get_result();
                $rows_title = $result_title->fetch_all(MYSQLI_ASSOC);

                if (count($rows_title) == 1) { // 找的到書名
                    $title = $rows_title[0]["title"];
                }
                else { // 找不到書名（理論上不可能）
                    $conn->rollback();
                    return false;
                }

                // 刪除預約
                $stmt_delete_reserve->bind_param("ii", $ID, $book_ID);
                $result_delete_reserve = $stmt_delete_reserve->execute();

                if ($result_delete_reserve && $stmt_delete_reserve->affected_rows == 1) {
                    // Nothing
                }
                else {
                    $conn->rollback();
                    return false;
                }

                // 通知使用者預約超時未借閱
                $content = "您預約的書籍「".$title."（book_ID：".$book_ID."）」已超過 3 日未去圖書館借閱，已取消預約資格。";
                
                $stmt_over_time->bind_param("iss", $ID, $today, $content);
                $result_over_time = $stmt_over_time->execute();

                if ($result_over_time && $stmt_over_time->affected_rows == 1) {
                    // Nothing
                }
                else {
                    $conn->rollback();
                    return false;
                }

                // 通知下一位預約者
                $stmt_next_reserve->bind_param("i", $book_ID);
                $stmt_next_reserve->execute();
                $result_next_reserve = $stmt_next_reserve->get_result();
                $rows_next_reserve = $result_next_reserve->fetch_all(MYSQLI_ASSOC);

                if (count($rows_next_reserve) != 0) { // 存在下一位預約者
                    $next_ID = $rows_next_reserve[0]["ID"];
                    $content = "您預約的書籍「".$title."（book_ID：".$book_ID."）」已被歸還，需在 3 日內前去圖書館借閱，否則取消預約資格。";
                    
                    // 通知下一位預約者
                    $sql_notify_reserve = "INSERT INTO Notification (ID, notify_date, content)
                                            VALUES (?, ?, ?)";         
                    $stmt_notify_reserve = $conn->prepare($sql_notify_reserve); 
                    $stmt_notify_reserve->bind_param("iss", $next_ID, $today, $content);
                    $result_notify_reserve = $stmt_notify_reserve->execute();

                    // 紀錄通知期限
                    $sql_notify_date = "UPDATE Book_Trace
                                        SET notify_date = ?
                                        WHERE ID = ? AND book_ID = ? AND deadline IS NULL AND notify_date IS NULL";
                    $stmt_notify_date = $conn->prepare($sql_notify_date);
                    $stmt_notify_date->bind_param("sii", $notify_date, $next_ID, $book_ID);
                    $result_notify_date = $stmt_notify_date->execute();

                    if ($result_notify_reserve && $result_notify_date &&
                        $stmt_notify_reserve->affected_rows == 1 && $stmt_notify_date->affected_rows == 1) {
                        // Nothing
                    }
                    else {
                        $conn->rollback();
                        return false;
                    }
                }
                else { // 不存在下一位使用者，改為 IDLE
                    $sql_book_status = "UPDATE Book
                                        SET book_status = 'IDLE'
                                        WHERE book_ID = ?";
                    $stmt_book_status = $conn->prepare($sql_book_status);
                    $stmt_book_status->bind_param("i", $book_ID);
                    $result_book_status = $stmt_book_status->execute();

                    if ($result_book_status && $stmt_book_status->affected_rows == 1) {
                        // Nothing
                    }
                    else {
                        $conn->rollback();
                        return false;
                    }
                }
            }
        }
        return true;
    }
?>