<?php
require_once('helpers.php');
require_once('categories.php');

$validation_rules = [
    'required' => [
        'validate' => function(string $var) : bool {
            return !empty($var);
        },
        'message' => 'Field is required!'
    ],
    'is_numeric' => [
        'validate' => function(string $var) : bool {
            return is_numeric($var);
        },
        'message' => 'Field should have numeric type!'
    ],
    'greater_zero' => [
        'validate' => function(string $var) : bool {
            return $var > 0;
        },
        'message' => 'Field should be greater than 0!'
    ],
    'correct_length' => [
        'validate' => function(string $var) : bool {
            $len = strlen($var);
            return $len > 80 && $len < 2000;
        },
        'message' => 'Description length should be between 80 and 2000 characters!'
    ],
    'date_format' => [
        'validate' => function(string $var) : bool {
            return is_date_valid($var);
        },
        'message' => 'Date should be in format "YYYY-MM-DD"!'
    ],
    'date_greater_today' => [
        'validate' => function(string $var) : bool {
            $diff = strtotime($var) - strtotime('now');
            $days = $diff / (60 * 60 * 24);
            return $days >= 1;
        },
        'message' => 'Date should be greater than the current minimum for one day!'
    ]
];
