<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $title = $_POST["title"];
        $score = $_POST["score"];
        $comment = $_POST["comment"];

        if ($ID != null && $title != null && $score != null && $comment != null &&
            is_int((int) $ID) && is_string($title) && is_int((int) $score) && is_string($comment) &&
            strlen($title) <= 64 && 0 <= (int) $score && (int) $score <= 10 && strlen($comment) <= 256) {
            
                $sql = "SELECT *
                        FROM Book_Detail
                        WHERE title = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("s", $title);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if (count($rows) == 1) {
                    // 確認是否歸還過此書籍
                    $sql = "SELECT *
                            FROM Book_Trace NATURAL JOIN Book
                            WHERE ID = ? AND title = ? AND end_date IS NOT NULL";
                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("is", $ID, $title);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    if (count($rows) != 0) {
                        $today = date("Y-m-d H:i:s");

                        // 新增或編輯評論
                        $sql = "INSERT INTO Book_Comment (ID, title, score, comment, comment_date)
                                VALUES (?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE ID = ?, title = ?, score = ?, comment = ?, comment_date = ?";
                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("isissisiss", $ID, $title, $score, $comment, $today, $ID, $title, $score, $comment, $today);
                        $result = $stmt->execute();

                        if ($result) {
                            echo json_encode(array('__STATUS' => 'SUCCESS'));
                        }
                        else {
                            echo json_encode(array('__STATUS' => 'ERROR',
                                                'errorMsg' => '新增或編輯評論時發生錯誤。'));
                        }
                    }
                    else {
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '未有此書的借閱及歸還紀錄，不得評論。'));
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