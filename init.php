<?php
date_default_timezone_set('Europe/Kiev');
session_start();
$link = getDataBaseConnection();
if (!$link) {
    $error_page_content = include_template('error.php', ['error' => 'Failed to connect to the database: ' . mysqli_connect_error()]);
    print($error_page_content);
    die;
} else {
    mysqli_set_charset($link, "utf8");
    $date_now = new DateTime();
    $mins = $date_now->getOffset() / 60;
    $sgn = ($mins < 0 ? -1 : 1);
    $mins = abs($mins);
    $hrs = floor($mins / 60);
    $mins -= $hrs * 60;
    $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
    $query = "SET time_zone='".$offset."'";
    runQuery($link, $query);
}
