<?php 

    session_start();

    require_once __DIR__ . '/../app/helpers/auth.php';

    requireLogin();

    if(!isset($_SESSION['user_id'])){
        header('Location: login.php');
        exit;
    }

    require_once __DIR__ . '/../app/config/database.php';
    require_once __DIR__ . '/../app/controllers/ProductsController.php';

    $controller = new ProductsController($pdo);
    $page = $_GET['page'] ?? 1;
    $sortBy = $_GET['sort'] ?? 'id';
    $sortOrder = $_GET['order'] ?? 'ASC';

    $result = $controller->getPaginatedProducts(
        $page,
        $sortBy,
        $sortOrder
    );

    $products = $result['products'];
    $currentPage = $result['currentPage'];
    $totalPages = $result['totalPages'];
    $totalProducts = $result['totalProducts'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Vending Machine</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">
            <a href="index.php" class="navbar-brand">Vending Machine</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>

    </nav>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Product Management</h3>
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="product_create.php" class="btn btn-primary">+ Add Product</a>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <form method="GET" class="mb-3 d-flex gap-2">

                        <select name="sort" class="form-select w-auto">
                            <option value="id">ID</option>
                            <option value="name">Name</option>
                            <option value="price">Price</option>
                            <option value="stock">Quantity</option>
                        </select>

                        <select name="order" class="form-select w-auto">
                            <option value="ASC">Ascending</option>
                            <option value="DESC">Descending</option>
                        </select>

                        <button type="submit" class="btn btn-primary">Sort</button>

                    </form>

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <?php if($_SESSION['role'] === 'admin'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string) $product['id']) ?></td>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars((string) $product['price']) ?></td>
                                        <td><?= htmlspecialchars((string) $product['stock']) ?></td>

                                        <?php if($_SESSION['role'] === 'admin'): ?>

                                            <td>

                                                <a href="product_edit.php?id=<?= (int) $product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>

                                                <form method="POST" action="product_delete.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>

                                            </td>
                                        <?php endif; ?>
                                        
                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4" class="text-center text-muted">No products found.</td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                    <nav>

                        <ul class="pagination">

                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>&sort=<?= urlencode($sortBy) ?>&order=<?= urlencode($sortOrder) ?>">Previous</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                                <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">

                                    <a class="page-link" href="?page=<?= $i ?>&sort=<?= urlencode($sortBy) ?>&order=<?= urlencode($sortOrder) ?>">
                                        <?= $i ?>
                                    </a>

                                </li>

                            <?php endfor; ?>

                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>&sort=<?= urlencode($sortBy) ?>&order=<?= urlencode($sortOrder) ?>">Next</a>
                            </li>

                        </ul>

                    </nav>

                    <p class="text-muted">Total Products: <?= $totalProducts ?> </p>

                </div>

            </div>

        </div>

    </div>

    
</body>
</html>