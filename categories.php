<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

$categories_query = '';
$categories_query = <<<SQL
    SELECT * FROM categories
SQL;
$categories_result = runQuery($link, $categories_query);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
