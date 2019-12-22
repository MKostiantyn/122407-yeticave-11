<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('categories.php');
require_once('validationRules.php');

$lot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$lot_query = <<<SQL
    SELECT l.id as id, l.name AS name, image_url, description, date_end, price_default, price_step, c.name AS category_name
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.id = $lot_id
SQL;
$lot_result = runQuery($link, $lot_query);
$lot_data = mysqli_fetch_assoc($lot_result);

if ($lot_data && count($lot_data)) {
    $lot_name = isset($lot_data['name']) ? $lot_data['name'] : '';
    $lot_bets_query = <<<SQL
    SELECT date_bet, total, name 
    FROM bets b
    JOIN users u ON author_id = u.id
    WHERE b.lot_id = $lot_id
SQL;
    $lot_bets_result = runQuery($link, $lot_bets_query);
    $lot_bets_data = mysqli_fetch_all($lot_bets_result, MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form_fields = filter_input_array(INPUT_POST, ['cost' => FILTER_DEFAULT], true);
        $fields_validation_rules = [
            'cost' => [
                $validation_rules['required'],
                $validation_rules['is_numeric']
            ]
        ];
        $errors = validatePostData($form_fields, $fields_validation_rules);
        $error_min = validateIsMinBet($form_fields['cost'], $lot_data['price_step']);
        if (!count($errors) && $error_min) {
            $errors['cost'] = $error_min;
        }

        if (count($errors)) {
            $lot_page_content = include_template('lot.php', [
                'categories' => $categories,
                'lot' => $lot_data,
                'bets' => $lot_bets_data,
                'is_auth' => $is_auth,
                'errors' => $errors
            ]);
            print(getLayout($lot_name, $lot_page_content, $is_auth, $user_name, $categories));
            exit();
        } else {
            $last_bet_price = count($lot_bets_data) ? max(array_column($lot_bets_data, 'total')) : $lot_data['price_default'];
            $bet_query = <<<SQL
INSERT INTO bets(date_bet, lot_id, total, author_id)
VALUES (NOW(), ?, ?, ?)
SQL;
            $bet_result = runQuery($link, $bet_query, [
                $lot_data['id'],
                $form_fields['cost'] + $last_bet_price,
                $_SESSION['user_id']
            ]);
            header("Location: lot.php?id=" . $lot_data['id']);
            exit();
        }
    }

    $lot_page_content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot_data,
        'bets' => $lot_bets_data,
        'is_auth' => $is_auth
    ]);
    print(getLayout($lot_name, $lot_page_content, $is_auth, $user_name, $categories));
} else {
    http_response_code(404);
    $lot_not_found = include_template('404.php', []);
    print($lot_not_found);
}

