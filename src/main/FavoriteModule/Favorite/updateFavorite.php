<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $title = $_POST["title"];
        $folder = $_POST["folder"];

        if ($ID != null && $title != null && $folder != null &&
            is_int((int) $ID) && is_string($title) && is_string($folder) &&
            strlen($title) <= 64 && strlen($folder) <= 16) {

            $sql = "SELECT *
                    FROM Book_Detail
                    WHERE title = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $title);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) {
                $sql = "INSERT INTO User_Favorite (ID, title, folder)
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE ID = ?, title = ?, folder = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("ississ", $ID, $title, $folder, $ID, $title, $folder);
                $result = $stmt->execute();

                if ($result) {
                    echo json_encode(array('__STATUS' => 'SUCCESS'));
                }
                else {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '更新最愛清單時發生錯誤。'));
                }
            }
            else {
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '查無書籍資料。'));
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