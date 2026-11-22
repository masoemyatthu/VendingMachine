<?php 

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/url.php';

class UserController
{
    private $user;

    public function __construct($pdo)
    {
        $this->user = new User($pdo);
    }

    #[Route('GET', '/login')]
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . url('products'));
            exit;
        }

        $message = null;

        require __DIR__ . '/../views/auth/login.php';
    }

    #[Route('GET', '/register')]
    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . url('products'));
            exit;
        }

        $message = null;

        require __DIR__ . '/../views/auth/register.php';
    }

    #[Route('POST', '/register')]
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $message = '';

        if (empty($name) || empty($email) || empty($password)) {
            $message = "All fields are required.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email address.";

        } elseif (strlen($password) < 8) {
            $message = "Password must be at least 8 characters.";

        } else {
            try {
                $this->user->register($name, $email, $password);

                $message = "Registration successful!";

            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = "Email already exists.";
                } else {
                    $message = "Registration failed.";
                }
            }
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    #[Route('POST', '/login')]
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $message = '';

        if (empty($email) || empty($password)) {
            $message = "Email and password are required.";
        } else {

            $user = $this->user->login($email, $password);

            if ($user === "email_not_found") {
                $message = "Email not found.";
            } elseif ($user === "wrong_password") {
                $message = "Incorrect password.";
            } else {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                header('Location: ' . url('products'));
                exit;
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    #[Route('GET', '/logout')]
    public function logout()
    {
        session_unset();
        session_destroy();

        header('Location: ' . url('login'));
        exit;
    }

}

