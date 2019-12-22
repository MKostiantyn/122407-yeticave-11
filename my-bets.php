<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('categories.php');
require_once('validationRules.php');

if(!$is_auth) {
    http_response_code(403);
    $lot_not_found = include_template('error.php', ['error' => 'The page is available only to authorized users!']);
    print($lot_not_found);
    exit();
}

$page_title = 'YetiCave - Мои ставки';
$bets_query = <<<SQL
SELECT date_bet, date_end, total, l.id as lot_id, l.name as lot_name, c.name as category_name, image_url
FROM bets b
JOIN lots l ON lot_id = l.id
JOIN categories c ON l.category_id = c.id
WHERE b.author_id = ?
ORDER BY date_end, date_bet ASC
SQL;
$bets_result = runQuery($link, $bets_query, [$_SESSION['user_id']]);
$bets = mysqli_fetch_all($bets_result, MYSQLI_ASSOC);

$page_data_content = include_template('my-bets.php', [
    'categories' => $categories,
    'bets' => $bets
]);

print(getLayout($page_title, $page_data_content, $is_auth, $user_name, $categories));

