<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $book_ID = $_POST["book_ID"];
        
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
                    $conn->autocommit(false);

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

                    // 預約書籍，允許多人預約同本書，優先通知最早預約的使用者
                    $sql_book_reserve = "INSERT INTO Book_Trace (book_ID, ID, start_date)
                                            VALUES (?, ?, ?)";
                    $stmt_book_reserve = $conn->prepare($sql_book_reserve); 
                    $stmt_book_reserve->bind_param("iis", $book_ID, $ID, $today);
                    $result_book_reserve = $stmt_book_reserve->execute();

                    if ($result_book_reserve) {
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