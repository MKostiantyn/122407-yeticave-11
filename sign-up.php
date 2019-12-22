<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('categories.php');
require_once('validationRules.php');

if($is_auth) {
    http_response_code(403);
    $lot_not_found = include_template('error.php', ['error' => 'The page is available only to unauthorized users!']);
    print($lot_not_found);
    exit();
}

$signup_page_title = 'YetiCave - Регистрация нового аккаунта';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_fields = filter_input_array(INPUT_POST,
        [
            'email' => FILTER_DEFAULT,
            'password' => FILTER_DEFAULT,
            'name' => FILTER_DEFAULT,
            'message' => FILTER_DEFAULT
        ], true);
    $fields_validation_rules = [
        'email' => [
            $validation_rules['required']
        ],
        'password' => [
            $validation_rules['required']
        ],
        'name' => [
            $validation_rules['required']
        ],
        'message' => [
            $validation_rules['required']
        ]
    ];
    $errors = validatePostData($form_fields, $fields_validation_rules);
    $error_email = validateIsEmailExist($link, $form_fields['email']);

    if (!isset($errors['email']) && $error_email) {
        $errors['email'] = $error_email;
    }

    if (count($errors)) {
        $signup_page_data_content = include_template('sign-up.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        $new_user_query = <<<SQL
    INSERT INTO users (date_registration, email, name, password, contacts)
    VALUES (NOW(), ?, ?, ?, ?)
SQL;
        $new_user_result = runQuery($link, $new_user_query, [
            $form_fields['email'],
            $form_fields['name'],
            password_hash($form_fields['password'],PASSWORD_DEFAULT),
            mysqli_real_escape_string($link, $form_fields['message'])
        ]);

        if ($new_user_result) {
            header("Location: login.php");
            exit();
        }
    }
} else {
    $signup_page_data_content = include_template('sign-up.php', [
        'categories' => $categories
    ]);
}

print(getLayout($signup_page_title, $signup_page_data_content, $is_auth, $user_name, $categories));

