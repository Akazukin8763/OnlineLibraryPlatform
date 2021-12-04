<?php
    // Include config file
    $conn = require_once "../../config.php";

    class Book {
        public $book_ID;
        public $title;
        public $image;
        public $author;
        public $publisher;
        public $description;
        public $publish_date;
        public $arrive_date;
        public $category;
        public $book_status;
        public $score;
        public $comment;
        public $times;
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        session_start(); 
        $ID = $_SESSION["ID"];
        $title = $_GET["title"];
        $category = $_GET["category"];

        $category_list = array("action_and_adventure", "alternate_history", "anthology", "chick_lit", "children",
                                "classic", "comic_book", "coming_of_age", "crime", "drama",
                                "fairytale", "fantasy", "graphic_novel", "historical_fiction", "horror",
                                "mystery", "paranormal_romance", "picture_book", "poetry", "political_thriller",
                                "romance", "satire", "science_fiction", "short_story", "suspense",
                                "thriller", "western", "young_adult");

        if ($title != null && $category != null &&
            is_string($title) && is_array($category) &&
            strlen($title) <= 64) {

            // 書籍類別陣列轉換
            $sql_category = '';
            foreach ($category as $value) {
                $sql_category = $sql_category.$value.' = 1 OR ';
            }
            $sql_category = substr($sql_category, 0, -4);

            // 使用者偏好
            $sql = "SELECT * FROM User_Preferences WHERE ID = '$ID'";
            $result = mysqli_query($conn, $sql);

            $flag = 0;
            $sql_prefer = '';
            if ($result) { // 條件
                $row = mysqli_fetch_assoc($result);

                foreach ($category_list as $value) {
                    if ($row[$value] == 0) {
                        $sql_prefer = $sql_prefer.$value.' = 1 OR ';
                        $flag = $flag + 1;
                    }
                }

                if ($flag > 0) {
                    $sql_prefer = " AND 
                                    title not in (SELECT title
                                                    FROM Book_Category
                                                    WHERE ".$sql_prefer;
                    $sql_prefer = substr($sql_prefer, 0, -4);
                    $sql_prefer = $sql_prefer.")"; 
                }
            }
            else { // 找不到偏好不重要，不額外篩選
                // Nothing
            }

            // 塞選結果
            $sql = "SELECT *
                    FROM Book JOIN Book_Category USING (title)
                    WHERE title LIKE '%$title%' AND ($sql_category) $sql_prefer";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $data = array();
                while ($row = mysqli_fetch_assoc($result)) { // 抓出每一筆相關資料
                    $last_book = end($data);

                    if ($last_book->title == $row["title"]) { // 在確保同一本書並排時，若前一本書與現在書名相同，則只合併 book_ID, book_status
                        $last_book->book_ID[] = $row["book_ID"];
                        $last_book->book_status[] = $row["book_status"];
                    }
                    else {
                        $book = new Book();

                        // 書籍資訊
                        $book->book_ID = array($row["book_ID"]);
                        $book->title = $row["title"];
                        $book->image = $row["image"];
                        $book->author = $row["author"];
                        $book->publisher = $row["publisher"];
                        $book->description = $row["description"];
                        $book->publisher = $row["publish_date"];
                        $book->arrive_date = $row["arrive_date"];

                        $book_category = array();
                        foreach ($category_list as $value) {
                            if ($row[$value] == 1) {
                                $book_category[] = $value;
                            }
                        }
                        $book->category = $book_category;

                        $book->book_status = array($row["book_status"]);

                        // 書籍評分
                        $sql_book_score = "SELECT avg(score) as avg_score, sum(times) as total_times
                                            FROM Book_Comment
                                            WHERE title = '$book->title'
                                            GROUP BY (title)";
                        $sql_book_comment = "SELECT comment
                                                FROM Book_Comment
                                                WHERE title = '$book->title'";
                        
                        $result_book_score = mysqli_query($conn, $sql_book_score);
                        $result_book_comment = mysqli_query($conn, $sql_book_comment);
                                    
                        if ($result_book_score && $result_book_comment) {
                            // 平均評分、總借閱次數
                            $row = mysqli_fetch_assoc($result_book_score);

                            $book->score = $row["avg_score"];
                            $book->times = $row["total_times"];

                            // 全部評論
                            $comment = array();
                            while ($row = mysqli_fetch_assoc($result_book_comment)) {
                                $comment[] = $row["comment"];
                            }

                            $book->comment = $comment;
                        }
                        else { // 查詢失敗，不重要，評分類的顯示未知即可
                            // Nothing
                        }

                        $data[] = $book;
                    }
                }
                
                if (count($data) != 0) {
                    echo json_encode(array('result' => $data));
                }
                else {
                    echo json_encode(array('errorMsg' => '查無書籍資料。'));
                }
            }
            else {
                echo json_encode(array('errorMsg' => '獲取書籍時發生錯誤。'));
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