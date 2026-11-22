<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /vendor_machine/public/login');
    exit;
}

header('Location: /vendor_machine/public/products');
exit;

?>