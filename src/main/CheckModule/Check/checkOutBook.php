<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $ID = $_POST["ID"];
        $book_ID = $_POST["book_ID"];
        
        $conn->autocommit(false);

        if ($ID != null && $book_ID != null &&
            is_int((int) $ID) && is_int((int) $book_ID)) {

            // 檢查書籍是否是可歸還
            $sql_check = "SELECT book_status
                            FROM Book
                            WHERE book_ID = ?";
            $stmt = $conn->prepare($sql_check); 
            $stmt->bind_param("i", $book_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) {
                $book_status = $rows[0]["book_status"];

                if ($book_status == "BORROW") {
                    // 檢查是否是本人借閱，且尚未歸還
                    $sql_is_borrow = "SELECT *
                                        FROM Book_Trace
                                        WHERE book_ID = ? AND ID = ? AND end_date IS NULL AND deadline IS NOT NULL";
                    $stmt = $conn->prepare($sql_is_borrow); 
                    $stmt->bind_param("ii", $book_ID, $ID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    if (count($rows) != 0) { // 基本上只為 1
                        $today = date("Y-m-d H:i:s");

                        $start_date = $rows[0]["start_date"];
                        $end_date = $today;
                        $deadline = $rows[0]["deadline"];
                        $punish_date;

                        if (strtotime($end_date) < strtotime($deadline)) { // 尚未逾期
                            // Nothing，你很棒
                        }
                        else { // 逾期
                            // 查詢懲處紀錄
                            $sql_punish = "SELECT punish_date
                                            FROM User_Punishment
                                            WHERE ID = ?";
                            $stmt = $conn->prepare($sql_punish); 
                            $stmt->bind_param("i", $ID);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $rows = $result->fetch_all(MYSQLI_ASSOC);

                            if (count($rows) == 1) { // 有過懲處紀錄
                                if (strtotime($rows[0]["punish_date"]) < strtotime($today)) { // 懲處紀錄已經過期
                                    $punish_date = date("Y-m-d H:i:s", strtotime("+7 day")); // 懲處 7 日
                                }
                                else { // 懲處紀錄尚未過期
                                    $punish = $rows[0]["punish_date"]."+7 day";
                                    $punish_date = date("Y-m-d H:i:s", strtotime($punish)); // 額外懲處 7 日
                                }
                            }
                            else { // 沒有懲處紀錄
                                $punish_date = date("Y-m-d H:i:s", strtotime("+7 day")); // 懲處 7 日
                            }

                            // 更新懲處日期
                            $sql_punish = "INSERT INTO User_Punishment (ID, punish_date)
                                            VALUES (?, ?)
                                            ON DUPLICATE KEY UPDATE ID = ?, punish_date = ?";
                            $stmt = $conn->prepare($sql_punish); 
                            $stmt->bind_param("isis", $ID, $punish_date, $ID, $punish_date);
                            $result = $stmt->execute();

                            if ($result && $stmt->affected_rows == 1) {
                                // Nothing
                            }
                            else {
                                $conn->rollback();
                                echo json_encode(array('errorMsg' => '更新懲處時發生錯誤。'));
                                exit;
                            }
                        }

                        // 更新還書日期
                        $sql_checkOut = "UPDATE Book_Trace
                                            SET end_date = ?
                                            WHERE book_ID = ? AND ID = ? AND end_date IS NULL AND deadline IS NOT NULL";      
                        $stmt = $conn->prepare($sql_checkOut); 
                        $stmt->bind_param("sii", $end_date, $book_ID, $ID);
                        $result = $stmt->execute();

                        if ($result && $stmt->affected_rows == 1) {
                            // 檢查後續有無人預約
                            $sql_is_reserve = "SELECT *
                                                FROM Book_Trace
                                                WHERE book_ID = ? AND deadline IS NULL
                                                ORDER BY start_date ASC";
                            $stmt = $conn->prepare($sql_is_reserve); 
                            $stmt->bind_param("i", $book_ID);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $rows = $result->fetch_all(MYSQLI_ASSOC);
                            
                            $new_book_status = 'IDLE';
                            if (count($rows) != 0) { // 有人預約
                                $new_book_status = 'RESERVE';

                                // 查詢書籍名稱
                                $sql_title = "SELECT title
                                                FROM Book
                                                WHERE book_ID = ?";
                                $stmt_title = $conn->prepare($sql_title); 
                                $stmt_title->bind_param("i", $book_ID);
                                $stmt_title->execute();
                                $result_title = $stmt_title->get_result();
                                $rows_title = $result_title->fetch_all(MYSQLI_ASSOC);

                                $content;
                                if (count($rows_title) == 1) { // 找的到書名
                                    $content = "預約的書籍「書籍編號：".$book_ID."、書籍名稱：".$rows_title[0]["title"]."」已被歸還，可以前去圖書館借閱";
                                }
                                else { // 找不到書名（理論上不可能）
                                    $content = "預約的書籍「書籍編號：".$book_ID."」已被歸還，可以前去圖書館借閱";
                                }

                                // 通知下一位預約者
                                $sql_notify_reserve = "INSERT INTO Notification (ID, book_ID, notify_date, content)
                                                        VALUES (?, ?, ?, ?)";         
                                $stmt = $conn->prepare($sql_notify_reserve); 
                                $stmt->bind_param("iiss", $rows[0]["ID"], $book_ID, $today, $content);
                                $result = $stmt->execute();

                                if ($result && $stmt->affected_rows == 1) { // 通知成功
                                    // Nothing
                                }
                                else { // 通知失敗
                                    $conn->rollback();
                                    echo json_encode(array('errorMsg' => '傳送通知時發生錯誤。'));
                                    exit;
                                }
                            }
                            else { // 沒人預約
                                $new_book_status = 'IDLE';
                            }

                            $sql_book_status = "UPDATE Book
                                                SET book_status = ?
                                                WHERE book_ID = ?";
                            $stmt = $conn->prepare($sql_book_status); 
                            $stmt->bind_param("si", $new_book_status, $book_ID);
                            $result = $stmt->execute();

                            if ($result && $stmt->affected_rows == 1) {
                                $conn->commit();
                                echo json_encode(array('start_date' => $start_date,
                                                        'end_date' => $end_date,
                                                        'deadline' => $deadline,
                                                        'punish_date' => $punish_date));
                            }
                            else {
                                $conn->rollback();
                                echo json_encode(array('errorMsg' => '更新書籍狀態時發生錯誤。'));
                            }
                        }
                        else {
                            $conn->rollback();
                            echo json_encode(array('errorMsg' => '歸還書籍時發生錯誤。'));
                        }
                    }
                    else {
                        echo json_encode(array('errorMsg' => '使用者並未借用此書，無法歸還。'));
                    }
                }
                else {
                    echo json_encode(array('errorMsg' => '此書尚未被借閱，實體理應存在於圖書館中。'));
                }
            }
            else {
                echo json_encode(array('errorMsg' => '查無書籍資料。'));
            }
        }
        else {
            echo json_encode(array('errorMsg' => '參數輸入錯誤！'));
        }
    }
    else {
        echo json_encode(array('errorMsg' => '請求無效，只允許 POST 方式訪問！'));
    }

    // Close connection
    mysqli_close($conn);
?>