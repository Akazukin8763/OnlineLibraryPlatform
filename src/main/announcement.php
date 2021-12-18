<?php
    session_start();  // 使用者變數在 Session 內

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        if ($_SESSION["is_admin"] == 1) {
            $ID = $_SESSION["ID"];
            $username = $_SESSION["username"];
        }
        else {
            header('location: non-admin.html'); // 提示頁面，導至首頁
            exit;
        }
    }
    else {
        header('location: index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <title>Online Library Platform - 發布公告</title>

        <style>
            .navbar {
                margin-bottom: 0;
                background-color: #FFA042;
                z-index: 9999;
                border: 0;
                font-size: 12px !important;
                line-height: 1.42857143 !important;
                letter-spacing: 4px;
                border-radius: 0;
            }

            .navbar li a, .navbar .navbar-brand {
                color: #fff !important;
            }

            .navbar-nav li a:hover, .navbar-nav li.active a {
                color: #FFA042 !important;
                background-color: #fff !important;
            }

            .navbar-default .navbar-toggle {
                border-color: transparent;
                color: #fff !important;
            } 

            .sidenav {
                background-color: #f1f1f1;
                height: 100%;
            }

            .navhref {
                color: #BBBBBB;
            }
            .navhref:hover {
                color: #555555;
            }
        </style>
        
        <script type="module">
            import { notifyAnnouncement } from "./ManageModule/Notify/notify.js";
            $("#btn_announcement").click(function() {
                $("#announcementERR").html("");
                $("#submitERR").html("");

                var content = $("#announcement").val();

                notifyAnnouncement(content);
            });
            $("#btn_clear").click(function() {
                $("#submitERR").html("");

                $("#announcement").val("");
            });
        </script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Logo</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#about">ABOUT</a></li>
                        <li><a href="#services">SERVICES</a></li>
                        <li><a href="#portfolio">PORTFOLIO</a></li>
                        <li><a href="#pricing">PRICING</a></li>
                        <li><a href="#contact">CONTACT</a></li>
                    </ul>
                </div>
            </div>
        </nav><br><br>
        
        <div class="container-fluid">
            <div class="row" style="height: 94vh;">
                <!-- 管理員選單 -->
                <div class="col-sm-2 sidenav" style="padding-left: 2%;">
                    <br>
                    <h3><span class="glyphicon glyphicon-tower" style="color: #FFA042;"></span>管理員選單</h3>
                    
                    <br>
                    <div>
                        <i class="glyphicon glyphicon-menu-right" style="color: #FFA042;"></i>
                        <label class="navhref" onclick="window.location.href='manageBook.php'">編輯書籍</label>
                    </div>
                    <div>
                        <i class="glyphicon glyphicon-menu-right" style="color: #FFA042;"></i>
                        <label class="navhref" onclick="window.location.href='checkBook.php'">借閱書籍</label>
                    </div>
                    <div>
                        <i class="glyphicon glyphicon-menu-right" style="color: #FFA042;"></i>
                        <label style="color: #FFA042;">發布公告</label>
                    </div>
                </div>
                
                <br>

                <div class="col-sm-10">
                    <div>
                        <label for="announcement" class="form-label" required="required">Announcement：</label>
                        <span id="announcementERR" style="color:red"></span>
                        <textarea type="text" class="form-control" id="announcement" name="announcement" rows="5"></textarea>
                    </div>

                    <div class="modal-footer">
                        <span id="submitERR" style="color:red; float: left;"></span>
                        <button type="button" class="btn btn-danger" id="btn_clear">Clear</button>
                        <button type="button" class="btn btn-primary" id="btn_announcement">Submit</button>
                    </div>
                </div>
            </div>


        </div>
    </body>
</html>