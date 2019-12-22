<?php
$is_auth = isset($_SESSION['user_name']);
$user_name = $is_auth ? $_SESSION['user_name'] : '';
