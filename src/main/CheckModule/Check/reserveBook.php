<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $book_ID = $_POST["book_ID"];

        $conn->autocommit(false);

        if ($ID != null && $book_ID != null &&
            is_int((int) $ID) && is_int((int) $book_ID)) {
            
            // 檢查有無懲處
            $sql = "SELECT punish_date
                    FROM User_Punishment
                    WHERE ID = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) {
                $today = date("Y-m-d H:i:s");
                $punish_date = $rows[0]["punish_date"];
                
                if (strtotime($today) < strtotime($punish_date)) {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '懲處日期（'.$punish_date.'）尚未結束，不可預約。'));
                    exit;
                }
            }
            else { // 查不到表示沒有懲處，可預約
                // Nothing
            }

            $sql = "SELECT book_status
                    FROM Book
                    WHERE book_ID = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $book_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) {
                $book_status = $rows[0]["book_status"]; // 現在書籍狀態

                // 是否已經預約過，借閱之後才會有 deadline，因此可以借閱後再次預約（處於借閱狀態時）
                $sql_is_reserve = "SELECT *
                                    FROM Book_Trace
                                    WHERE book_ID = ? AND ID = ? AND deadline IS NULL";
                $stmt_is_reserve = $conn->prepare($sql_is_reserve); 
                $stmt_is_reserve->bind_param("ii", $book_ID, $ID);
                $stmt_is_reserve->execute();
                $result_is_reserve = $stmt_is_reserve->get_result();
                $rows_is_reserve = $result_is_reserve->fetch_all(MYSQLI_ASSOC);

                if (count($rows_is_reserve) == 0) {
                    if ($book_status != "BORROW") { // IDLE, RESERVE
                        // 將狀態改成 RESERVE
                        $sql_book = "UPDATE Book
                                        SET book_status = 'RESERVE'
                                        WHERE book_ID = ?";
                        $stmt_book = $conn->prepare($sql_book); 
                        $stmt_book->bind_param("i", $book_ID);
                        $result_book = $stmt_book->execute();

                        if (!$result_book) {
                            $conn->rollback();
                            echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍狀態時發生錯誤。'));
                            exit;
                        }
                    }
                    else { // BORROW，不更新狀態
                        // Nothing
                    }

                    $today = date("Y-m-d H:i:s");

                    // 確認是否是第一順位預約，是則發通知
                    $sql_fisrt_reserve = "SELECT *
                                            FROM Book_Trace
                                            WHERE book_ID = ? AND deadline IS NULL";
                    $stmt_fisrt_reserve = $conn->prepare($sql_fisrt_reserve); 
                    $stmt_fisrt_reserve->bind_param("i", $book_ID);
                    $stmt_fisrt_reserve->execute();
                    $result_fisrt_reserve = $stmt_fisrt_reserve->get_result();
                    $rows_fisrt_reserve = $result_fisrt_reserve->fetch_all(MYSQLI_ASSOC);

                    $notify_date;
                    if (count($rows_fisrt_reserve) != 0) { // 不是第一順位，不發通知
                        // Nothing
                    }
                    else { // 第一順位，通知
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
                            echo json_encode(array('__STATUS' => 'ERROR',
                                                    'errorMsg' => '查無書籍資料。'));
                            exit;
                        }

                        $notify_date = $today;
                        $content = "成功預約書籍「".$title."（book_ID：".$book_ID."）」於第一順位，需在 3 日內前去圖書館借閱，否則取消預約資格。";
                        
                        // 通知
                        $sql_notify = "INSERT INTO Notification (ID, notify_date, content)
                                            VALUES (?, ?, ?)";
                        $stmt_notify = $conn->prepare($sql_notify);
                        $stmt_notify->bind_param("iss", $ID, $notify_date, $content);
                        $result_notify = $stmt_notify->execute();

                        if ($result_notify && $stmt_notify->affected_rows == 1) {
                            // Nothing
                        }
                        else {
                            $conn->rollback();
                            echo json_encode(array('__STATUS' => 'ERROR',
                                                    'errorMsg' => '傳送通知時發生錯誤。'));
                            exit;
                        }
                    }

                    // 預約書籍，允許多人預約同本書，優先通知最早預約的使用者
                    $sql_book_reserve = "INSERT INTO Book_Trace (book_ID, ID, start_date, notify_date)
                                            VALUES (?, ?, ?, ?)";
                    $stmt_book_reserve = $conn->prepare($sql_book_reserve); 
                    $stmt_book_reserve->bind_param("iiss", $book_ID, $ID, $today, $notify_date);
                    $result_book_reserve = $stmt_book_reserve->execute();

                    if ($result_book_reserve && $stmt_book_reserve->affected_rows == 1) {
                        $conn->commit();
                        echo json_encode(array('__STATUS' => 'SUCCESS'));
                    }
                    else {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '預約書籍時發生錯誤。'));
                    }
                }
                else {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '已預約此書籍，不可再次預約'));
                }
            }
            else {
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '查無書籍資料。'));
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