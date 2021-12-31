<?php
    session_start();  // 使用者變數在 Session 內

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        if ($_SESSION["ID"] === 0) { // 遊客
            // 不可進入
            header('location: welcome.php');
            exit;
        }

        $ID = $_SESSION["ID"];
        $username = $_SESSION["username"];
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

        <title>Online Library Platform｜Trace</title>

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
            
            tr.overflow td {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
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

            import { traceBook } from "./SearchViewModule/View/view.js";
            $("#btn_searchBook").click(function() {
                showTrace();

                var __title = $('#searchTitle').val();
                var __category = [...category_list.keys()];

                if (!(__title.length <= 64)) {
                    return;
                }
                if (__title == '') { // 至少一個字元
                    __title = '_';
                }

                $.ajax({
                    type: "GET",
                    url: "SearchViewModule/Search/searchBook.php",
                    dataType: "json",
                    data: {
                        title: __title,
                        category: __category,
                    },
                    success: function(response) {
                        if (response.result) { // 回傳的 json 中含有 result
                            //console.log(response.result);

                            var result = $("#books");
                            var books = response.result;

                            result.empty();
                            $("#traceBookERR").html("&zwnj;");
                            
                            books.forEach(book => {
                                // 詳細資料
                                var details = $('<tr class="overflow"></tr>');
                                var th = $('<th scope="col" style="width: 15%;"></th>');
                                var tr = $('<td></td>');

                                var insert = $('<span class="glyphicon glyphicon-tags" style="float: center; color: #FFA042;"></span>');
                                var title = $('<span></span>');

                                title.html(book.title);
                                insert.click(function() {
                                    traceBook(book.title);
                                });

                                th.append(insert).appendTo(details);
                                tr.append(title).appendTo(details);
                                
                                result.append(details);
                            });
                        }
                        else {
                            //console.log(response.errorMsg);
                            $("#books").empty();
                            $("#traceBookERR").html(response.errorMsg);

                            if (response.errorMsg == "查無書籍資料。") {
                                var error = $('<tr class="overflow"></tr>');
                                var th = $('<th scope="col" style="width: 15%;"></th>');
                                var tr = $('<td></td>');
                                
                                var insert = $('<span class="glyphicon glyphicon-tags" style="float: center; color: #FFA042;"></span>');
                                var title = $('<span></span>');

                                title.html("將此書列入追蹤");
                                insert.click(function() {
                                    traceBook(__title);

                                    $('#searchTitle').val("");
                                    $("#books").empty();
                                    $("#traceBookERR").html("&zwnj;");
                                });

                                th.append(insert).appendTo(error);
                                tr.append(title).appendTo(error);
                                
                                $("#books").append(error);
                            }
                        }
                    },
                    error: function(jqXHR) {
                        //console.log(jqXHR);
                        $("#books").empty();
                        $("#traceBookERR").html("伺服器連線錯誤。");
                    }
                })
            });

            import { viewTrace } from "./SearchViewModule/View/view.js";
            function showTrace() {
                viewTrace();
            }

            import { logout } from "./AccountModule/Account/account.js";
            $("#btn_logout").click(function() {
                logout();
            })

            window.addEventListener("load", showTrace, false);
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
                                <li onclick="location='trace.php'"><span class="glyphicon glyphicon-list-alt"></span> Trace</li>
                                <li onclick="location='status.php'"><span class="glyphicon glyphicon-dashboard"></span> Status</li>
                                <li onclick="location='history.php'"><span class="glyphicon glyphicon-time"></span> History</li>
                                <li onclick="location='favorite.php'"><span class="glyphicon glyphicon-heart"></span> Favorite</li>
                                <li onclick="location='notification.php'"><span class="glyphicon glyphicon-bell"></span> Notification</li>
                                <li role="separator" class="divider"></li>
                                <li id="btn_logout"><span class="glyphicon glyphicon-log-out"></span> Log out</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav><br><br>
        
        <div class="container-fluid">
            <div class="row" style="height: 94vh;">
                
                <div class="col-sm-2 sidenav">
                    <br>
                    <h3><span class="glyphicon glyphicon-leaf" style="color: #FFA042;"></span> 搜尋書籍</h3>
                    
                    <span id="traceBookERR" style="color:red">&zwnj;</span>
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" placeholder="Search" id="searchTitle">
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-sm" id="btn_searchBook">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>

                    <br>
                    <table class="table table-bordered" style="table-layout: fixed;">
                        <tbody id="books">
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-10">
                    <br>
                    <h3 class="text-center"><span class="glyphicon glyphicon-list-alt" style="color: #FFA042;"></span> 追蹤清單</h3>
                    
                    <div class="col-sm-2">
                    </div>
    
                    <div class="col-sm-8">
                        <table class="table table-bordered" style="table-layout: fixed;">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="traceBooks">
                                <!-- Nothing -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>