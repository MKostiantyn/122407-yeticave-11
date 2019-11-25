<?php
require_once('helpers.php');
function dataBaseConnection(): mysqli {
    $connection = mysqli_connect('localhost', 'root', 'root', 'yeticave');
    if (!$connection) {
        throw new Exception(mysqli_connect_error());
    }
    mysqli_set_charset($connection, "utf8");
    return $connection;
}
function runSql(string $query) {
    $connection = dataBaseConnection();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        throw new Exception(mysqli_error($result));
    }
    return $result;
}
function getLots() {
    $query = 'SELECT l.id AS lot_id, l.name AS lot_name, l.date_end AS date_end, price_default, image_url, c.name AS category_name
FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.date_end > CURRENT_TIMESTAMP ORDER BY l.date_create DESC';
    $result = runSql($query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function getLotById(string $id) {
    $query = 'SELECT l.name AS name, image_url, description, date_end, price_default, price_step, c.name AS category_name
FROM lots l
JOIN categories c ON l.category_id = c.id
WHERE l.id = ' . $id;
    $result = runSql($query);
    return mysqli_fetch_assoc($result);
}
function getBetsById(string $id) {
    $query = 'SELECT date_bet, total, name 
FROM bets b
JOIN users u ON author_id = u.id
WHERE b.lot_id =' . $id;
    $result = runSql($query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function getCategories() {
    $query = 'SELECT * FROM categories';
    $result = runSql($query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
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
function escapeString(string $str) : string {
    return htmlspecialchars($str);
}
function getLayout(string $title, string $content, int $auth_status, string $user_name) : string {
    return include_template('layout.php', [
        'title' => $title,
        'content' => $content,
        'is_auth' => $auth_status,
        'user_name' => $user_name,
        'categories' => getCategories()
    ]);
}
