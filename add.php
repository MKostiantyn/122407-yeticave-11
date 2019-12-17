<?php
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('categories.php');
require_once('validationRules.php');

$category_ids = array_column($categories, 'id');
$add_page_title = 'YetiCave - Добавление лота';
$add_page_css = ['../css/flatpickr.min.css'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_field_name = 'lot-img';
    $form_fields = [
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
    $errors = validatePostData($form_fields, $category_ids);
    $error_file = validateFile($file_field_name);

    if ($error_file) {
        $errors[$file_field_name] = $error_file;
    }
    if (count($errors)) {
        $add_page_data_content = include_template('add.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        $lot_data = filter_input_array(INPUT_POST,
            [
                'lot-date' => FILTER_DEFAULT,
                'lot-name' => FILTER_DEFAULT,
                'message' => FILTER_DEFAULT,
                'category' => FILTER_DEFAULT,
                'lot-rate' => FILTER_DEFAULT,
                'lot-step' => FILTER_DEFAULT
            ], true);
        $lot_data['lot-img'] = saveFile($file_field_name);

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
            $lot_data['lot-date'],
            $lot_data['lot-name'],
            $lot_data['message'],
            $lot_data['lot-img'],
            $lot_data['lot-rate'],
            $lot_data['lot-step'],
            1,
            $lot_data['category']
        ]);

        if ($new_lot_result) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        }
    }
} else {
    $add_page_data_content = include_template('add.php', [
        'categories' => $categories
    ]);
}

print(getLayout($add_page_title, $add_page_data_content, $is_auth, $user_name, $categories, false, $add_page_css));

