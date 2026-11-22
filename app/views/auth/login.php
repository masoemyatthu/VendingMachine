<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container">

        <div class="min-vh-100 row justify-content-center align-items-center">

            <div class="col-md-5 col-lg-4">

                <div class="card shadow border-0">
                    <div class="card-body p-4">

                        <h3 class="text-center mb-2">Welcome Back</h3>
                        <p class="text-center text-muted mb-4">Login to your account</p>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= url('login') ?>">

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter your password" required>
                            </div>

                            <button type="submit" class="w-100 btn btn-primary">
                                Login
                            </button>

                        </form>

                        <div class="text-center mt-3 mb-0">
                            Don't have an account?
                            <a href="<?= url('register') ?>">Register</a>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>