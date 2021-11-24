<?php
    session_start();  // 使用者變數在 Session 內

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $username = $_SESSION["username"];
        echo "<h1>你好 [".$username."]</h1>";
        echo "<a href='logout.php'>登出</a>";
    }
    else {
        header('location: index.php');
        exit;
    }
?>