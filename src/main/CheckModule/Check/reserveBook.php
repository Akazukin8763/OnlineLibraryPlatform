<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $book_ID = $_POST["book_ID"];
        
        if ($ID != null && $book_ID != null &&
            is_int((int) $ID) && is_int((int) $book_ID)) {
            
            $sql = "SELECT book_status
                    FROM Book
                    WHERE book_ID = '$book_ID'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);

                if ($row["book_status"] == "IDLE") { // 只有閒置才能預約，一次只能有一人預約
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

                    $today = date("Y-m-d H:i:s");

                    // 預約書籍
                    $sql_book = "UPDATE Book
                                    SET book_status = 'RESERVE'
                                    WHERE book_ID = '$book_ID'";
                    $sql_book_trace = "INSERT INTO Book_Trace (book_ID, ID, start_date)
                                        VALUES ('$book_ID', '$ID', '$today')";

                    $result_book = mysqli_query($conn, $sql_book);
                    $result_book_trace = mysqli_query($conn, $sql_book_trace);

                    if ($result_book && $result_book_trace) {
                        echo json_encode(array('__STATUS' => 'SUCCESS'));
                    }
                    else {
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '預約書籍時發生錯誤。'));
                    }
                }
                else {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '書籍並非閒置狀態，不可預約。'));
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