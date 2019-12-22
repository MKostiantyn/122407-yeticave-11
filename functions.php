<?php
require_once('helpers.php');
function getDataBaseConnection() {
    return mysqli_connect('localhost', 'root', 'root', 'yeticave');
}
function runQuery(mysqli $link, string $sql, $params = array()) {
    if (!empty($params)) {
        $stmt = db_get_prepare_stmt($link, $sql, $params);
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_query($link, $sql);
    }
    if (!$result) {
        errorQueryHandler($link);
        die();
    }
    return $result;
}
function errorQueryHandler($link) {
    $error = mysqli_error($link);
    $error_content = include_template('error.php', ['error' => $error]);
    print($error_content);
}
function checkIsEmailExist(mysqli $link, string $email) : bool {
    $email_query = <<<SQL
    SELECT email
    FROM users
    WHERE email = "$email"
SQL;
    $result = runQuery($link, $email_query);
    $num = mysqli_num_rows($result);
    return $num > 0;
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
function getLayout(string $title, string $content, bool $auth_status, string $user_name, array $categories = [], bool $is_main_page = false, array $css = []) : string {
    return include_template('layout.php', [
        'title' => $title,
        'content' => $content,
        'is_auth' => $auth_status,
        'user_name' => $user_name,
        'categories' => $categories,
        'is_main_page' => $is_main_page,
        'css' => $css
    ]);
}
function getPostValue(string $name) : string {
    return $_POST[$name] ?? '';
}
function validatePostData(array $fields, array $rules) : array {
    $errors = [];
    foreach ($fields as $key => $value) {
        if (isset($rules[$key])) {
            foreach($rules[$key] as $rule) {
                if (!$rule['validate']($fields[$key])) {
                    $errors[$key] = $rule['message'];
                    break;
                }
            }
        }
    }
    return $errors;
}
function validateCategoryId(string $id, array $category_ids) {
    return $id && !in_array($id, $category_ids) ? 'Choose available category!' : null;
}
function validateFile(string $name) {
    if (!isset($_FILES[$name])) {
        return 'File not uploaded!';
    }

    switch ($_FILES[$name]['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return 'File not uploaded!';
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Exceeded file size limit!';
        default:
            return 'Unknown errors!';
    }

    $file_type = mime_content_type($_FILES[$name]['tmp_name']);
    $available_types = ['image/jpeg', 'image/png'];

    if (!in_array($file_type, $available_types)) {
        return 'Invalid file format! Available formats - jpg, jpeg, png.';
    }

    return null;
}
function validateIsEmailExist(mysqli $link, string $email) {
    $email_filtered = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email_filtered) {
        return 'Please enter valid Email address! Example - emailname@donmain.com';
    }

    if (checkIsEmailExist($link, $email_filtered)) {
        return "Email address is already registered";
    }

    return null;
}

function validateIsEmailCorrect(mysqli $link, string $email) {
    $email_filtered = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email_filtered) {
        return 'Please enter valid Email address! Example - emailname@donmain.com';
    }

    if (!checkIsEmailExist($link, $email_filtered)) {
        return "Email address is not registered";
    }

    return null;
}

function validateIsPasswordCorrect(mysqli $link, string $email, string $password) {
    $email_query = <<<SQL
    SELECT password
    FROM users
    WHERE email = "$email"
SQL;
    $result = runQuery($link, $email_query);
    $result_fetched = mysqli_fetch_assoc($result);
    return password_verify($password, $result_fetched['password']) ? null : 'You entered an incorrect password!';
}
function saveFile(string $name) {
    $tmp_name = $_FILES[$name]['tmp_name'];
    $path = $_FILES[$name]['name'];
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $filename = 'uploads/' . uniqid() . '.' . $extension;
    move_uploaded_file($tmp_name, $filename);
    return $filename;
}
