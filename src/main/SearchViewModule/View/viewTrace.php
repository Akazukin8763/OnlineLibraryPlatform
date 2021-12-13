<?php
    // Include config file
    $conn = require_once "../../config.php";

    class Trace {
        public $title;
        public $book_status;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];

        if ($ID != null &&
            is_int((int) $ID)) {
            
            // 追蹤清單書名
            $sql = "SELECT title
                    FROM User_Trace
                    WHERE ID = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $data = array();
                foreach($rows as $row) {
                    $trace = new Trace();

                    $trace->title = $row["title"];

                    // 書籍狀態
                    $sql_status = "SELECT book_status
                                    FROM Book
                                    WHERE title = ?";
                    $stmt_status = $conn->prepare($sql_status); 
                    $stmt_status->bind_param("s", $row["title"]);
                    $stmt_status->execute();
                    $result_status = $stmt_status->get_result();
                    $rows_status = $result_status->fetch_all(MYSQLI_ASSOC);

                    $book_status = "BORROW"; // 預設為都被借閱
                    if (count($rows_status) != 0) {
                        foreach($rows_status as $row_status) { // IDLE > RESERVE > BORROW
                            if ($row_status["book_status"] == "BORROW")
                                continue;
                            else if ($row_status["book_status"] == "RESERVE" && $book_status == "IDLE")
                                continue;
                            $book_status = $row_status["book_status"];
                        }
                    }
                    else {
                        $book_status = "NONE";
                    }
                    $trace->book_status = $book_status;

                    $data[] = $trace;
                }

                echo json_encode(array('result' => $data));
            }
            else {
                echo json_encode(array('errorMsg' => '查無追蹤清單。'));
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