<?php
    // Include config file
    $conn = require_once "../../config.php";

    class History {
        public $book_ID;
        public $title;
        public $image;
        public $start_date;
        public $end_date;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];

        if ($ID != null &&
            is_int((int) $ID)) {
            
            $sql = "SELECT *
                    FROM Book_Trace NATURAL JOIN Book NATURAL JOIN Book_Detail
                    WHERE ID = ? AND end_date IS NOT NULL ORDER BY end_date DESC";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $data = array();
                foreach($rows as $row) {
                    // 歷史紀錄
                    $history = new History();
                    
                    $history->book_ID = $row["book_ID"];
                    $history->title = $row["title"];
                    $history->image = $row["image"];
                    $history->start_date = $row["start_date"];
                    $history->end_date = $row["end_date"];
                    
                    $data[] = $history;
                }

                echo json_encode(array('result' => $data));
            }
            else {
                echo json_encode(array('errorMsg' => '查無歷史紀錄。'));
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