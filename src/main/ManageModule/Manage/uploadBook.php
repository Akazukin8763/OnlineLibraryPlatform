<?php
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
        
        $conn->autocommit(false);

        if ($title != null && $image != null && $author != null && $publisher != null && $description != null && $publish_date != null && $arrive_date != null && $category != null &&
            is_string($title) && is_string($image) && is_string($author) && is_string($publisher) && is_string($description) && is_string($publish_date) && is_string($arrive_date) && is_array($category) &&
            strlen($title) <= 64 && strlen($image) <= 256 && strlen($author) <= 16 && strlen($publisher) <= 16 && strlen($description) <= 512) {
            
            // 先確認有無詳細資料
            $sql = "SELECT *
                    FROM Book_Detail
                    WHERE title = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $title);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) == 1) { // 已經有詳細資料，不重複新建
                // Nothing
            }
            else {
                // 建立書籍詳細內容
                $sql_book_detail = "INSERT INTO Book_Detail (title, image, author, publisher, description, publish_date, arrive_date)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_book_detail); 
                $stmt->bind_param("sssssss", $title, $image, $author, $publisher, $description, $publish_date, $arrive_date);
                $result = $stmt->execute();

                if ($result && $stmt->affected_rows == 1) { // 建立成功
                    // Nothing
                }
                else { // 建立失敗
                    $conn->rollback();
                    echo json_encode(array('errorMsg' => '建立書籍資料時發生錯誤。'));
                    exit;
                }

                // 建立書籍類別
                // 書籍類別陣列轉換
                $category_list = '';
                $category_value = '';
                foreach ($category as $value) {
                    $category_list = $category_list.', '.$value;
                    $category_value = $category_value.', 1';
                }

                $sql_book_category = "INSERT INTO Book_Category (title $category_list)
                                        VALUES (? $category_value)";
                $stmt = $conn->prepare($sql_book_category); 
                $stmt->bind_param("s", $title);
                $result = $stmt->execute();

                if ($result && $stmt->affected_rows == 1) { // 建立成功
                    // Nothing
                }
                else { // 建立失敗
                    $conn->rollback();
                    echo json_encode(array('errorMsg' => '建立書籍類別時發生錯誤。'));
                    exit;
                }

                // 通知追蹤此本書籍的使用者（新上架）
                // 找出新上架書籍的子字串中，含有使用者追蹤的書籍
                $sql_is_trace = "SELECT DISTINCT ID
                                    FROM User_Trace
                                    WHERE INSTR(?, title) > 0 AND title NOT IN (SELECT title FROM Book_Detail)";
                $stmt = $conn->prepare($sql_is_trace); 
                $stmt->bind_param("s", $title);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                // 通知追蹤的使用者
                $sql_notify_trace = "INSERT INTO Notification (ID, notify_date, content)
                                        VALUES (?, ?, ?)";         
                $stmt = $conn->prepare($sql_notify_trace); 

                $today = date("Y-m-d H:i:s");
                $content = "您追蹤的可能書籍「".$title."」已於館內新上架，可以前去參考。";

                foreach($rows as $row) {
                    $stmt->bind_param("iss", $row["ID"], $today, $content);
                    $result = $stmt->execute();

                    if ($result && $stmt->affected_rows == 1) {
                        // Nothing
                    }
                    else {
                        $conn->rollback();
                        echo json_encode(array('errorMsg' => '傳送通知時發生錯誤。'));
                        exit;
                    }
                }
            }

            // 建立書籍
            $sql_book = "INSERT INTO Book (title)
                            VALUES (?)";
            $stmt = $conn->prepare($sql_book); 
            $stmt->bind_param("s", $title);
            $result = $stmt->execute();

            if ($result && $stmt->affected_rows == 1) {
                $sql = "SELECT book_ID
                        FROM Book
                        WHERE title = ? ORDER BY book_ID DESC";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("s", $title);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if (count($rows) != 0) {
                    $conn->commit();
                    echo json_encode(array('book_ID' => $rows[0]["book_ID"]));
                }
                else {
                    $conn->rollback();
                    echo json_encode(array('errorMsg' => '獲取書籍編號時發生錯誤。'));
                }
            }
            else {
                $conn->rollback();
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