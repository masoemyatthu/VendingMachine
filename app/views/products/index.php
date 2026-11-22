
<?php

$pageTitle = 'Product List';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

<main class="container mt-5 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Product List</h2>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="/vendor_machine/public/products/create" class="btn btn-primary">
                + Create Product
            </a>
        <?php endif; ?>
    </div>

    <form method="GET" action="/vendor_machine/public/products" class="row g-2 mb-3">

        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="id" <?= $sortBy === 'id' ? 'selected' : '' ?>>Sort by ID</option>
                <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Sort by Name</option>
                <option value="price" <?= $sortBy === 'price' ? 'selected' : '' ?>>Sort by Price</option>
                <option value="stock" <?= $sortBy === 'stock' ? 'selected' : '' ?>>Sort by Stock</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="order" class="form-select">
                <option value="ASC" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                <option value="DESC" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Descending</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Sort</button>
        </div>

    </form>

    <table class="table table-bordered mt-3">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($products as $product): ?>

                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>

                    <td>
                        <?php if ($_SESSION['role'] === 'admin'): ?>

                            <a href="/vendor_machine/public/products/<?= (int) $product['id'] ?>/edit"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form method="POST"
                                  action="/vendor_machine/public/products/<?= (int) $product['id'] ?>/delete"
                                  style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this product?');">

                                <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">

                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>

                            </form>

                        <?php endif; ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <div class="d-flex justify-content-between align-items-center mt-3">

        <div>
            Showing <?= count($products) ?> of <?= $totalProducts ?> products
        </div>

        <nav>
            <ul class="pagination mb-0">

                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="?page=<?= $currentPage - 1 ?>&sort=<?= urlencode($sortBy) ?>&order=<?= urlencode($sortOrder) ?>">
                            Previous
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
                        <a class="page-link"
                           href="?page=<?= $i ?>&sort=<?= urlencode($sortBy) ?>&order=<?= urlencode($sortOrder) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="?page=<?= $currentPage + 1 ?>&sort=<?= urlencode($sortBy) ?>&order=<?= urlencode($sortOrder) ?>">
                            Next
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>

    </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
