<?php
    // Include config file
    $conn = require_once "../../config.php";

    class Folder {
        public $folder;

        public $content; // class Contents(array)
    }
    class Content {
        public $title;
        public $image;
        public $score;
        public $comment;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];

        if ($ID != null &&
            is_int((int) $ID)) {
            
            // 資料夾列表
            $sql = "SELECT DISTINCT folder
                    FROM User_Favorite
                    WHERE ID = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $data = array();
                foreach($rows as $row) {
                    // 最愛清單
                    $folder = new Folder();
                    
                    $folder->folder = $row["folder"];

                    // 清單內容
                    $sql_content = "SELECT *
                                    FROM User_Favorite NATURAL JOIN Book_Detail LEFT OUTER JOIN Book_Comment USING (ID, title) 
                                    WHERE ID = ? AND folder = ?";
                    $stmt_content = $conn->prepare($sql_content); 
                    $stmt_content->bind_param("is", $ID, $row["folder"]);
                    $stmt_content->execute();
                    $result_content = $stmt_content->get_result();
                    $rows_content = $result_content->fetch_all(MYSQLI_ASSOC);

                    $data_content = array();
                    foreach($rows_content as $row_content) {
                        $content = new Content();
                        
                        $content->title = $row_content["title"];
                        $content->image = $row_content["image"];
                        $content->score = $row_content["score"];
                        $content->comment = $row_content["comment"];

                        $data_content[] = $content;
                    }
                    $folder->content = $data_content;
                    
                    $data[] = $folder;
                }

                echo json_encode(array('result' => $data));
            }
            else {
                echo json_encode(array('errorMsg' => '查無最愛清單。'));
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