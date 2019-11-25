<?php
require_once('default.php');
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');

$main_page_content = include_template('main.php', [
    'categories' => getCategories(),
    'lots' => getLots()
]);
print(getLayout('Yeticave', $main_page_content, $is_auth, $user_name));
