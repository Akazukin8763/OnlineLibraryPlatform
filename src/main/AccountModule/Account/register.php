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
            if (mysqli_num_rows(mysqli_query($conn, $checkEmail)) != 0) {
                echo json_encode(array('errorMsg' => '電子郵件已重複註冊。'));
                exit;
            }

            // 檢查使用者名稱是否重複
            $checkUsername = "SELECT * FROM User WHERE username = '$username'";
            if (mysqli_num_rows(mysqli_query($conn, $checkUsername)) != 0) {
                echo json_encode(array('errorMsg' => '使用者名稱已經被人使用過。'));
                exit;
            }

            $sql = "INSERT INTO User (username, email, password, is_admin)
                    VALUES ('".$username."', '".$email."', '".$password."', 0)";

            if (mysqli_query($conn, $sql)) { // 建立成功
                $sql = "SELECT ID FROM User WHERE username = '$username'";
                $result = $conn->query($sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    echo json_encode(array('ID' => $row["ID"]));
                }
                else {
                    echo json_encode(array('errorMsg' => '查詢資料庫時錯誤。'));
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