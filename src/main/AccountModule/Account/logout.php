<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $ID = $_POST["ID"];

        if ($ID != null &&
            is_int((int) $ID)) {

            // 更新使用者狀態為 OFFLINE
            $sql = "UPDATE User SET status = 'OFFLINE' WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);
    
            if ($result) {
                session_start(); 
                $_SESSION = array(); 
                session_destroy(); 

                echo json_encode(array('__STATUS' => 'SUCCESS'));
            }
            else {
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '狀態更新時發生錯誤。'));
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