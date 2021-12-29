<?php
    // https://jam68ty.github.io/BucketTalk/post/php-mysql-login/
    define('DB_SERVER', 'sql303.epizy.com');
    define('DB_USERNAME', 'epiz_30343440');
    define('DB_PASSWORD', NULL);
    define('DB_NAME', 'epiz_30343440_OnlineLibraryPlatform');

    // 連結 MySQL
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($link, 'SET NAMES utf8');

    // 確認連結 MySQL 是否成功
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    else {
        return $link;
    }
?>