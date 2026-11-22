<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');

    exit;

}

require_once __DIR__ . '/../app/config/database.php';

require_once __DIR__ . '/../app/controllers/TransactionController.php';

$controller = new TransactionController($pdo);

$transactions = $controller->index();

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Transaction History</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"

            rel="stylesheet">

    </head>

    <body class="bg-light">

        <nav class="navbar navbar-dark bg-dark">

            <div class="container">

                <a class="navbar-brand" href="index.php">

                    Vending Machine

                </a>

                <a href="purchase.php" class="btn btn-outline-light btn-sm">

                    Purchase

                </a>

            </div>

        </nav>

        <div class="container mt-5">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3>Transaction History</h3>

                <a href="purchase.php" class="btn btn-primary">

                    + New Purchase

                </a>

            </div>

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">

                                <tr>

                                    <th>ID</th>

                                    <th>Customer</th>

                                    <th>Product</th>

                                    <th>Quantity</th>

                                    <th>Unit Price</th>

                                    <th>Total Price</th>

                                    <th>Date</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($transactions)): ?>

                                    <?php foreach ($transactions as $transaction): ?>

                                        <tr>

                                            <td><?= (int) $transaction['id'] ?></td>

                                            <td>

                                                <?= htmlspecialchars($transaction['user_name']) ?>

                                            </td>

                                            <td>

                                                <?= htmlspecialchars($transaction['product_name']) ?>

                                            </td>

                                            <td>

                                                <?= (int) $transaction['quantity'] ?>

                                            </td>

                                            <td>

                                                <?= number_format((float) $transaction['unit_price'], 2) ?>

                                            </td>

                                            <td>

                                                <?= number_format((float) $transaction['total_price'], 2) ?>

                                            </td>

                                            <td>

                                                <?= htmlspecialchars($transaction['created_at']) ?>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="7" class="text-center">

                                            No transactions found.

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>