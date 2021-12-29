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

            $sql = "SELECT username
                    FROM User
                    WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) { // ID 唯一
                $old_username = $rows[0]["username"];

                if ($old_username != $new_username) {
                    // 檢查使用者名稱是否重複
                    $checkUsername = "SELECT * 
                                        FROM User 
                                        WHERE username = ?";
                    $stmt = $conn->prepare($checkUsername);
                    $stmt->bind_param("s", $new_username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    if (count($rows) != 0) { // 基本上只為 1
                        echo json_encode(array('__STATUS' => 'ERROR',
                                            'errorMsg' => '使用者名稱已經被人使用過。'));
                        exit;
                    }

                    // 更新名稱
                    $sql = "UPDATE User
                            SET username = ?
                            WHERE ID = ? and username = ?";
                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("sis", $new_username, $ID, $old_username);
                    $result = $stmt->execute();

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
