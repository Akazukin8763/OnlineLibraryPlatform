<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $title = $_POST["title"];

        $conn->autocommit(false);
        
        if ($title != null &&
            is_string($title) &&
            strlen($title) <= 64) {

            $sql = "INSERT INTO User_Trace (ID, title) 
                    Values (?, ?)";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("is", $ID, $title);
            $result = $stmt->execute();

            if ($result && $stmt->affected_rows == 1) {
                $conn->commit();
                echo json_encode(array('__STATUS' => 'SUCCESS'));
            }
            else {
                $conn->rollback();
                echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '無法追蹤相同名稱的書籍。'));
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