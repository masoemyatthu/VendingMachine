<?php

session_start();

require_once __DIR__ . '/../app/helpers/url.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('login'));
    exit;
}

header('Location: ' . url('products'));
exit;