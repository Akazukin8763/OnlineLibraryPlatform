<?php
    function check_deadline($conn, $ID) {
        $today = date("Y-m-d H:i:s");
        
        $sql_deadline = "SELECT *
                        FROM Book_Trace
                        WHERE ID = ?  AND end_date IS NULL AND deadline IS NOT NULL AND deadline < ?";
        $stmt_deadline = $conn->prepare($sql_deadline); 
        $stmt_deadline->bind_param("is", $ID, $today);
        $stmt_deadline->execute();
        $result_deadline = $stmt_deadline->get_result();
        $rows_deadline = $result_deadline->fetch_all(MYSQLI_ASSOC);

        if (count($rows_deadline) != 0) { // 逾時
            // 通知使用者逾時
            $sql_over_time = "INSERT INTO Notification (ID, notify_date, content)
                                VALUES (?, ?, ?)";
            $stmt_over_time = $conn->prepare($sql_over_time);

            foreach($rows_deadline as $row_deadline) {
                $book_ID = $row_deadline["book_ID"];
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

                // 通知使用者逾時
                $content = "您借閱的書籍「".$title."（book_ID：".$book_ID."）」已逾時，請盡快歸還。";
                
                $stmt_over_time->bind_param("iss", $ID, $today, $content);
                $result_over_time = $stmt_over_time->execute();
    
                if ($result_over_time && $stmt_over_time->affected_rows == 1) {
                    // Nothing
                }
                else {
                    $conn->rollback();
                    return false;
                }
            }
        }
        return true;
    }
?>