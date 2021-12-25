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

        <title>Online Library Platform - 我的最愛</title>

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
            
            var folderList = Array();

            import { insertFavorite } from "./FavoriteModule/Favorite/favorite.js";
            $("#btn_searchBook").click(function() {
                showFavorite();

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
                            $("#favoriteBookERR").html("&zwnj;");
                            
                            books.forEach(book => {
                                // 詳細資料
                                var details = $('<tr class="overflow"></tr>');
                                var th = $('<th scope="col" style="width: 15%;"></th>');
                                var tr = $('<td></td>');

                                var insert = $('<span class="glyphicon glyphicon-heart-empty" style="float: center; color: #FFA042;"></span>');
                                var title = $('<span></span>');

                                title.html(book.title);
                                insert.click(function() {
                                    insertFavorite(book.title);
                                });

                                th.append(insert).appendTo(details);
                                tr.append(title).appendTo(details);
                                
                                result.append(details);
                            });
                        }
                        else {
                            //console.log(response.errorMsg);
                            $("#books").empty();
                            $("#favoriteBookERR").html(response.errorMsg);
                        }
                    },
                    error: function(jqXHR) {
                        //console.log(jqXHR);
                        $("#books").empty();
                        $("#favoriteBookERR").html("伺服器連線錯誤。");
                    }
                })
            });

            $("#btn_createList").click(function() {
                var folder = $("#createListName").val();
                
                if (!(0 < folder.length && folder.length <= 16)) {
                    if (!(0 < folder.length)) $("#createListERR").html("請輸入資料夾名稱。");
                    else $("#createListERR").html("長度需小於 16 個字元。");
                    return;
                }

                // 檢查有無相同
                var length = $("#currentListLength").val();
                var same = false;
                for (let i = 0; i < length; i++) {
                    var listName = $('#currentList' + i + '').val();
                    if (listName == folder) {
                        same = true;
                        break;
                    }
                }

                if (same) {
                    $("#createListERR").html("資料夾名稱不得重複。");
                }
                else {
                    // 顯示列表
                    $('<div class="thumbnail"><label class="glyphicon glyphicon-plus" style="color: #9C9C9C;"></label><label>&nbsp;' + folder + '</label></div>').appendTo("#favoriteFolder");
                    $("#modalCreateList").modal("hide");
                    $("#createListName").val("");

                    // 新增名稱
                    var v = $('<input id="currentList'+ length++ +'"></input>');
                    v.val(folder);
                    v.appendTo($("#currentList"));

                    // 更新長度
                    $("#currentListLength").val(length);
                }
            });

            // Edit Modal
            $("#updateModal").click(function() {
                $("#updateModal").addClass("active");
                $("#removeModal").removeClass("active");
                $("#deleteModal").removeClass("active");
                $("#editStatus").val("update");
                
                // Set Content
                var edit = $("#selectEdit");
                edit.empty();
                
                $('<label>Move \"' + $("#editBookTitle").val() + '\" from \"' + $("#editBookFolder").val() + '\" to&nbsp;</label>').appendTo(edit);
                
                var length = $("#currentListLength").val();
                var selection = $('<select id="selectListName"></select>');
                for (let i = 0; i < length; i++) {
                    var v = $('#currentList' + i + '').val();
                    $('<option value="' + v + '">' + v + '</option>').appendTo(selection);
                }
                selection.appendTo(edit);
            });
            $("#removeModal").click(function() {
                $("#updateModal").removeClass("active");
                $("#removeModal").addClass("active");
                $("#deleteModal").removeClass("active");
                $("#editStatus").val("remove");

                // Set Content
                var edit = $("#selectEdit");
                edit.empty();
                $('<label>Remove \"' + $("#editBookTitle").val() + '\" from \"' + $("#editBookFolder").val() + '\"</label>').appendTo(edit);
            });
            $("#deleteModal").click(function() {
                $("#updateModal").removeClass("active");
                $("#removeModal").removeClass("active");
                $("#deleteModal").addClass("active");
                $("#editStatus").val("delete");

                // Set Content
                var edit = $("#selectEdit");
                edit.empty();
                $('<label>Delete folder \"' + $("#editBookFolder").val() + '\" and move all book to "DEFAULT"</label>').appendTo(edit);
            });

            import { updateFavorite, removeFavorite, deleteFavoriteList } from "./FavoriteModule/Favorite/favorite.js";
            $("#btn_editList").click(function() {
                var status = $("#editStatus").val();
                if (status == "update") {
                    updateFavorite($("#editBookTitle").val(), $("#selectListName").val());
                }
                else if (status == "remove") {
                    removeFavorite($("#editBookTitle").val());
                }
                else if (status == "delete") {
                    deleteFavoriteList($("#editBookFolder").val());
                }
            });

            import { viewFavorite } from "./SearchViewModule/View/view.js";
            function showFavorite() {
                viewFavorite();
            }
            
            window.addEventListener("load", showFavorite, false);
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
                    </ul>
                </div>
            </div>
        </nav><br><br>
        
        <div class="container-fluid">
            <div class="row" style="height: 94vh;">
                
                <div class="col-sm-2 sidenav">
                    <br>
                    <h3><span class="glyphicon glyphicon-leaf" style="color: #FFA042;"></span>搜尋書籍</h3>
                    
                    <span id="favoriteBookERR" style="color:red">&zwnj;</span>
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
                    <h3 class="text-center">我的最愛</h3>
                    <h3><span class="glyphicon glyphicon-th-list" data-toggle="modal" data-target="#modalCreateList" style="float: center; color: #FFA042;"></span><span>&nbsp;創建清單</span></h3>
                    
                    <div class="container-fluid" id="favoriteFolder">
                        <!-- Nothing -->
                    </div>
                </div>

                <!-- CreateList Modal -->
                <div class="modal fade" id="modalCreateList" tabindex="-1" role="dialog" aria-labelledby="modalCreateList" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="padding-top: 2%;">
                        <div class="modal-content">
                            <!-- CreateList Header -->
                            <div class="modal-header">
                                <span id="createListHeader" style="font-weight: bold;">Create List</span>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- CreateList Body -->
                            <div class="modal-body">
                                <div>
                                    <label for="createListName" class="form-label" required="required">Folder Name：</label>
                                    <span id="createListERR" style="color:red"></span>
                                    <input type="text" class="form-control" id="createListName" name="createListName">
                                </div>
                            </div>
                            <!-- CreateList Footer -->
                            <div class="modal-footer">
                                <span id="createERR" style="color:red; float: left;"></span>
                                <button type="button" class="btn btn-secondary"  id="btn_closeUpload" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="btn_createList">Create</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EditList Modal -->
                <div class="modal fade" id="modalEditList" tabindex="-1" role="dialog" aria-labelledby="modalEditList" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="padding-top: 2%;">
                        <div class="modal-content">
                            <!-- EditList Header -->
                            <div class="modal-header">
                                <span id="editListHeader" style="font-weight: bold;">Edit List</span>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- EditList Body -->
                            <div class="modal-body">
                                <ul class="nav nav-tabs">
                                    <li role="presentation" class="active" id="updateModal"><a>Update Favorite</a></li>
                                    <li role="presentation" id="removeModal"><a>Remove Favorite</a></li>
                                    <li role="presentation" id="deleteModal"><a>Delete Favorite List</a></li>
                                </ul>

                                <br>
                                <div id="selectEdit">
                                    <!-- Nothing -->
                                </div>
                            </div>
                            <!-- EditList Footer -->
                            <div class="modal-footer">
                                <span id="editListERR" style="color:red; float: left;"></span>
                                <button type="button" class="btn btn-secondary"  id="btn_closeUpload" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="btn_editList">Edit</button>
                            </div>
                        </div>
                    </div>
                    <!-- Save Status -->
                    <input type="text" id="editBookFolder" style="display: none;"></input>
                    <input type="text" id="editBookTitle" style="display: none;"></input>
                    <select id="editStatus" style="display: none;">
                        <option value="update"></option>
                        <option value="remove"></option>
                        <option value="delete"></option>
                    </select>
                    <div id="currentList" style="display: none;">
                        <!-- Nothing -->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>