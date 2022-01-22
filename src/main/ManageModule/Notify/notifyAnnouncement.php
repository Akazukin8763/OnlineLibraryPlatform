<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $today = date("Y-m-d H:i:s");
        $content = $_POST["content"];

        $conn->autocommit(false);

        if ($content != null &&
            is_string($content) &&
            strlen($content) <= 256) {

            $sql = "SELECT ID
                    FROM User
                    WHERE is_admin = 0 AND ID != 0";
            $stmt = $conn->prepare($sql); 
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            if (count($rows) != 0) {
                $sql = "INSERT INTO Notification (ID, notify_date, content)
                        VALUES (?, ?, ?)";      
                $stmt = $conn->prepare($sql); 

                foreach ($rows as $row) {
                    $stmt->bind_param("iss", $row["ID"], $today, $content);
                    $result = $stmt->execute();

                    if ($result && $stmt->affected_rows == 1) {
                        // Nothing
                    }
                    else {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '傳送通知時發生錯誤。'));
                        exit;
                    }
                }
                
                $conn->commit();
                echo json_encode(array('__STATUS' => 'SUCCESS'));
            }
            else {
                $conn->rollback();
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '尚無任何使用者可以接收公告。'));
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