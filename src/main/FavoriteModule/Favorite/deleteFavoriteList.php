<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $folder = $_POST["folder"];

        if ($ID != null && $folder != null &&
            is_int((int) $ID) && is_string($folder) &&
            strlen($folder) <= 16) {
            
            if (strtoupper($folder) == "DEFAULT") {
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '不得刪除預設資料夾'));
                exit;
            }

            $sql = "SELECT *
                    FROM User_Favorite
                    WHERE ID = ? AND folder = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("is", $ID, $folder);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $sql = "UPDATE User_Favorite
                        SET folder = 'DEFAULT'
                        WHERE ID = ? AND folder = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("is", $ID, $folder);
                $result = $stmt->execute();

                if ($result) {
                    echo json_encode(array('__STATUS' => 'SUCCESS'));
                }
                else {
                    echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '刪除清單時發生錯誤。'));
                }
            }
            else {
                echo json_encode(array('__STATUS' => 'ERROR',
                                    'errorMsg' => '查無資料夾。'));
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