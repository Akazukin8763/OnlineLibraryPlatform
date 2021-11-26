<?php
    // Include config file
    $conn = require_once "../config.php";
    
    class User {
        public $ID;
        public $username;
        public $email;
        public $password;
        public $is_admin;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $is_admin = $_POST["is_admin"];

        if ($is_admin != null) {
            $sql = "SELECT * FROM User WHERE is_admin = '$is_admin'";
            $result = $conn->query($sql);
    
            if ($result) {
                $data = array();
                
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                    $user = new User();
                    $user->ID = $row["ID"];
                    $user->username = $row["username"];
                    $user->email = $row["email"];
                    $user->password = $row["password"];
                    $user->is_admin = $row["is_admin"];
                    $data[] = $user;
                }
            
                echo json_encode($data); // 把資料轉換為 JSON
            }
            else {
                echo json_encode(array('errorMsg' => '查無資料'));
            }
        }
        else {
            echo json_encode(array('errorMsg' => '資料輸入有誤'));
        }
    }
    else {
        echo json_encode(array('errorMsg' => '請求無效，只允許 POST 方式訪問'));
    }
?>