<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('categories.php');
require_once('getwinner.php');

$lots_query = <<<SQL
    SELECT l.id AS id, l.name AS name, l.date_end AS date_end, price_default, image_url, c.name AS category_name
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.date_end > CURRENT_TIMESTAMP ORDER BY l.date_create DESC
SQL;
$lots_result = runQuery($link, $lots_query);
$lots = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);
$main_page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots
]);
print(getLayout('Yeticave - Home page', $main_page_content, $is_auth, $user_name, $categories, true));
