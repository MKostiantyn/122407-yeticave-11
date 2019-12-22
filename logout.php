<?php
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($is_auth) {
    $_SESSION = [];
}
header('Location: /');
exit();

