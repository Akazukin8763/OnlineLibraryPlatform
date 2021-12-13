<?php
    /*
    尚缺通知使用者書籍已上架 User_Trace(...)
    */

    // Include config file
    $conn = require_once "../../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $old_title = $_POST["old_title"];
        $title = $_POST["title"];
        $image = $_POST["image"];
        $author = $_POST["author"];
        $publisher = $_POST["publisher"];
        $description = $_POST["description"];
        $publish_date = $_POST["publish_date"];
        $arrive_date = $_POST["arrive_date"];
        $category = $_POST["category"];

        $category_list = array("action_and_adventure", "alternate_history", "anthology", "chick_lit", "children",
                                "classic", "comic_book", "coming_of_age", "crime", "drama",
                                "fairytale", "fantasy", "graphic_novel", "historical_fiction", "horror",
                                "mystery", "paranormal_romance", "picture_book", "poetry", "political_thriller",
                                "romance", "satire", "science_fiction", "short_story", "suspense",
                                "thriller", "western", "young_adult");
        
        if ($title != null && $image != null && $author != null && $publisher != null && $description != null && $publish_date != null && $arrive_date != null && $category != null &&
            is_string($title) && is_string($image) && is_string($author) && is_string($publisher) && is_string($description) && is_string($publish_date) && is_string($arrive_date) && is_array($category) &&
            strlen($title) <= 64 && strlen($image) <= 256 && strlen($author) <= 16 && strlen($publisher) <= 16 && strlen($description) <= 512) {
            
                $sql = "SELECT * 
                        FROM Book_Detail
                        WHERE title = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("s", $old_title);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if (count($rows) == 1) {
                    if ($old_title != $title) { // 更改書名，確保新書名資料庫中不存在
                        $sql_same_book = "SELECT *
                                            FROM Book_Detail
                                            WHERE title = ?";
                        $stmt_same_book = $conn->prepare($sql_same_book); 
                        $stmt_same_book->bind_param("s", $title);
                        $stmt_same_book->execute();
                        $result_same_book = $stmt_same_book->get_result();
                        $rows_same_book = $result_same_book->fetch_all(MYSQLI_ASSOC);

                        if (count($rows_same_book) == 1) {
                            echo json_encode(array('__STATUS' => 'ERROR',
                                                'errorMsg' => '新書名已經存在。'));
                            exit;
                        }
                    }

                    $conn->autocommit(false);

                    // 更新書籍詳細資料
                    $sql_book_detail = "UPDATE Book_Detail
                                        SET title = ?, image = ?, author = ?, publisher = ?, description = ?, publish_date = ?, arrive_date = ?
                                        WHERE title = ?";
                    $stmt_book_detail = $conn->prepare($sql_book_detail); 
                    $stmt_book_detail->bind_param("ssssssss", $title, $image, $author, $publisher, $description, $publish_date, $arrive_date, $old_title);
                    $result = $stmt_book_detail->execute();

                    if (!$result) {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍資料時發生錯誤。'));
                        exit;
                    }

                    // 更新書籍類別
                    // 書籍類別陣列轉換
                    $sql_category = '';
                    foreach ($category_list as $value) {
                        $sql_category = $sql_category.', '.$value;
                        if (in_array($value, $category)) {
                            $sql_category = $sql_category.' = 1';
                        }
                        else {
                            $sql_category = $sql_category.' = 0';
                        }
                    }

                    $sql_book_category = "UPDATE Book_Category
                                            SET title = ? $sql_category
                                            WHERE title = ?";
                    $stmt_book_category = $conn->prepare($sql_book_category); 
                    $stmt_book_category->bind_param("ss", $title, $old_title);
                    $result = $stmt_book_category->execute();

                    if (!$result) {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍類別時發生錯誤。'));
                        exit;
                    }

                    // 更新書籍（編號）
                    $sql_book = "UPDATE Book
                                    SET title = ?
                                    WHERE title = ?";
                    $stmt_book = $conn->prepare($sql_book); 
                    $stmt_book->bind_param("ss", $title, $old_title);
                    $result = $stmt_book->execute();

                    if (!$result) {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍時發生錯誤。'));
                        exit;
                    }

                    // 更新書籍評論
                    $sql_book_comment = "UPDATE Book_Comment
                                            SET title = ?
                                            WHERE title = ?";
                    $stmt_book_comment = $conn->prepare($sql_book_comment); 
                    $stmt_book_comment->bind_param("ss", $title, $old_title);
                    $result = $stmt_book_comment->execute();

                    if (!$result) {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍時發生錯誤。'));
                        exit;
                    }

                    // 更新使用者最愛
                    $sql_user_favorite = "UPDATE User_Favorite
                                            SET title = ?
                                            WHERE title = ?";
                    $stmt_user_favorite = $conn->prepare($sql_user_favorite); 
                    $stmt_user_favorite->bind_param("ss", $title, $old_title);
                    $result = $stmt_user_favorite->execute();

                    if (!$result) {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍時發生錯誤。'));
                        exit;
                    }

                    // 更新使用者追蹤
                    $sql_user_trace = "UPDATE User_Trace
                                            SET title = ?
                                            WHERE title = ?";
                    $stmt_user_trace = $conn->prepare($sql_user_trace); 
                    $stmt_user_trace->bind_param("ss", $title, $old_title);
                    $result = $stmt_user_trace->execute();

                    if (!$result) {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍時發生錯誤。'));
                        exit;
                    }

                    // 確保至少有更動一項
                    if ($stmt_book_detail->affected_rows + $stmt_book_category->affected_rows > 0 ||
                        ($old_title != $title && $stmt_book->affected_rows > 0) ||
                        ($old_title != $title && $stmt_book_comment->affected_rows > 0) ||
                        ($old_title != $title && $stmt_user_favorite->affected_rows > 0) ||
                        ($old_title != $title && $stmt_user_trace->affected_rows > 0)) {
                        $conn->commit();
                        echo json_encode(array('__STATUS' => 'SUCCESS'));
                    }
                    else {
                        $conn->rollback();
                        echo json_encode(array('__STATUS' => 'ERROR', 'errorMsg' => '更新書籍時發生錯誤。'));
                        exit;
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