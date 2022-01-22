<?php
    // Include config file
    $conn = require_once "../../config.php";

    class Status {
        public $book_ID;
        public $title;
        public $image;
        public $book_status;
        public $order;
        public $deadline;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start(); 
        $ID = $_SESSION["ID"];

        if ($ID != null &&
            is_int((int) $ID)) {
            
            $sql = "SELECT *
                    FROM Book_Trace NATURAL JOIN Book NATURAL JOIN Book_Detail
                    WHERE ID = ? AND end_date IS NULL";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (count($rows) != 0) {
                $data = array();
                foreach($rows as $row) {
                    // 借閱狀態
                    $status = new Status();
                    
                    $status->book_ID = $row["book_ID"];
                    $status->title = $row["title"];
                    $status->image = $row["image"];

                    // 使用者對書籍的狀態
                    if ($row["deadline"] == null) { // 預約此書（含預約順位，不含逾期期限）
                        $status->book_status = "RESERVE";
                        
                        // 書籍現在的預約人數（不含現已借閱的人）
                        $sql_order = "SELECT start_date
                                        FROM Book_Trace
                                        WHERE book_ID = ? AND end_date IS NULL AND deadline IS NULL
                                        ORDER BY start_date ASC"; // 不得使用 GROUP BY，無法字串比較時間
                        $stmt_order = $conn->prepare($sql_order); 
                        $stmt_order->bind_param("i", $row["book_ID"]);
                        $stmt_order->execute();
                        $result_order = $stmt_order->get_result();
                        $rows_order = $result_order->fetch_all(MYSQLI_ASSOC);

                        $order = 0;
                        foreach($rows_order as $row_order) {
                            if (strtotime($row_order["start_date"]) <= strtotime($row["start_date"])) {
                                $order++;
                            }
                            else {
                                break;
                            }
                        }
                        $status->order = $order;
                    }
                    else { // 借閱此書（不含預約順位，含逾期期限）
                        $status->book_status = "BORROW";
                        
                        $status->deadline = $row["deadline"];
                    }
                    
                    $data[] = $status;
                }

                echo json_encode(array('result' => $data));
            }
            else {
                echo json_encode(array('errorMsg' => '查無借閱狀態。'));
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