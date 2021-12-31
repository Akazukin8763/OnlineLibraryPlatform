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

        <title>Online Library Platform｜Check</title>

        <style>
            body {
                overflow-y: hidden;
            }

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

            .fuckyou ul li{
                color: black !important;
                padding-left: 5%;
                width: 190px !important;
            }
            .fuckyou ul li:hover {
                color: #FFA042 !important;
            }
        </style>
        
        <script type="module">
            const category_list = new Map([
                ["action_and_adventure", "Action/Adventure"],
                ["alternate_history", "Alternate History"],
                ["anthology", "Anthology"],
                ["chick_lit", "Chick Lit"],
                ["children", "Children"],
                ["classic", "Classic"],
                ["comic_book", "Comic Book"],
                ["coming_of_age", "Coming of Age"],
                ["crime", "Crime"],
                ["drama", "Drama"],
                ["fairytale", "Fairytale"],
                ["fantasy", "Fantasy"],
                ["graphic_novel", "Graphic Novel"],
                ["historical_fiction", "Historical Fiction"],
                ["horror", "Horror"],
                ["mystery", "Mystery"],
                ["paranormal_romance", "Paranormal Romance"],
                ["picture_book", "Picture Book"],
                ["poetry", "Poetry"],
                ["political_thriller", "Political Thriller"],
                ["romance", "Romance"],
                ["satire", "Satire"],
                ["science_fiction", "Science Fiction"],
                ["short_story", "Short Story"],
                ["suspense", "Suspense"],
                ["thriller", "Thriller"],
                ["western", "Western"],
                ["young_adult", "Young Adult"]
            ]);

            $("#btn_searchBook").click(function() {
                var __book_ID = $("#searchBookID").val();

                $.ajax({
                    type: "GET",
                    url: "SearchViewModule/Search/searchBookByID.php",
                    dataType: "json",
                    data: {
                        book_ID: __book_ID,
                    },
                    success: function(response) {
                        if (response.result) { // 回傳的 json 中含有 result
                            var book = response.result[0];

                            // 書籍資訊
                            var result = $("#bookDetail");
                            result.empty();

                            // Image
                            $('<img src="' + book.image + '.png" style="height: 49vh;">').appendTo(result);

                            // Details
                            var details = $('<div class="caption text-center"><div>');

                            $('<h3></h3>').html(book.title).appendTo(details);

                            var c = $('<h5></h5>');
                            for (let i = 0; i < book.category.length; i++) {
                                if (i % 2 == 0) $('<span class="label label-danger"></span>').html(category_list.get(book.category[i])).appendTo(c);
                                else $('<span class="label label-primary"></span>').html(category_list.get(book.category[i])).appendTo(c);
                            }
                            c.appendTo(details);

                            $('<h4></h4>').html('<span class="glyphicon glyphicon-user"></span>&nbsp;Author：' + book.author).appendTo(details);
                            $('<h4></h4>').html('<span class="glyphicon glyphicon-bookmark"></span>&nbsp;Publihser：' + book.publisher).appendTo(details);
                            $('<h5></h5>').html('<span class="glyphicon glyphicon-time"></span>&nbsp;Publish Date：' + book.publish_date).appendTo(details);
                            $('<h5></h3>').html('<span class="glyphicon glyphicon-time"></span>&nbsp;Arrive Date：' + book.arrive_date).appendTo(details);

                            details.appendTo(result);

                            // 書籍狀態
                            var result = $("#book_status");
                            result.empty();
                            
                            for (let i = 0; i < book.books.length; i++) {
                                $('<tr></tr>').html('<th scope="row">' + book.books[i].book_ID + '</th><td>' + book.books[i].book_status + '</td>').appendTo(result);
                            }
                            for (let i = book.books.length; i < 5; i++) {
                                $('<tr><th scope="row">&zwnj;</th><td></td></tr>').appendTo(result);
                            }
                        }
                        else {
                            //console.log(response.errorMsg);
                            // 書籍資訊
                            var result = $("#bookDetail");
                            result.empty();

                            // Image
                            $('<div style="height: 49vh;"></div>').html(result.errorMsg).appendTo(result);
                            
                            // Details
                            var details = $('<div class="caption text-center"><div>');

                            $('<h3 style="color: red;"></h3>').html(response.errorMsg).appendTo(details);
                            $('<h5></h5>').html('<span class="label label-danger">undefined</span>').appendTo(details);
                            $('<h4></h4>').html('<span class="glyphicon glyphicon-user"></span>&nbsp;Author：undefined').appendTo(details);
                            $('<h4></h4>').html('<span class="glyphicon glyphicon-bookmark"></span>&nbsp;Publihser：undefined').appendTo(details);
                            $('<h5></h5>').html('<span class="glyphicon glyphicon-time"></span>&nbsp;Publish Date：undefined').appendTo(details);
                            $('<h5></h3>').html('<span class="glyphicon glyphicon-time"></span>&nbsp;Arrive Date：undefined').appendTo(details);
                            
                            details.appendTo(result);

                            // 書籍狀態
                            var result = $("#book_status");
                            result.empty();
                            for (let i = 0; i < 5; i++) {
                                $('<tr><th scope="row">&zwnj;</th><td></td></tr>').appendTo(result);
                            }
                        }
                    },
                    error: function(jqXHR) {
                        console.log(jqXHR);
                    }
                })
            });

            import { checkInBook } from "./CheckModule/Check/check.js";
            $("#btn_checkInBook").click(function() {
                clearMsg();

                var ID = $("#checkUserID").val();
                var book_ID = $("#checkBookID").val();

                checkInBook(ID, book_ID);
            })
            import { checkOutBook } from "./CheckModule/Check/check.js";
            $("#btn_checkOutBook").click(function() {
                clearMsg();

                var ID = $("#checkUserID").val();
                var book_ID = $("#checkBookID").val();

                checkOutBook(ID, book_ID);
            })
            function clearMsg() {
                $("#checkUserIDERR").html("&zwnj;");
                $("#checkBookIDERR").html("&zwnj;");
                $("#checkERR").html("&zwnj;");
                $("#resultUserID").html("");
                $("#resultBookID").html("");
                $("#resultStartDate").html("");
                $("#resultEndDate").html("");
                $("#resultDeadline").html("");
                $("#resultPunishDate").html("");
            }

            import { logout } from "./AccountModule/Account/account.js";
            $("#btn_logout").click(function() {
                logout();
            })
        </script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="welcome.php"><span class="glyphicon glyphicon-book"></span> Online Library</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="welcome.php#about">ABOUT</a></li>
                        <li><a href="welcome.php#popular">POPULAR</a></li>
                        <li><a href="welcome.php#services">SERVICES</a></li>
                        <li><a href="welcome.php#pricing">PRICING</a></li>
                        <li><a href="welcome.php#contact">CONTACT</a></li>

                        <li class="dropdown fuckyou">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#navbarDropdown">
                                <span class="glyphicon glyphicon-user"></span>
                                <?php echo $username; ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-left w-100" aria-labelledby="navbarDropdown">
                                <li onclick="location='setting.php'"><span class="glyphicon glyphicon-cog"></span> Setting</li>
                                <li onclick="location='welcome.php'"><span class="glyphicon glyphicon-list-alt"></span> Trace</li>
                                <li onclick="location='welcome.php'"><span class="glyphicon glyphicon-dashboard"></span> Status</li>
                                <li onclick="location='welcome.php'"><span class="glyphicon glyphicon-time"></span> History</li>
                                <li onclick="location='favorite.php'"><span class="glyphicon glyphicon-heart"></span> Favoirte</li>
                                <li onclick="location='welcome.php'"><span class="glyphicon glyphicon-bell"></span> Notification</li>
                                <li role="separator" class="divider"></li>
                                <li id="btn_logout"><span class="glyphicon glyphicon-log-out"></span> Log out</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav><br><br>
        
        <div class="container-fluid">
            <div class="row" style="height: 100vh;">
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
                        <label style="color: #FFA042;">借閱書籍</label>
                    </div>
                    <div>
                        <i class="glyphicon glyphicon-menu-right" style="color: #FFA042;"></i>
                        <label class="navhref" onclick="window.location.href='announcement.php'">發布公告</label>
                    </div>
                </div>
                
                <br>

                <div class="col-sm-10">
                    <!-- 搜尋欄 -->
                    <div class="input-group">
                        <span class="input-group-addon">Book ID</span>
                        <input type="text" class="form-control input-lg" placeholder="Search" id="searchBookID">
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-lg" id="btn_searchBook">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
        
                    <br>

                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail" id="bookDetail">
                                <div style="height: 49vh;"></div>

                                <div class="caption text-center">
                                    <h3>undefined</h3>
                                    <h5><span class="label label-danger">undefined</span></h5>
                                    <h4><span class="glyphicon glyphicon-user"></span>&nbsp;Author：undefined</h4>
                                    <h4><span class="glyphicon glyphicon-bookmark"></span>&nbsp;Publihser：undefined</h4>
                                    <h5><span class="glyphicon glyphicon-time"></span>&nbsp;Publish Date：undefined</h5>
                                    <h5><span class="glyphicon glyphicon-time"></span>&nbsp;Arrive Date：undefined</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-8">
                            <div style="height: 34vh;">
                                <table class="table table-bordered" style="table-layout: fixed;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" style="width: 25%;">Book ID</th>
                                            <th scope="col">Book Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="book_status">
                                        <tr>
                                            <th scope="row">&zwnj;</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">&zwnj;</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">&zwnj;</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">&zwnj;</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">&zwnj;</th>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <span id="checkUserIDERR" style="color:red">&zwnj;</span>

                                    <div class="input-group">
                                        <span class="input-group-addon">User ID</span>
                                        <input type="text" class="form-control input-lg" id="checkUserID">
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <span id="checkBookIDERR" style="color:red">&zwnj;</span>

                                    <div class="input-group">
                                        <span class="input-group-addon">Book ID</span>
                                        <input type="text" class="form-control input-lg" id="checkBookID">
                                    </div>
                                </div>
                                
                                <br><br><br><br>

                                <div class="col-md-12">
                                    <span id="checkERR" style="color:red">&zwnj;</span>

                                    <table class="table table-bordered" style="table-layout: fixed;">
                                        <tbody id="books">
                                            <tr>
                                                <th scope="row">User ID</th>
                                                <td id="resultUserID"></td>
                                                <th scope="row">Book ID</th>
                                                <td id="resultBookID"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Start Date</th>
                                                <td colspan="3" id="resultStartDate"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">End Date</th>
                                                <td colspan="3" id="resultEndDate"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Deadline</th>
                                                <td colspan="3" id="resultDeadline"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Punished Date</th>
                                                <td colspan="3" id="resultPunishDate"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="col-md-6 d-flex justify-content-center text-center">
                                        <button type="button" class="btn btn-primary" id="btn_checkInBook">Check In</button>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-center text-center">
                                        <button type="button" class="btn btn-danger" id="btn_checkOutBook">Check Out</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>