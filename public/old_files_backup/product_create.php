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

    $message = $controller->store();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">
            <a href="index.php" class="navbar-brand">Vending Machine</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>

    </nav>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4">

                        <h3 class="mb-4">Create Product</h3>

                        <?php if (!empty($message)): ?>

                            <div class="alert alert-danger">
                                <?= htmlspecialchars($message) ?>
                            </div>

                        <?php endif; ?>

                        <form method="POST" action="product_create.php">

                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter product name" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Enter price" min="0" step="0.01" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number"name="stock"class="form-control"placeholder="Enter stock quantity"min="0"step="1"required />
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Save Product</button>
                                <a href="products.php" class="btn btn-secondary">Cancel</a>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>