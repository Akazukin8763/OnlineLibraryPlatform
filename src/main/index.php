<?php
    // 初始化 Session
    session_start();
 
    // Check if the user is already logged in, if yes then redirect him to welcome page
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: welcome.php");
        exit;  // 避免重複轉址過多次
    }
?>

<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Index</title>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="module">
            import { login } from "./AccountModule/Account/account.js";
            document.getElementById("btn").addEventListener("click", function() {
                var email = document.getElementById("email").value;
                var password = document.getElementById("password").value;

                login(email, password);
            }, false);
        </script>
    </head>
    <body>
        <div style="width: 100%; position: absolute; top: 15%;">
            <div style="text-align: center;"><h1>Log In</h1></div>

            <form name="loginForm" style="width: 30%; margin: auto;">
                <div class="mb-3">
                    <span for="email" class="form-label" required="required">Email</span>
                    <input type="text" class="form-control" id="email" name="email">
                </div> 
                <div class="mb-3">
                    <span for="password" class="form-label" required="required">Password</span>
                    <a href="forgotPassword.html" class="form-label" style="float: right;">Forgot Password?</a>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <!-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> -->
                    <a href="register.html" class="form-label" >Create Account</a>
                <button type="button" class="btn btn-primary" id="btn" style="float: right;">Log In</button>
            </form>
        </div>
        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        -->
    </body>
</html>