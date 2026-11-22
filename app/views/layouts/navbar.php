
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">

        <a class="navbar-brand fw-bold"
           href="<?= url('products') ?>">
            <span class="text-warning">Vending</span> Machine
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('products') ?>">
                        Products
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= url('products/purchase') ?>">
                        Purchase
                    </a>
                </li>

                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('transactions') ?>">
                            Transaction History
                        </a>
                    </li>
                <?php endif; ?>

            </ul>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-white">
                        Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>
                    </span>

                    <a href="<?= url('logout') ?>"
                       class="btn btn-outline-warning btn-sm">
                        Logout
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</nav>