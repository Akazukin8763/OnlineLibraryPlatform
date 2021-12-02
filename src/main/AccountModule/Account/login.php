<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($email != null && $password != null &&
            is_string($email) && is_string($password) &&
            strlen($email) <= 64 && 6 <= strlen($password) && strlen($password) <= 16) {

            $sql = "SELECT * FROM User WHERE email = '$email' and password = '$password'";
            $result = mysqli_query($conn, $sql);
    
            if (mysqli_num_rows($result) == 1) { // email 唯一
                $row = mysqli_fetch_assoc($result);
    
                // 更新使用者狀態為 ONLINE
                $sql = "UPDATE User SET status = 'ONLINE' WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    session_start();
        
                    // _Session 紀錄登入狀態
                    $_SESSION["loggedin"] = true;
                    // _Session 紀錄使用者資料
                    $_SESSION["ID"] = $row["ID"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["is_admin"] = $row["is_admin"];

                    echo json_encode(array('ID' => $row["ID"],
                                        'username' => $row["username"],
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