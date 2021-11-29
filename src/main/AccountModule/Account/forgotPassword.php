<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];

        if ($username != null && $email != null) {
            $sql = "SELECT * FROM User WHERE username = '$username' and email = '$email'";
            $result = mysqli_query($conn, $sql);
    
            if (mysqli_num_rows($result) == 1) { // 基本上註冊時已確保名稱郵件唯一
                $row = mysqli_fetch_assoc($result);

                $old_password = $row["password"];
                $new_password = random_string(16);

                // 更新使用者密碼
                $sql = "UPDATE User SET password = '$new_password' WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo json_encode(array('old_password' => $old_password, 
                                        'new_password' => $new_password));
                }
                else {
                    echo json_encode(array('errorMsg' => '密碼更新時發生錯誤。'));
                }

            }
            else {
                echo json_encode(array('errorMsg' => '名稱或郵件帳號錯誤。'));
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

    // 產生隨機密碼
    function random_string($length = 32, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        if (!is_int($length) || $length < 0) {
            return false;
        }

        $characters_length = strlen($characters) - 1;
        $string = '';
    
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $characters_length)];
        }

        return $string;
    }
?>