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

$category_ids = array_column($categories, 'id');
$add_page_title = 'YetiCave - Добавление лота';
$add_page_css = ['../css/flatpickr.min.css'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_fields = filter_input_array(INPUT_POST,
        [
            'lot-date' => FILTER_DEFAULT,
            'lot-name' => FILTER_DEFAULT,
            'message' => FILTER_DEFAULT,
            'category' => FILTER_DEFAULT,
            'lot-rate' => FILTER_DEFAULT,
            'lot-step' => FILTER_DEFAULT
        ], true);
    $fields_validation_rules = [
        'lot-name' => [
            $validation_rules['required']
        ],
        'category' => [
            $validation_rules['required']
        ],
        'message' => [
            $validation_rules['required'],
            $validation_rules['correct_length']
        ],
        'lot-rate' => [
            $validation_rules['required'],
            $validation_rules['is_numeric'],
            $validation_rules['greater_zero']
        ],
        'lot-step' => [
            $validation_rules['required'],
            $validation_rules['is_numeric'],
            $validation_rules['greater_zero']
        ],
        'lot-date' => [
            $validation_rules['required'],
            $validation_rules['date_format'],
            $validation_rules['date_greater_today']
        ]
    ];
    $errors = validatePostData($form_fields, $fields_validation_rules);
    $error_category_id = validateCategoryId($form_fields['category'], $category_ids);
    $error_file = validateFile('lot-img');

    if ($error_category_id) {
        $errors['category'] = $error_category_id;
    }
    if ($error_file) {
        $errors['lot-img'] = $error_file;
    }
    if (count($errors)) {
        $add_page_data_content = include_template('add.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        $form_fields['lot-img'] = saveFile('lot-img');

        $new_lot_query = <<<SQL
    INSERT INTO lots
    (
        date_create,
        date_end,
        name,
        description,
        image_url,
        price_default,
        price_step,
        author_id,
        category_id
    )
VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)
SQL;
        $new_lot_result = runQuery($link, $new_lot_query, [
            $form_fields['lot-date'],
            mysqli_real_escape_string($link, $form_fields['lot-name']),
            mysqli_real_escape_string($link, $form_fields['message']),
            $form_fields['lot-img'],
            $form_fields['lot-rate'],
            $form_fields['lot-step'],
            $_SESSION['user_id'],
            $form_fields['category']
        ]);

        if ($new_lot_result) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
            exit();
        }
    }
} else {
    $add_page_data_content = include_template('add.php', [
        'categories' => $categories
    ]);
}

print(getLayout($add_page_title, $add_page_data_content, $is_auth, $user_name, $categories, false, $add_page_css));

