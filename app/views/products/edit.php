
<?php

$pageTitle = 'Edit Product';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

<main class="container mt-5 flex-grow-1">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h2 class="mb-4">Edit Product</h2>

                    <form action="<?= url('products/' . (int) $product['id'] . '/update') ?>" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Product Name</label>

                            <input type="text" name="name" class="form-control"
                                value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price (USD)</label>

                            <input type="number" name="price" class="form-control"
                                step="0.001" min="0.001"
                                value="<?= htmlspecialchars($product['price']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity Available</label>

                            <input type="number" name="stock" class="form-control"
                                min="0" step="1"
                                value="<?= htmlspecialchars($product['stock']) ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update Product
                        </button>

                        <a href="<?= url('products') ?>" class="btn btn-secondary">
                            Back
                        </a>

                    </form>

                </div>
            </div>

        </div>
    </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>