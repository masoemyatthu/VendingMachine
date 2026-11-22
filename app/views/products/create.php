<?php

$pageTitle = 'Create Product';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

<main class="container mt-5 flex-grow-1">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h2 class="mb-4">Create Product</h2>

                    <form action="<?= url('products') ?>" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" name="price" class="form-control"
                                step="0.001" min="0.001" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity Available</label>
                            <input type="number" name="stock" class="form-control"
                                min="0" step="1" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save Product
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