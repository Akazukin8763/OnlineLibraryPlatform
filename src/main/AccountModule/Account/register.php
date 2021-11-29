<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        if ($username != null && $email != null && $password != null) {
            // 檢查電子郵件是否重複註冊
            $checkEmail = "SELECT * FROM User WHERE email = '$email'";
            if (mysqli_num_rows(mysqli_query($conn, $checkEmail)) != 0) { // 基本上只為 1
                echo json_encode(array('errorMsg' => '電子郵件已重複註冊。'));
                exit;
            }

            // 檢查使用者名稱是否重複
            $checkUsername = "SELECT * FROM User WHERE username = '$username'";
            if (mysqli_num_rows(mysqli_query($conn, $checkUsername)) != 0) { // 基本上只為 1
                echo json_encode(array('errorMsg' => '使用者名稱已經被人使用過。'));
                exit;
            }

            // 建立使用者
            $sql = "INSERT INTO User (username, email, password, is_admin)
                    VALUES ('$username', '$email', '$password', 0)";

            if (mysqli_query($conn, $sql)) { // 建立成功
                // 獲取剛建立使用者的 ID
                $sql = "SELECT ID FROM User WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    echo json_encode(array('ID' => $row["ID"]));
                }
                else {
                    echo json_encode(array('errorMsg' => '獲取使用者 ID 時發生錯誤。'));
                }
            }
            else {
                echo json_encode(array('errorMsg' => '建立帳號時發生錯誤。'));
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