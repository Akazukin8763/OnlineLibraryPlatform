<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $ID = $_POST["ID"];
        $new_password = $_POST["password"];

        if ($ID != null && $new_password != null &&
            is_int((int) $ID) && is_string($new_password) &&
            6 <= strlen($new_password) && strlen($new_password) <= 16) {

            $sql = "SELECT password FROM User WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);

            if ($result) { // ID 唯一
                $row = mysqli_fetch_assoc($result);
                $old_password = $row["password"];

                if ($old_password != $new_password) {
                    // 更新密碼
                    $sql = "UPDATE User SET password = '$new_password' WHERE ID = '$ID' and password = '$old_password'";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        echo json_encode(array('__STATUS' => 'SUCCESS'));
                    }
                    else {
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '密碼更新時發生錯誤。'));
                    }
                }
                else {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '新舊密碼不可相同。'));
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