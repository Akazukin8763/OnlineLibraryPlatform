<?php
    /*
    尚缺通知使用者書籍已上架 User_Trace(...)
    */

    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $title = $_POST["title"];
        $image = $_POST["image"];
        $author = $_POST["author"];
        $publisher = $_POST["publisher"];
        $description = $_POST["description"];
        $publish_date = $_POST["publish_date"];
        $arrive_date = $_POST["arrive_date"];
        $category = $_POST["category"];
        
        if ($title != null && $image != null && $author != null && $publisher != null && $description != null && $publish_date != null && $arrive_date != null && $category != null &&
            is_string($title) && is_string($image) && is_string($author) && is_string($publisher) && is_string($description) && is_string($publish_date) && is_string($arrive_date) && is_array($category) &&
            strlen($title) <= 64 && strlen($image) <= 256 && strlen($author) <= 16 && strlen($publisher) <= 16 && strlen($description) <= 256) {
            
            // 建立書籍詳細內容
            $sql_book_detail = "INSERT INTO Book_Detail (title, image, author, publisher, description, publish_date, arrive_date)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_book_detail); 
            $stmt->bind_param("sssssss", $title, $image, $author, $publisher, $description, $publish_date, $arrive_date);
            $result = $stmt->execute();
            //$result = $stmt->get_result();

            // 不管內容是否建立成功，都能確保資料表中含有此書籍
            /*
            if ($result) { // 新增書籍內容
                // Nothing
            }
            else { // 已有重複
                // Nothing
            }
            */
            
            // 建立書籍類別
            // 書籍類別陣列轉換
            $category_list = '';
            $category_value = '';
            foreach ($category as $value) {
                $category_list = $category_list.', '.$value;
                $category_value = $category_value.', 1';
            }

            $sql_book_category = "INSERT INTO Book_Category (title $category_list)
                                    VALUES ('$title' $category_value)";
            $result = $conn->query($sql_book_category);

            // 不管內容是否建立成功，都能確保資料表中含有此書籍
            /*
            if ($result) { // 新增書籍類別
                // Nothing
            }
            else { // 已有重複
                // Nothing
            }
            */

            // 建立書籍
            $sql_book = "INSERT INTO Book (title)
                            VALUES (?)";
            $stmt = $conn->prepare($sql_book); 
            $stmt->bind_param("s", $title);
            $result = $stmt->execute();

            if ($result) {
                $sql = "SELECT book_ID
                        FROM Book
                        WHERE title = ? ORDER BY book_ID DESC";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("s", $title);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if (count($rows) != 0) {
                    echo json_encode(array('book_ID' => $rows[0]["book_ID"]));
                }
                else {
                    echo json_encode(array('errorMsg' => '獲取書籍 book_ID 時發生錯誤。'));
                }
            }
            else {
                echo json_encode(array('errorMsg' => '建立書籍時發生錯誤。'));
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