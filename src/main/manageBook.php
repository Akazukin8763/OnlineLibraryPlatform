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

        <title>Online Library Platform - 書籍管理</title>

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

            import { editBook } from "./ManageModule/Manage/manage.js";
            document.getElementById("btn_searchBook").addEventListener("click", function() {
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
                            var result = $("#books");
                            var books = response.result;

                            result.empty();

                            books.forEach(book => {
                                // 詳細資料
                                var details = $('<tr class="overflow"></tr>');

                                $('<th style="vertical-align: middle;" scope="row" rowspan="2"></th>').html(book.title).appendTo(details);
                                $('<td></td>').html(book.image).appendTo(details);
                                $('<td></td>').html(book.author).appendTo(details);
                                $('<td></td>').html(book.publisher).appendTo(details);
                                $('<td></td>').html(book.description).appendTo(details);
                                $('<td></td>').html(book.publish_date).appendTo(details);
                                $('<td></td>').html(book.arrive_date).appendTo(details);

                                var edit = $('<td style="vertical-align: middle;" rowspan="2"></td>');
                                var btn_edit = $('<span class="glyphicon glyphicon-edit" data-toggle="modal" data-target="#modalEditBook" style="float: center; color: #FFA042;"></span>')
                                btn_edit.click(function() {
                                    resetEditERR();

                                    $('#editHeader').html("Edit Book：" + book.title);
                                    $('#editTitle').val(book.title);
                                    $('#editImage').val(book.image);
                                    $('#editAuthor').val(book.author);
                                    $('#editPublisher').val(book.publisher);
                                    $('#editDescription').val(book.description);
                                    $('#editPublishDate').val(book.publish_date.substr(0, 10));
                                    $('#editArriveDate').val(book.arrive_date.substr(0, 10));

                                    for (let [key, value] of category_list)
                                        $("#editCategory" + key)[0].checked = false;
                                    for (let i = 0; i < book.category.length; i++)
                                        $("#editCategory" + book.category[i])[0].checked = true;
                                        
                                    // Modal
                                    // Reset
                                    $('#btn_resetEdit').off('click');
                                    $('#btn_resetEdit').click(function() {
                                        resetEditERR();

                                        $('#editTitle').val(book.title);
                                        $('#editImage').val(book.image);
                                        $('#editAuthor').val(book.author);
                                        $('#editPublisher').val(book.publisher);
                                        $('#editDescription').val(book.description);
                                        $('#editPublishDate').val(book.publish_date.substr(0, 10));
                                        $('#editArriveDate').val(book.arrive_date.substr(0, 10));

                                        for (let [key, value] of category_list)
                                            $("#editCategory" + key)[0].checked = false;
                                        for (let i = 0; i < book.category.length; i++)
                                            $("#editCategory" + book.category[i])[0].checked = true;
                                    });
                                    // Edit
                                    $('#btn_editBook').off('click');
                                    $('#btn_editBook').click(function() {
                                        resetEditERR();

                                        var title = $('#editTitle').val();
                                        var image = $('#editImage').val();
                                        var author = $('#editAuthor').val();
                                        var publisher = $('#editPublisher').val();
                                        var description = $('#editDescription').val();
                                        var publish_date = $('#editPublishDate').val();
                                        var arrive_date = $('#editArriveDate').val();

                                        var category = [];
                                        for (let [key, value] of category_list) {
                                            if ($("#editCategory" + key)[0].checked) {
                                                category.push(key);
                                            }
                                        }

                                        //console.log(book.title)
                                        //console.log(title + " " + image + " " + author + " " + publisher + " " + description + " " + publish_date + " " + arrive_date);
                                        //console.log(category);

                                        editBook(book.title, title, image, author, publisher, description, publish_date, arrive_date, category);
                                    });
                                });
                                edit.append(btn_edit);
                                edit.appendTo(details);

                                result.append(details);

                                // 類別
                                var categories = $('<tr></tr>');
                                var c = $('<th colspan="6"></th>')

                                for (let i = 0; i < book.category.length; i++) {
                                    if (i % 2 == 0) $('<span class="label label-danger"></span>').html(category_list.get(book.category[i])).appendTo(c);
                                    else $('<span class="label label-primary"></span>').html(category_list.get(book.category[i])).appendTo(c);
                                }

                                categories.append(c);
                                result.append(categories);
                            });
                        }
                        else {
                            $("#books").html(response.errorMsg);
                        }
                    },
                    error: function(jqXHR) {
                        console.log(jqXHR);
                    }
                })
            }, false);
            function resetEditERR() {
                $("#editTitleERR").html("");
                $("#editImageERR").html("");
                $("#editAuthorERR").html("");
                $("#editPublisherERR").html("");
                $("#editDescriptionERR").html("");
                $("#editPublishDateERR").html("");
                $("#editArriveDateERR").html("");
                $("#editCategoryERR").html("");
                $("#editERR").html("");
            }

            import { uploadBook } from "./ManageModule/Manage/manage.js";
            document.getElementById("btn_uploadBook").addEventListener("click", function() {
                var title = $('#uploadTitle').val();
                var image = $('#uploadImage').val();
                var author = $('#uploadAuthor').val();
                var publisher = $('#uploadPublisher').val();
                var description = $('#uploadDescription').val();
                var publish_date = $('#uploadPublishDate').val();
                var arrive_date = $('#uploadArriveDate').val();
                
                var category = [];
                for (let [key, value] of category_list) {
                    if ($("#uploadCategory" + key)[0].checked) {
                        category.push(key);
                    }
                }

                //console.log(title + " " + image + " " + author + " " + publisher + " " + description + " " + publish_date + " " + arrive_date);
                //console.log(category);

                clearUploadERR();
                uploadBook(title, image, author, publisher, description, publish_date, arrive_date, category);
            }, false);
            document.getElementById("btn_clearUpload").addEventListener("click", function() {
                clearUpload();
            }, false);
            function clearUploadERR() {
                $("#uploadTitleERR").html("");
                $("#uploadImageERR").html("");
                $("#uploadAuthorERR").html("");
                $("#uploadPublisherERR").html("");
                $("#uploadDescriptionERR").html("");
                $("#uploadPublishDateERR").html("");
                $("#uploadArriveDateERR").html("");
                $("#uploadCategoryERR").html("");
                $("#uploadERR").html("");
            }

            function clearUpload() {
                $('#uploadTitle').val('');
                $('#uploadImage').val('');
                $('#uploadAuthor').val('');
                $('#uploadPublisher').val('');
                $('#uploadDescription').val('');
                $('#uploadPublishDate').val('');
                $('#uploadArriveDate').val('');

                for (let [key, value] of category_list) {
                    $("#uploadCategory" + key)[0].checked = false;
                }
            }

            function createUploadCategory() {
                var uploadCategory = '';
                for (let [key, value] of category_list) {
                    uploadCategory += '<div class="col-md-4">';
                    uploadCategory += '<input type="checkbox" class="form-check-input" id="uploadCategory' + key + '">';
                    uploadCategory += '<label for="uploadCategory' + key + '">&nbsp&nbsp' + value + '</label>';
                    uploadCategory += '</div>';
                }
                $('#uploadCategory').html(uploadCategory);
            }

            function createEditCategory() {
                var editCategory = '';
                for (let [key, value] of category_list) {
                    editCategory += '<div class="col-md-4">';
                    editCategory += '<input type="checkbox" class="form-check-input" id="editCategory' + key + '">';
                    editCategory += '<label for="editCategory' + key + '">&nbsp&nbsp' + value + '</label>';
                    editCategory += '</div>';
                }
                $('#editCategory').html(editCategory);
            }
            
            window.addEventListener("load", createUploadCategory, false);
            window.addEventListener("load", createEditCategory, false);
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
                        <label style="color: #FFA042;">編輯書籍</label>
                    </div>
                    <div>
                        <i class="glyphicon glyphicon-menu-right" style="color: #FFA042;"></i>
                        <a>借閱書籍</a>
                    </div>
                    <div>
                        <i class="glyphicon glyphicon-menu-right" style="color: #FFA042;"></i>
                        <a>發布公告</a>
                    </div>
                </div>
                
                <br>

                <div class="col-sm-10">
                    <!-- 搜尋欄 -->
                    <div class="input-group">
                        <input type="text" class="form-control input-lg" placeholder="Search" id="searchTitle">
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-lg" id="btn_searchBook">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
        
                    <br>
        
                    <table class="table table-bordered" style="table-layout: fixed;">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Image</th>
                                <th scope="col">Author</th>
                                <th scope="col">Publisher</th>
                                <th scope="col">Description</th>
                                <th scope="col">Publish Date</th>
                                <th scope="col">Arrive Date</th>
                                <th scope="col" style="width: 3%;">
                                    <!-- 開啟 Upload Book Modal -->
                                    <span class="glyphicon glyphicon-plus" data-toggle="modal" data-target="#modalUploadBook" style="float: center; color: #FFA042;"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="books">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upload Book Modal -->
            <div class="modal fade" id="modalUploadBook" tabindex="-1" role="dialog" aria-labelledby="modalUploadBookTitle" aria-hidden="true">
                <div class="modal-dialog" role="document" style="padding-top: 2%;">
                    <div class="modal-content">
                        <!-- 上傳書籍 Header -->
                        <div class="modal-header">
                            <span id="uploadHeader" style="font-weight: bold;">Upload Book</span>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!-- 上傳書籍 Body -->
                        <div class="modal-body">
                            <div>
                                <label for="uploadTitle" class="form-label" required="required">Title：</label>
                                <span id="uploadTitleERR" style="color:red"></span>
                                <input type="text" class="form-control" id="uploadTitle" name="uploadTitle">
                            </div>
                            <div>
                                <label for="uploadImage" class="form-label" required="required">Image url：</label>
                                <span id="uploadImageERR" style="color:red"></span>
                                <input type="text" class="form-control" id="uploadImage" name="uploadImage">
                            </div>
                            <div>
                                <label for="uploadAuthor" class="form-label" required="required">Author：</label>
                                <span id="uploadAuthorERR" style="color:red"></span>
                                <input type="text" class="form-control" id="uploadAuthor" name="uploadAuthor">
                            </div>
                            <div>
                                <label for="uploadPublisher" class="form-label" required="required">Publisher：</label>
                                <span id="uploadPublisherERR" style="color:red"></span>
                                <input type="text" class="form-control" id="uploadPublisher" name="uploadPublisher">
                            </div>
                            <div>
                                <label for="uploadDescription" class="form-label" required="required">Description：</label>
                                <span id="uploadDescriptionERR" style="color:red"></span>
                                <textarea type="text" class="form-control" id="uploadDescription" name="uploadDescription"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <label for="uploadPublishDate" class="form-label" required="required">Publish Date：</label>
                                    <span id="uploadPublishDateERR" style="color:red"></span>
                                    <input type="date" class="form-control" id="uploadPublishDate" name="uploadPublishDate">
                                </div> 
                                <div class="col-md-6"> 
                                    <label for="uploadArriveDate" class="form-label" required="required">Arrive Date：</label>
                                    <span id="uploadArriveDateERR" style="color:red"></span>
                                    <input type="date" class="form-control" id="uploadArriveDate" name="uploadArriveDate">
                                </div>
                            </div>
                            <div>
                                <label for="uploadCategory" class="form-label" required="required">Categroy：</label>
                                <span id="uploadCategoryERR" style="color:red"></span>
                                <div class="row" id="uploadCategory">
                                    <!-- createUploadCategory() -->
                                </div>
                            </div>
                        </div>
                        <!-- 上傳書籍 Footer -->
                        <div class="modal-footer">
                            <span id="uploadERR" style="color:red; float: left;"></span>
                            <button type="button" class="btn btn-secondary"  id="btn_closeUpload" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" id="btn_clearUpload">Clear</button>
                            <button type="button" class="btn btn-primary" id="btn_uploadBook">Upload</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Book Modal -->
            <div class="modal fade" id="modalEditBook" tabindex="-1" role="dialog" aria-labelledby="modalEditBookTitle" aria-hidden="true">
                <div class="modal-dialog" role="document" style="padding-top: 2%;">
                    <div class="modal-content">
                        <!-- 編輯書籍 Header -->
                        <div class="modal-header">
                            <span id="editHeader" style="font-weight: bold;">Edit Book</span>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!-- 編輯書籍 Body -->
                        <div class="modal-body">
                            <div>
                                <label for="editTitle" class="form-label" required="required">Title：</label>
                                <span id="editTitleERR" style="color:red"></span>
                                <input type="text" class="form-control" id="editTitle" name="editTitle">
                            </div>
                            <div>
                                <label for="editImage" class="form-label" required="required">Image url：</label>
                                <span id="editImageERR" style="color:red"></span>
                                <input type="text" class="form-control" id="editImage" name="editImage">
                            </div>
                            <div>
                                <label for="editAuthor" class="form-label" required="required">Author：</label>
                                <span id="editAuthorERR" style="color:red"></span>
                                <input type="text" class="form-control" id="editAuthor" name="editAuthor">
                            </div>
                            <div>
                                <label for="editPublisher" class="form-label" required="required">Publisher：</label>
                                <span id="editPublisherERR" style="color:red"></span>
                                <input type="text" class="form-control" id="editPublisher" name="editPublisher">
                            </div>
                            <div>
                                <label for="editDescription" class="form-label" required="required">Description：</label>
                                <span id="editDescriptionERR" style="color:red"></span>
                                <textarea type="text" class="form-control" id="editDescription" name="editDescription"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <label for="editPublishDate" class="form-label" required="required">Publish Date：</label>
                                    <span id="editPublishDateERR" style="color:red"></span>
                                    <input type="date" class="form-control" id="editPublishDate" name="editPublishDate">
                                </div> 
                                <div class="col-md-6"> 
                                    <label for="editArriveDate" class="form-label" required="required">Arrive Date：</label>
                                    <span id="editArriveDateERR" style="color:red"></span>
                                    <input type="date" class="form-control" id="editArriveDate" name="editArriveDate">
                                </div>
                            </div>
                            <div>
                                <label for="editCategory" class="form-label" required="required">Categroy：</label>
                                <span id="editCategoryERR" style="color:red"></span>
                                <div class="row" id="editCategory">
                                    <!-- createEditCategory() -->
                                </div>
                            </div>
                        </div>
                        <!-- 編輯書籍 Footer -->
                        <div class="modal-footer">
                            <span id="editERR" style="color:red; float: left;"></span>
                            <button type="button" class="btn btn-secondary" id="btn_closeEdit" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" id="btn_resetEdit">Reset</button>
                            <button type="button" class="btn btn-primary" id="btn_editBook">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>