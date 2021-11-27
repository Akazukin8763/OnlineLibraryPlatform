<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $ID = $_POST["ID"];

        if ($ID != null) {
            $sql = "UPDATE User SET status = 'OFFLINE' WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);
    
            if ($result) {
                session_start(); 
                $_SESSION = array(); 
                session_destroy(); 

                echo json_encode(array('__STATUS' => 'SUCCESS'));
            }
            else {
                echo json_encode(array('__STATUS' => 'ERROR'));
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