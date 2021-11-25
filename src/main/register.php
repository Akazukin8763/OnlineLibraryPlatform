<?php 
    // Include config file
    $conn = require_once("config.php");

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        // 檢查電子郵件是否重複註冊
        $checkEmail = "SELECT * FROM user WHERE email = '".$email."'";
        if (mysqli_num_rows(mysqli_query($conn, $checkEmail)) != 0) {
            return function_alert("Email is invalid or already taken.");
        }

        // 檢查使用者名稱是否重複
        $checkUsername = "SELECT * FROM user WHERE username = '".$username."'";
        if (mysqli_num_rows(mysqli_query($conn, $checkUsername)) != 0) {
            return function_alert("Username has already taken.");
        }
        
        $sql = "INSERT INTO user (username, email, password, is_admin)
                VALUES ('".$username."', '".$email."', '".$password."', 0)";

        if (mysqli_query($conn, $sql)){
            header('location: index.php');
            exit;
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    // Close connection
    mysqli_close($conn);

    // alert
    function function_alert($message) { 
        echo "<script>
                alert('$message');
                window.location.href='register.php';
            </script>"; 
        return false;
    } 
?>

<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Register</title>

        <script>
            function validateForm() {
                var password = document.forms["registerForm"]["password"].value;
                var passwordConfirm = document.forms["registerForm"]["passwordConfirm"].value;
                if(password.length < 6){
                    alert("密碼長度不足");
                    return false;
                }
                if (password != passwordConfirm) {
                    alert("請確認密碼是否輸入正確");
                    return false;
                }
            }
        </script>
    </head>

    <body>
        <div style="width: 100%; position: absolute; top: 15%;">
            <div style="text-align: center;"><h1>Registration</h1></div>

            <form name="registerForm" method="post" action="register.php" onsubmit="return validateForm()" style="width: 30%; margin: auto;">
                <div class="mb-3">
                    <span for="username" class="form-label" required="required">Username</span>
                    <input type="text" class="form-control" id="username" name="username">
                </div>    
                <div class="mb-3">
                    <span for="email" class="form-label" required="required">Email</span>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                </div>
                <div class="mb-3">
                    <span for="password" class="form-label" required="required">Password</span>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3">
                    <span for="passwordConfirm" class="form-label" required="required">Comfirm Password</span>
                    <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm">
                </div>
                <!-- <a href="https:/google.com" class="form-label" style="float: right;">Forgot Password?</a> -->
                <!-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> -->
                <button type="submit" class="btn btn-primary" id="submit" style="float: right;">Register</button>
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