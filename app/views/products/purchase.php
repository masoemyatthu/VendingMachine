
<?php

$pageTitle = 'Purchase Product';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

<main class="container mt-5 flex-grow-1">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Purchase Product</h4>
                </div>

                <div class="card-body">

                    <form action="/vendor_machine/public/products/purchase" method="POST">

                        <div class="mb-3">

                            <label class="form-label">Select Product</label>

                            <select name="product_id" class="form-select" required>

                                <option value="">Choose Product</option>

                                <?php foreach ($products as $product): ?>

                                    <option value="<?= $product['id'] ?>">

                                        <?= htmlspecialchars($product['name']) ?>

                                        - $<?= htmlspecialchars($product['price']) ?>

                                        (Stock: <?= htmlspecialchars($product['stock']) ?>)

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Quantity</label>

                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                min="1"
                                placeholder="Enter quantity"
                                required
                            >

                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Purchase Now
                        </button>

                        <a href="/vendor_machine/public/products"
                           class="btn btn-secondary w-100 mt-2">
                            Back to Products
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
