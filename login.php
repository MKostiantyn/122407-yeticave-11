<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('categories.php');
require_once('validationRules.php');

$page_title = 'YetiCave - Вход';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_fields = filter_input_array(INPUT_POST,
        [
            'email' => FILTER_DEFAULT,
            'password' => FILTER_DEFAULT
        ], true);
    $fields_validation_rules = [
        'email' => [
            $validation_rules['required']
        ],
        'password' => [
            $validation_rules['required']
        ]
    ];
    $errors = validatePostData($form_fields, $fields_validation_rules);
    $error_email = validateIsEmailCorrect($link, $form_fields['email']);
    $error_password = !$error_email ? validateIsPasswordCorrect($link, $form_fields['email'], $form_fields['password']) : null;

    if (!isset($errors['email']) && $error_email) {
        $errors['email'] = $error_email;
    }

    if (!isset($errors['password']) && $error_password) {
        $errors['password'] = $error_password;
    }

    if (count($errors)) {
        $page_data_content = include_template('login.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        $email_filtered = filter_var($form_fields['email'], FILTER_VALIDATE_EMAIL);
        $user_query = <<<SQL
SELECT *
FROM users
WHERE email = "$email_filtered"
SQL;
        $user_result = runQuery($link, $user_query);
        $user_data = mysqli_fetch_assoc($user_result);
        if ($user_data && isset($user_data['name']) && isset($user_data['email'])) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_name'] = $user_data['name'];
            $_SESSION['user_email'] = $user_data['email'];
            header('Location: /');
            exit();
        }
    }
} else {
    $page_data_content = include_template('login.php', [
        'categories' => $categories
    ]);
}

print(getLayout($page_title, $page_data_content, $is_auth, $user_name, $categories));

