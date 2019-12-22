<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('categories.php');
require_once('validationRules.php');

$form_fields = filter_input_array(INPUT_GET, [
    'search' => FILTER_DEFAULT,
    'page' => FILTER_DEFAULT
], true);
$item_per_page = 2;
$form_fields['page'] = $form_fields['page'] ?? 1;
$offset = ($form_fields['page'] - 1) * $item_per_page;
$search_text_escaped = mysqli_real_escape_string($link, $form_fields['search']);
$search_all_items_query = <<<SQL
    SELECT l.name AS name, image_url, description, date_end, price_default, price_step, c.name AS category_name
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE MATCH(l.name, description) AGAINST(?)
SQL;
$search_all_items_result = runQuery($link, $search_all_items_query, [$form_fields['search']]);
$items_count = mysqli_num_rows($search_all_items_result);
$pages_count = ceil($items_count / $item_per_page);
$pages = range(1, $pages_count);

$search_query = <<<SQL
    SELECT l.name AS name, image_url, description, date_end, price_default, price_step, c.name AS category_name
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE MATCH(l.name, description) AGAINST(?)
    LIMIT $item_per_page
    OFFSET $offset
SQL;
$search_result = runQuery($link, $search_query, [
    $form_fields['search']
]);
$lots = mysqli_fetch_all($search_result, MYSQLI_ASSOC);
$page_title = 'YetiCave - Результаты поиска';
$page_data_content = include_template('search.php', [
    'categories' => $categories,
    'search_text' => $form_fields['search'],
    'lots' => $lots,
    'pages' => $pages,
    'current_page' => $form_fields['page']
]);
print(getLayout($page_title, $page_data_content, $is_auth, $user_name, $categories));
