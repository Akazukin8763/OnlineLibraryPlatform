<?php
    // Include config file
    $conn = require_once "config.php";
    
    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM user WHERE username = '".$username."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) == 1 && $password == $row["password"]) {
            session_start();

            // 系統為登入狀態
            $_SESSION["loggedin"] = true;
            // 紀錄該名使用者的變數
            $_SESSION["ID"] = $row["ID"];
            $_SESSION["username"] = $row["username"];

            header("location: welcome.php");
        }
        else {
            function_alert("Incorrect username or password."); 
        }
    }
    else {
        function_alert("Something wrong"); 
    }

    // Close connection
    mysqli_close($link);

    // alert
    function function_alert($message) { 
        echo "<script>
                alert('$message');
                window.location.href='index.php';
            </script>"; 
        return false;
    } 
?>