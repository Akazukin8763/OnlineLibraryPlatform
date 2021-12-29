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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Library Platform｜Setting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="module">
      import { changePassword } from "./SettingModule/Setting/setting.js";
      document.getElementById("changePassword").addEventListener("click",function(){
        var new_password = document.getElementById("new_password").value;
    
        changePassword(new_password);
      }, false);
    </script>
    <script type="module">
      import { changeUsername } from "./SettingModule/Setting/setting.js";
      document.getElementById("changeUsername").addEventListener("click",function(){
        var new_username = document.getElementById("new_username").value;
    
        changeUsername(new_username);
      }, false);
    </script>
    <script type="module">
      import { setPreferences } from "./SettingModule/Setting/setting.js";
      document.getElementById("setPreferences").addEventListener("click",function(){
          var category_list = ["action_and_adventure", "alternate_history", "anthology", "chick_lit", "children",
                                      "classic", "comic_book", "coming_of_age", "crime", "drama",
                                      "fairytale", "fantasy", "graphic_novel", "historical_fiction", "horror",
                                      "mystery", "paranormal_romance", "picture_book", "poetry", "political_thriller",
                                      "romance", "satire", "science_fiction", "short_story", "suspense",
                                      "thriller", "western", "young_adult"];
          var category = [];

          for (var i = 0; i < category_list.length; i++) {
              if (document.getElementById(category_list[i]).checked) {
                  category.push(category_list[i]);
              }
          }

          setPreferences(category);
      }, false);
    </script>

        <script type="module">
            import { logout } from "./AccountModule/Account/account.js";
            document.getElementById("btn_logout").addEventListener("click", function() {
                logout();
            }, false);
        </script>

    <style>
      .navbar-nav li a:hover, .navbar-nav li.active a {
        color: #FFA042 !important;
        background-color: #fff !important;
      }
      .card-header {
        color: #fff !important;
        background-color: #FFA042 !important;
      }
      .saveButton {
        color: #FFA042; 
        border-color: #FFA042;
      }
      .saveButton:hover {
        color: #fff;
        background-color: #FFA042;
        border-color: #FFA042;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-fixed-top navbar-dark" style="background-color: #FFA042;">
      <div class="container-fluid">
        <a class="navbar-brand ms-5" href="welcome.php"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-journal-bookmark-fill me-1" viewBox="0 0 18 18"><path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8V1z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/><path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/></svg>
        Online Library Platform
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav flex-row flex-wrap ms-md-auto">
            <li class="nav-item">
              <a class="nav-link active ms-5 me-3" aria-current="page" href="welcome.php#about">ABOUT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active me-3" aria-current="page" href="welcome.php#popular">POPULAR</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active me-3" aria-current="page" href="welcome.php#services">SERVICES</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active me-3" aria-current="page" href="welcome.php#pricing">PRICING</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active me-3" aria-current="page" href="welcome.php#contact">CONTACT</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active me-5" aria-current="page" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 18 18">
                      <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                      <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>
                  </svg>
                  <?php echo $username; ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="setting.php">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 18 18">
                          <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434l.071-.286zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5zm0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78h4.723zM5.048 3.967c-.03.021-.058.043-.087.065l.087-.065zm-.431.355A4.984 4.984 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8 4.617 4.322zm.344 7.646.087.065-.087-.065z"></path>
                      </svg>
                      Setting
                      </a>
                  </li>
                  <li><a class="dropdown-item" href="#">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-range" viewBox="0 0 18 18">
                          <path d="M9 7a1 1 0 0 1 1-1h5v2h-5a1 1 0 0 1-1-1zM1 9h4a1 1 0 0 1 0 2H1V9z"></path>
                          <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"></path>
                      </svg>
                      Trace
                      </a>
                  </li>
                  <li><a class="dropdown-item" href="#">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 18 18">
                          <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"></path>
                          <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"></path>
                      </svg>
                      Status
                      </a>
                  </li>
                  <li><a class="dropdown-item" href="#">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 18 18">
                          <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"></path>
                          <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"></path>
                          <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"></path>
                      </svg>
                      History
                      </a>
                  </li>
                  <li><a class="dropdown-item" href="favorite.php">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 18 18">
                          <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"></path>
                      </svg>
                      Favorite
                      </a>
                  </li>
                  <li><a class="dropdown-item" href="#">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 18 18">
                          <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"></path>
                      </svg>
                      Notfication
                      </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" id="btn_logout">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 18 18">
                          <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"></path>
                          <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"></path>
                      </svg>
                      Log out
                      </a>
                  </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link active me-1" aria-current="page" href="#"></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <h5 class="fs-2 mb-3 d-flex justify-content-center mt-4" style="color: #FFA042;">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-gear-wide-connected me-2" viewBox="0 0 16 16">
              <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434l.071-.286zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5zm0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78h4.723zM5.048 3.967c-.03.021-.058.043-.087.065l.087-.065zm-.431.355A4.984 4.984 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8 4.617 4.322zm.344 7.646.087.065-.087-.065z"/>
          </svg>
          Setting
    </h5>
    <div class="container-sm d-flex justify-content-center mt-4" style="text-align: center;">
      <div class="card bg-light col-md-8 text-center mx-5">
        <h5 class="card-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 17 17">
            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
          </svg>
          Change your Password
        </h5>
        <div class="card-body">
          <div class="mx-5">
            <div class="d-flex justify-content-center row g-3">
              <div class="form-floating col-sm-10">
                <input type="password" id="new_password" name="new_password" class="form-control mb-3" placeholder="Enter New Password">
                <label for="new_password" class="ms-2">Enter New Password</label>
                <div class="d-grid">
                  <button id="changePassword" class="btn btn-outline-danger saveButton">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <h5 class="card-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 17 17">
            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"></path>
          </svg>
          Change your Username
        </h5>
        <div class="card-body">
          <div class="mx-5">
            <div class="d-flex justify-content-center row g-3">
              <div class="form-floating col-sm-10">
                <input id="new_username" name="new_username" class="form-control mb-3" placeholder="Enter New Username">
                <label for="new_username" class="ms-2">Enter New Username</label>
                <div class="d-grid">
                  <button id="changeUsername" class="btn btn-outline-danger saveButton">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <h5 class="card-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-sliders" viewBox="0 0 17 17">
            <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"></path>
          </svg>
          Change your Preferences
        </h5>
        <div class="card-body">
          <div class="mx-5">
            <div class="alert alert-warning" role="alert">Check book types that you <strong>don't like</strong>！And you'll never see them again！<strong>Never！</strong></div>
            <div class="d-flex justify-content-center row g-3">
              <div class="col-sm-10">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="action_and_adventure" name="action_and_adventure" value="1">
                  <label class="form-check-label" for="action_and_adventure">Action/Adventure</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="alternate_history" name="alternate_history" value="1">
                  <label class="form-check-label" for="alternate_history">Alternate History</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="anthology" name="anthology" value="1">
                  <label class="form-check-label" for="anthology">Anthology</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="chick_lit" name="chick_lit" value="1">
                  <label class="form-check-label" for="chick_lit">Chick Lit</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="children" name="children" value="1">
                  <label class="form-check-label" for="children">Children</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="classic" name="classic" value="1">
                  <label class="form-check-label" for="classic">Classic</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="comic_book" name="comic_book" value="1">
                  <label class="form-check-label" for="comic_book">Comic Book</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="coming_of_age" name="coming_of_age" value="1">
                  <label class="form-check-label" for="coming_of_age">Coming of Age</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="crime" name="crime" value="1">
                  <label class="form-check-label" for="crime">Crime</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="drama" name="drama" value="1">
                  <label class="form-check-label" for="drama">Drama</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="fairytale" name="fairytale" value="1">
                  <label class="form-check-label" for="fairytale">Fairytale</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="fantasy" name="fantasy" value="1">
                  <label class="form-check-label" for="fantasy">Fantasy</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="graphic_novel" name="graphic_novel" value="1">
                  <label class="form-check-label" for="graphic_novel">Graphic Novel</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="historical_fiction" name="historical_fiction" value="1">
                  <label class="form-check-label" for="historical_fiction">Historical Fiction</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="horror" name="horror" value="1">
                  <label class="form-check-label" for="horror">Horror</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="mystery" name="mystery" value="1">
                  <label class="form-check-label" for="mystery">Mystery</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="paranormal_romance" name="paranormal_romance" value="1">
                  <label class="form-check-label" for="paranormal_romance">Paranormal Romance</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="picture_book" name="picture_book" value="1">
                  <label class="form-check-label" for="picture_book">Picture Book</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="poetry" name="poetry" value="1">
                  <label class="form-check-label" for="poetry">Poetry</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="political_thriller" name="political_thriller" value="1">
                  <label class="form-check-label" for="political_thriller">Political Thriller</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="romance" name="romance" value="1">
                  <label class="form-check-label" for="romance">Romance</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="satire" name="satire" value="1">
                  <label class="form-check-label" for="satire">Satire</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="science_fiction" name="science_fiction" value="1">
                  <label class="form-check-label" for="science_fiction">Science Fiction</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="short_story" name="short_story" value="1">
                  <label class="form-check-label" for="short_story">Short Story</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="suspense" name="suspense" value="1">
                  <label class="form-check-label" for="suspense">Suspense</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="thriller" name="thriller" value="1">
                  <label class="form-check-label" for="thriller">Thriller</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="western" name="western" value="1">
                  <label class="form-check-label" for="western">Western</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="young_adult" name="young_adult" value="1">
                  <label class="form-check-label" for="young_adult">Young Adult</label>
                </div>
                  <?php 
                      $category = $_SESSION["category"]; // 裡面都是隱藏的類別
                      $result;
                      foreach($category as $c) {
                          $result .= "document.getElementById(\"".$c."\").checked = true;";
                      }
                      echo "<script>".$result."</script>"; 
                  ?>
                <div class="d-grid mt-2">
                  <button id="setPreferences" class="btn btn-outline-danger saveButton">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class='text-muted py-3 mt-4 border-top'>
        <div class='container'>
          <p class='text-center'>Copyright © 2021 Online Library Platform｜<a href='welcome.php' style='text-decoration:none;'>Home</a>｜<a href='welcome.php#about' style='text-decoration:none;'>About</a>｜<a href='welcome.php#popular' style='text-decoration:none;'>Popular</a>｜<a href='welcome.php#services' style='text-decoration:none;'>Services</a>｜Code on <a href='https://github.com/Akazukin8763/OnlineLibraryPlatform' style='text-decoration:none;'><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-github" viewBox="0 0 18 18"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg> GitHub</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>
