
<?php

$pageTitle = 'Transaction History';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

<main class="container mt-5 flex-grow-1">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Transaction History</h2>
        <a href="/vendor_machine/public/products" class="btn btn-secondary">
            Back to Products
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
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
                                    <td><?= htmlspecialchars($transaction['id']) ?></td>

                                    <td><?= htmlspecialchars($transaction['user_name']) ?></td>

                                    <td><?= htmlspecialchars($transaction['product_name']) ?></td>

                                    <td><?= htmlspecialchars($transaction['quantity']) ?></td>

                                    <td>
                                        $<?= number_format((float) $transaction['unit_price'], 3) ?>
                                    </td>

                                    <td>
                                        $<?= number_format((float) $transaction['total_price'], 3) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($transaction['created_at'] ?? '-') ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No transactions found.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <div class="text-muted">
                Total Transactions: <?= count($transactions) ?>
            </div>

        </div>
    </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
