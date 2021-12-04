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

            // 建立書籍
            $sql_book = "INSERT INTO Book (title, image, author, publisher, description, publish_date, arrive_date)
                            VALUES ('$title', '$image', '$author', '$publisher', '$description', '$publish_date', '$arrive_date')";
            
            if (mysqli_query($conn, $sql_book)) {
                // 查看有無必要再度新增相同書籍的類別
                $sql_same_book = "SELECT * FROM Book WHERE title = '$title'";
                if (mysqli_num_rows(mysqli_query($conn, $sql_same_book)) == 0) { // 暫無相同書籍
                    // 書籍類別陣列轉換
                    $category_list = '';
                    $category_value = '';
                    foreach ($category as $value) {
                        $category_list = $category_list.', '.$value;
                        $category_value = $category_value.', 1';
                    }

                    // 建立書籍類別
                    $sql_book_category = "INSERT INTO Book_Category (title $category_list)
                    VALUES ('$title' $category_value)";

                    if (mysqli_query($conn, $sql_book_category)) {
                        // Nothing
                    }
                    else {
                        echo json_encode(array('errorMsg' => '建立書籍類別時發生錯誤。'));
                        exit;
                    }
                }
                else { // 有重複書籍，不新增
                    // Nothing
                }

                // 獲取剛建立書籍的 book_ID，最後新增的那本（可能有重複 title）
                $sql = "SELECT book_ID FROM Book WHERE title = '$title' ORDER BY book_ID DESC";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    echo json_encode(array('book_ID' => $row["book_ID"]));
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