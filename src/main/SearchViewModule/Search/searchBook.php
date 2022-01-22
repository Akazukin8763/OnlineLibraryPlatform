<?php
    // Include config file
    $conn = require_once "../../config.php";

    class Book {
        public $title;
        public $image;
        public $author;
        public $publisher;
        public $description;
        public $publish_date;
        public $arrive_date;
        public $category; // (array)
        public $times;

        public $books; // class Books(array)
        public $comments; // class Comments(array)
    }
    class Books {
        public $book_ID;
        public $book_status;
    }
    class Comments {
        public $username;
        public $score;
        public $comment;
        public $comment_date;
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
            $sql_category = '(';
            foreach ($category as $value) {
                $sql_category = $sql_category.$value.' = 1 OR ';
            }
            $sql_category = substr($sql_category, 0, -4);
            $sql_category = $sql_category.')';

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
            else { // 找不到偏好，不重要，不額外篩選
                // Nothing
            }

            // 篩選結果
            $_title = "%$title%"; // SQL 預處理，於關鍵字搜尋

            $sql = "SELECT * 
                    FROM Book_Detail JOIN Book_Category USING (title)
                    WHERE title LIKE ? AND $sql_category $sql_prefer";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $_title);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $data = array();
                foreach($rows as $row) {
                    // 書籍資訊
                    $book = new Book();

                    $book->title = $row["title"];
                    $book->image = $row["image"];
                    $book->author = $row["author"];
                    $book->publisher = $row["publisher"];
                    $book->description = $row["description"];
                    $book->publish_date = $row["publish_date"];
                    $book->arrive_date = $row["arrive_date"];

                    $book_category = array();
                    foreach ($category_list as $value) {
                        if ($row[$value] == 1) {
                            $book_category[] = $value;
                        }
                    }
                    $book->category = $book_category;

                    // 書籍總借閱次數
                    $sql_times = "SELECT count(ID) as times
                                    FROM Book_Trace NATURAL JOIN Book
                                    WHERE title = ? AND end_date IS NOT NULL
                                    GROUP BY (title)";
                    $stmt_times = $conn->prepare($sql_times);
                    $stmt_times->bind_param("s", $row["title"]);
                    $stmt_times->execute();
                    $result_times = $stmt_times->get_result();
                    $rows_times = $result_times->fetch_all(MYSQLI_ASSOC);

                    if (count($rows_times) != 0) {
                        $book->times = $rows_times[0]["times"];
                    }
                    else {
                        $book->times = 0;
                    }

                    // 書籍狀態
                    $sql_books = "SELECT *
                                    FROM Book
                                    WHERE title = ?";
                    $stmt_books = $conn->prepare($sql_books); 
                    $stmt_books->bind_param("s", $row["title"]);
                    $stmt_books->execute();
                    $result_books = $stmt_books->get_result();
                    $rows_books = $result_books->fetch_all(MYSQLI_ASSOC);

                    $data_books = array();
                    foreach($rows_books as $row_books) { // 一定有至少一本書
                        $books = new Books();

                        $books->book_ID = $row_books["book_ID"];
                        $books->book_status = $row_books["book_status"];

                        $data_books[] = $books;
                    }
                    $book->books = $data_books;

                    // 書籍評論
                    $sql_comments = "SELECT *
                                        FROM Book_Comment JOIN User USING (ID)
                                        WHERE title = ?";
                    $stmt_comments = $conn->prepare($sql_comments); 
                    $stmt_comments->bind_param("s", $row["title"]);
                    $stmt_comments->execute();
                    $result_comments = $stmt_comments->get_result();
                    $rows_comments = $result_comments->fetch_all(MYSQLI_ASSOC);

                    $data_comments = array();
                    foreach($rows_comments as $row_comments) { // 不一定有人評論
                        $comments = new Comments();
                        
                        $comments->username = $row_comments["username"];
                        $comments->score = $row_comments["score"];
                        $comments->comment = $row_comments["comment"];
                        $comments->comment_date = $row_comments["comment_date"];

                        $data_comments[] = $comments;
                    }
                    $book->comments = $data_comments;

                    $data[] = $book;
                }
                echo json_encode(array('result' => $data));
            }
            else {
                echo json_encode(array('errorMsg' => '查無書籍資料。'));
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