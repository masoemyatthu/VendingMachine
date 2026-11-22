<?php

session_start();

require_once __DIR__ . '/../app/helpers/auth.php';

requireLogin();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');

    exit;

}

require_once __DIR__ . '/../app/config/database.php';

require_once __DIR__ . '/../app/controllers/ProductsController.php';

require_once __DIR__ . '/../app/controllers/TransactionController.php';

$ProductsController = new ProductsController($pdo);

$transactionController = new TransactionController($pdo);

$products = $ProductsController->index();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productId = $_POST['product_id'] ?? '';

    $quantity = $_POST['quantity'] ?? '';

    $message = $transactionController->purchase(

        $_SESSION['user_id'],

        $productId,

        $quantity

    );

}

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Purchase Product</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    </head>

    <body class="bg-light">

        <nav class="navbar navbar-dark bg-dark">

            <div class="container">

                <a class="navbar-brand" href="index.php">Vending Machine</a>

                <a href="products.php" class="btn btn-outline-light btn-sm">

                    Products

                </a>

            </div>

        </nav>

        <div class="container mt-5">

            <div class="row justify-content-center">

                <div class="col-md-6">

                    <div class="card shadow-sm">

                        <div class="card-header bg-primary text-white">

                            <h4 class="mb-0">Purchase Product</h4>

                        </div>

                        <div class="card-body">

                            <?php if ($message): ?>

                                <div class="alert <?= $message === 'Purchase successful!' ? 'alert-success' : 'alert-danger' ?>">

                                    <?= htmlspecialchars($message) ?>

                                </div>

                            <?php endif; ?>

                            <form method="POST">

                                <div class="mb-3">

                                    <label class="form-label">Select Product</label>

                                    <select name="product_id" class="form-select" required>

                                        <option value="">Choose Product</option>

                                        <?php foreach ($products as $product): ?>

                                            <option value="<?= (int) $product['id'] ?>"

                                                <?= (int) $product['stock'] <= 0 ? 'disabled' : '' ?>>

                                                <?= htmlspecialchars($product['name']) ?>

                                                - <?= htmlspecialchars((string) $product['price']) ?>

                                                (Stock: <?= (int) $product['stock'] ?>)

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">Quantity</label>

                                    <input type="number"

                                        name="quantity"

                                        class="form-control"

                                        min="1"

                                        required>

                                </div>

                                <button type="submit" class="btn btn-primary w-100">

                                    Purchase Now

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>