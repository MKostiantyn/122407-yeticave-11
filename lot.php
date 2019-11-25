<?php
require_once('default.php');
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');

$lot_data = isset($_GET['id']) ? getLotById($_GET['id']) : false;
if ($lot_data && count($lot_data)) {
    $lot_name = isset($lot_data['name']) ? $lot_data['name'] : '';
    $lot_bets = getBetsById($_GET['id']);
    $lot_page_content = include_template('lot.php', [
        'categories' => getCategories(),
        'lot' => $lot_data,
        'bets' => $lot_bets
    ]);
    print(getLayout($lot_name, $lot_page_content, $is_auth, $user_name));
} else {
    http_response_code(404);
}

