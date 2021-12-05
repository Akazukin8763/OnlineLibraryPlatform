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
                    WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);

                $today = date("Y-m-d H:i:s");
                $punish_date = $row["punish_date"];
                
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
                    WHERE book_ID = '$book_ID'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $book_status = $row["book_status"]; // 現在書籍狀態

                // 是否已經預約過，借閱之後才會有 deadline，因此可以借閱後再次預約（處於借閱狀態時）
                $sql_is_reserve = "SELECT *
                                    FROM Book_Trace
                                    WHERE book_ID = '$book_ID' AND ID = '$ID' AND deadline IS NULL";
                $result_is_reserve = mysqli_query($conn, $sql_is_reserve);

                if (mysqli_num_rows($result_is_reserve) == 0) {
                    if ($book_status != "BORROW") { // IDLE, RESERVE
                        // 將狀態改成 RESERVE
                        $sql_book = "UPDATE Book
                                        SET book_status = 'RESERVE'
                                        WHERE book_ID = '$book_ID'";
                        $result_book = mysqli_query($conn, $sql_book);

                        if ($result_book) { 
                            // Nothing
                        }
                        else {
                            echo json_encode(array('__STATUS' => 'ERROR',
                                                'errorMsg' => '更新書籍狀態時發生錯誤。'));
                            exit;
                        }
                    }
                    else { // BORROW，不更新狀態
                        // Nothing
                    }

                    $today = date("Y-m-d H:i:s");

                    // 預約書籍，允許多人預約同本書，優先通知最早預約的使用者
                    $sql_book_reserve = "INSERT INTO Book_Trace (book_ID, ID, start_date)
                                            VALUES ('$book_ID', '$ID', '$today')";
                    $result_book_reserve = mysqli_query($conn, $sql_book_reserve);

                    if ($result_book_reserve) {
                        echo json_encode(array('__STATUS' => 'SUCCESS'));
                    }
                    else {
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