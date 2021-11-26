<?php
    session_start();  // 使用者變數在 Session 內

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $ID = $_SESSION["ID"];
        $username = $_SESSION["username"];
    }
    else {
        header('location: index.php');
        exit;
    }
?>

<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Welcome</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <?php echo "<h1>你好 [".$username."]</h1>"; ?>

        <a href='logout.php'>登出</a>
        <div>
            <span for="is_admin" class="form-label">Admin</span>
            <input type="text" class="form-control" id="is_admin" name="is_admin">
            <button id="btn" oncl>Submit</button>
        </div>

        <script type="module">
            import { listUser } from "./user_func/user_func.js";
            document.getElementById("btn").addEventListener("click", function() {
                var is_admin = document.getElementById("is_admin").value;
                listUser(is_admin);
            }, false);
        </script>
    </body>
</html>