<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($username != null && $password != null) {
            $sql = "SELECT * FROM User WHERE username = '$username' and password = '$password'";
            $result = mysqli_query($conn, $sql);
    
            if (mysqli_num_rows($result) == 1) { // 基本上註冊時已確保名稱唯一
                $row = mysqli_fetch_assoc($result);
    
                // 更新使用者狀態為 ONLINE
                $sql = "UPDATE User SET status = 'ONLINE' WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    session_start();
        
                    // 系統為登入狀態
                    $_SESSION["loggedin"] = true;
                    // 紀錄該名使用者的變數
                    $_SESSION["ID"] = $row["ID"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["is_admin"] = $row["is_admin"];

                    echo json_encode(array('ID' => $row["ID"], 
                                        'is_admin' => $row["is_admin"]));
                }
                else {
                    echo json_encode(array('errorMsg' => '狀態更新時發生錯誤。'));
                }
            }
            else {
                echo json_encode(array('errorMsg' => '帳號或密碼錯誤。'));
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