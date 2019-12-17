<?php
date_default_timezone_set('Europe/Kiev');
$link = getDataBaseConnection();
if (!$link) {
    $error_page_content = include_template('error.php', ['error' => 'Failed to connect to the database: ' . mysqli_connect_error()]);
    print($error_page_content);
    die;
} else {
    mysqli_set_charset($link, "utf8");
}
