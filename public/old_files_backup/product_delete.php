<?php

session_start();

require_once __DIR__ . '/../app/helpers/auth.php';

requireAdmin();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/ProductsController.php';

$controller = new ProductsController($pdo);

$id = $_POST['id'] ?? '';

if (!$id) {
    header('Location: products.php');
    exit;
}

$controller->delete($id);

header('Location: products.php');
exit;

?>