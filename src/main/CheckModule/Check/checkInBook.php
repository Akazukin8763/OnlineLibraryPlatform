<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $ID = $_POST["ID"];
        $book_ID = $_POST["book_ID"];
        
        $conn->autocommit(false);

        if ($ID != null && $book_ID != null &&
            is_int((int) $ID) && is_int((int) $book_ID)) {

            // 檢查書籍是否是可借閱（真實性存在於圖書館）
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

                if ($book_status != "BORROW") {
                    // 檢查有無懲處紀錄
                    $sql_punish = "SELECT punish_date
                                    FROM User_Punishment
                                    WHERE ID = ?";
                    $stmt = $conn->prepare($sql_punish); 
                    $stmt->bind_param("i", $ID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    if (count($rows) == 1) { // ID 唯一
                        $today = date("Y-m-d H:i:s");
                        $punish_date = $rows[0]["punish_date"];

                        if (strtotime($today) < strtotime($punish_date)) { // 尚未過懲處日期
                            $sql_delete_reserve = "DELETE FROM Book_Trace
                                                    WHERE book_ID = ? AND ID = ? AND deadline IS NULL";
                            $stmt = $conn->prepare($sql_delete_reserve); 
                            $stmt->bind_param("ii", $book_ID, $ID);
                            $result = $stmt->execute();

                            if ($result) {
                                echo json_encode(array('errorMsg' => '懲處日期（'.$punish_date.'）尚未結束，不可借閱，已清除預約。'));
                            }
                            else {
                                echo json_encode(array('errorMsg' => '懲處日期（'.$punish_date.'）尚未結束，不可借閱。'));
                            }
                            exit;
                        }
                    }
                    else { // 查不到表示沒有懲處，可預約
                        // Nothing
                    }

                    // 檢查有無他人事先預約
                    $sql_is_reserve = "SELECT *
                                        FROM Book_Trace
                                        WHERE book_ID = ? AND deadline IS NULL
                                        ORDER BY start_date ASC";
                    $stmt = $conn->prepare($sql_is_reserve); 
                    $stmt->bind_param("i", $book_ID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    $start_date = date("Y-m-d H:i:s");
                    $deadline = date("Y-m-d H:i:s", strtotime("+14 day"));  // 借閱 14 日

                    if (count($rows) != 0) {
                        $reserve_ID = $rows[0]["ID"];

                        if ($reserve_ID != $ID) { // 表示有他人在自己之前事先預約，不可借閱
                            echo json_encode(array('errorMsg' => '已有人在此之前先預約此書，不可借閱。'));
                            exit;
                        }
                        else { // 自己是最先預約的，可借閱（直接更新資料庫）
                            // 借書
                            $sql_checkIn = "UPDATE Book_Trace
                                            SET start_date = ?, deadline = ?
                                            WHERE book_ID = ? AND ID = ? AND deadline IS NULL";
                            $stmt = $conn->prepare($sql_checkIn); 
                            $stmt->bind_param("ssii", $start_date, $deadline, $book_ID, $ID);
                            $result = $stmt->execute();
        
                            if ($result && $stmt->affected_rows == 1) { // 更新日期成功，不做任何事
                                // Nothing
                            }
                            else {
                                $conn->rollback();
                                echo json_encode(array('errorMsg' => '借閱書籍時發生錯誤'));
                                exit;
                            }
                        }
                    }
                    else { // 查不到表示沒人預約，可借閱（新增資料庫）
                        // 借書
                        $sql_checkIn = "INSERT INTO Book_Trace (book_ID, ID, start_date, deadline)
                                        VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql_checkIn); 
                        $stmt->bind_param("iiss", $book_ID, $ID, $start_date, $deadline);
                        $result = $stmt->execute();
    
                        if ($result && $stmt->affected_rows == 1) { // 更新日期成功，不做任何事
                            // Nothing
                        }
                        else {
                            $conn->rollback();
                            echo json_encode(array('errorMsg' => '借閱書籍時發生錯誤'));
                            exit;
                        }
                    }

                    // 更新書籍狀態
                    $sql_status = "UPDATE Book
                                    SET book_status = 'BORROW'
                                    WHERE book_ID = ?";
                    $stmt = $conn->prepare($sql_status); 
                    $stmt->bind_param("i", $book_ID);
                    $result = $stmt->execute();

                    if ($result && $stmt->affected_rows == 1) {
                        $conn->commit();
                        echo json_encode(array('start_date' => $start_date, 
                                                'deadline' => $deadline));
                    }
                    else {
                        $conn->rollback();
                        echo json_encode(array('errorMsg' => '借閱書籍時發生錯誤'));
                    }
                }
                else {
                    echo json_encode(array('errorMsg' => '已有人借閱此書，實體理應不存在於圖書館中。'));
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