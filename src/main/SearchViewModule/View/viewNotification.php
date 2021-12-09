<?php
    // Include config file
    $conn = require_once "../../config.php";

    class Notification {
        public $book_ID;
        public $title;
        public $notify_date;
        public $content;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];

        if ($ID != null &&
            is_int((int) $ID)) {
            
            $sql = "SELECT *
                    FROM Notification NATURAL JOIN Book
                    WHERE ID = ?
                    ORDER BY notify_date DESC";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $data = array();
                foreach($rows as $row) {
                    // 所有通知
                    $notification = new Notification();
                    
                    $notification->book_ID = $row["book_ID"];
                    $notification->title = $row["title"];
                    $notification->notify_date = $row["notify_date"];
                    $notification->content = $row["content"];
                    
                    $data[] = $notification;
                }

                echo json_encode(array('result' => $data));
            }
            else {
                echo json_encode(array('errorMsg' => '查無通知。'));
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