<?php
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $category_list = array("action_and_adventure", "alternate_history", "anthology", "chick_lit", "children",
                                "classic", "comic_book", "coming_of_age", "crime", "drama",
                                "fairytale", "fantasy", "graphic_novel", "historical_fiction", "horror",
                                "mystery", "paranormal_romance", "picture_book", "poetry", "political_thriller",
                                "romance", "satire", "science_fiction", "short_story", "suspense",
                                "thriller", "western", "young_adult");

        if ($email != null && $password != null &&
            is_string($email) && is_string($password) &&
            strlen($email) <= 64 && 6 <= strlen($password) && strlen($password) <= 16) {
    
            $sql = "SELECT *
                    FROM User
                    WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) {
                $ID = $rows[0]["ID"];
                $username = $rows[0]["username"];
                $is_admin = $rows[0]["is_admin"];

                // 更新使用者狀態為 ONLINE
                $sql = "UPDATE User 
                        SET status = 'ONLINE' 
                        WHERE email = ? AND password = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("ss", $email, $password);
                $result = $stmt->execute();

                if ($result) {
                    // 獲取偏好設定
                    $sql = "SELECT *
                            FROM User_Preferences
                            WHERE ID = ?";
                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("i", $ID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    if (count($rows) == 1) {
                        $category = array();
                        foreach ($category_list as $value) {
                            if ($rows[0][$value] == 0) {
                                $category[] = $value;
                            }
                        }

                        session_start();
        
                        // _Session 紀錄登入狀態
                        $_SESSION["loggedin"] = true;
                        // _Session 紀錄使用者資料
                        $_SESSION["ID"] = $ID;
                        $_SESSION["username"] = $username;
                        $_SESSION["is_admin"] = $is_admin;
                        $_SESSION["category"] = $category;
        
                        echo json_encode(array('ID' => $ID,
                                                'username' => $username,
                                                'is_admin' => $is_admin,
                                                'category' => $category));
                    }
                    else {
                        echo json_encode(array('errorMsg' => '獲取資料時發生錯誤。'));
                    }
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