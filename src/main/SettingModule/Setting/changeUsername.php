<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start();
        $ID = $_SESSION["ID"];
        $new_username = $_POST["username"];

        if ($ID != null && $new_username != null &&
            is_int((int) $ID) && is_string($new_username) &&
            strlen($new_username) <= 16) {

            $sql = "SELECT username FROM User WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);

            if ($result) { // ID 唯一
                $row = mysqli_fetch_assoc($result);
                $old_username = $row["username"];

                if ($old_username != $new_username) {
                    // 檢查使用者名稱是否重複
                    $checkUsername = "SELECT * FROM User WHERE username = '$new_username'";
                    if (mysqli_num_rows(mysqli_query($conn, $checkUsername)) != 0) { // 基本上只為 1
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '使用者名稱已經被人使用過。'));
                        exit;
                    }

                    // 更新名稱
                    $sql = "UPDATE User SET username = '$new_username' WHERE ID = '$ID' and username = '$old_username'";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        // 修改 _SESSION 的 username
                        $_SESSION["username"] = $new_username;

                        echo json_encode(array('__STATUS' => 'SUCCESS'));
                    }
                    else {
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '名稱更新時發生錯誤。'));
                    }
                }
                else {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '新舊名稱不可相同。'));
                }
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