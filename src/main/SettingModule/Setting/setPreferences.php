<?php
    /*
    待修正 _SESSION 
    */
    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $category = $_POST["category"]; // 可能為空
        
        $category_list = array("action_and_adventure", "alternate_history", "anthology", "chick_lit", "children",
                                "classic", "comic_book", "coming_of_age", "crime", "drama",
                                "fairytale", "fantasy", "graphic_novel", "historical_fiction", "horror",
                                "mystery", "paranormal_romance", "picture_book", "poetry", "political_thriller",
                                "romance", "satire", "science_fiction", "short_story", "suspense",
                                "thriller", "western", "young_adult");

        if ($ID != null &&
            is_int((int) $ID)) {
            
            // 書籍類別陣列轉換
            $sql_category = '';
            foreach ($category_list as $value) {
                if (in_array($value, $category)) { // 在輸入中則將其設為隱藏
                    $sql_category = $sql_category.$value.' = 0, ';
                }
                else {
                    $sql_category = $sql_category.$value.' = 1, ';
                }
            }
            $sql_category = substr($sql_category, 0, -2);

            $sql = "UPDATE User_Preferences
                        SET $sql_category
                        WHERE ID = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $result = $stmt->execute();

            if ($result) {
                // 修改 _SESSION 的 category
                $_SESSION["category"] = $category;

                echo json_encode(array('__STATUS' => 'SUCCESS'));
            }
            else {
                echo json_encode(array('__STATUS' => 'ERROR',
                                        'errorMsg' => '個人偏好更新時發生錯誤。'));
            }
        }
        else {
            echo json_encode(array('errorMsg' => '參數輸入錯誤！'));
        }
    }
    else {
        echo json_encode(array('errorMsg' => '請求無效，只允許 GET 方式訪問！'));
    }

    // Close connection
    mysqli_close($conn);
?>
