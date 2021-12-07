<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start();
        $ID = $_SESSION["ID"];
        $new_password = $_POST["password"];

        if ($ID != null && $new_password != null &&
            is_int((int) $ID) && is_string($new_password) &&
            6 <= strlen($new_password) && strlen($new_password) <= 16) {

            $sql = "SELECT password FROM User WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);

            $sql = "SELECT password
                    FROM User
                    WHERE ID = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) { // ID 唯一
                $old_password = $rows[0]["password"];

                if ($old_password != $new_password) {
                    // 更新密碼
                    $sql = "UPDATE User 
                            SET password = ?
                            WHERE ID = ? and password = ?";
                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("sis", $new_password, $ID, $old_password);
                    $result = $stmt->execute();

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
            else {
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '資料庫發生不可預期的錯誤！'));
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