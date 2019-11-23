<?php
require_once('helpers.php');
$is_auth = rand(0, 1);
date_default_timezone_set('Europe/Kiev');
$connection = mysqli_connect('localhost', 'root', 'root', 'yeticave');
if ($connection == false) {
    print('Connection error: ' . mysqli_connect_error());
} else {
    print('Connected!');
    mysqli_set_charset($connection, 'utf8');

    $get_new_lots_sql = 'SELECT * FROM lots WHERE date_end > CURRENT_TIMESTAMP ORDER BY date_create DESC';
    $get_new_lots_result = mysqli_query($connection, $get_new_lots_sql);
    $get_new_lots_result_rows = null;
    if ($get_new_lots_result) {
        $get_new_lots_result_rows = mysqli_fetch_all($get_new_lots_result, MYSQLI_ASSOC);
    } else {
        print('Error in SQL - "' . $get_new_lots_sql . '". Error info: ' . mysqli_error($connection));
    }

    $get_categories_sql = 'SELECT * FROM categories';
    $get_categories_result = mysqli_query($connection, $get_categories_sql);
    $get_categories_result_rows = null;
    if ($get_categories_result) {
        $get_categories_result_rows = mysqli_fetch_all($get_categories_result, MYSQLI_ASSOC);
    } else {
        print('Error in SQL - "' . $get_categories_sql . '". Error info: ' . mysqli_error($connection));
    }
}

$user_name = 'Kostiantyn';
function formatPrice(int $price) : string {
    $priceRounded = ceil($price);
    $priceFormatted = $priceRounded < 1000 ? $priceRounded : number_format ($priceRounded , 0 , '.' , ' ');
    return $priceFormatted . ' ₽';
}
function formatTime(int $time) : string {
    return ($time > 9 ? '' : '0') . $time;
}
function getDateRange(string $date_string) : array {
    if (is_date_valid($date_string)) {
        $date_data = [
            'seconds' => 60,
            'minutes' => 60
        ];
        $now_timestamp = time();
        $date_timestamp = strtotime($date_string);
        $date_diff = $date_timestamp > $now_timestamp ? $date_timestamp - $now_timestamp : 0;
        $secondsInHour = $date_data['minutes'] * $date_data['seconds'];
        $hours = floor($date_diff / $secondsInHour);
        $minutes = floor(($date_diff - $hours * $secondsInHour) / $date_data['seconds']);
        return [formatTime($hours), formatTime($minutes)];
    }
    return [];
}

$main_page_content = include_template('main.php', [
    'categories' => $get_categories_result_rows,
    'announcements' => $get_new_lots_result_rows
]);
$layout_page = include_template('layout.php', [
    'title' => 'Yeticave',
    'content' => $main_page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $get_categories_result_rows
]);

print($layout_page);
