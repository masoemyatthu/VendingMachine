<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/jwt.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiController

{

    private $pdo;
    private $product;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->product = new Product($pdo);
    }

    private function requireAuth()
    {
        header('Content-Type: application/json');

        $headers = getallheaders();
        $authHeader = $headers['Authorization']
            ?? $headers['authorization']
            ?? '';

        if (!preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Token is required.'
            ]);

            exit;
        }

        try {
            $token = $matches[1];

            $decoded = JWT::decode(
                $token,
                new Key(JWT_SECRET, 'HS256')
            );

            return $decoded;

        } catch (Throwable $e) {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid or expired token.'
            ]);

            exit;
        }
    }

    #[Route('POST', '/api/login')]
    public function login()
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            http_response_code(422);

            echo json_encode([
                'success' => false,
                'message' => 'Email and password are required.'
            ]);
            return;
        }

        $userModel = new User($this->pdo);
        $user = $userModel->login($email, $password);

        if (!is_array($user)) {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password.'
            ]);
            return;
        }

        $payload = [
            'iss' => JWT_ISSUER,
            'iat' => time(),
            'exp' => time() + 3600,
            'user_id' => $user['id'],
            'email' => $user['email']
        ];

        $token = JWT::encode($payload, JWT_SECRET, 'HS256');

        http_response_code(200);

        echo json_encode([
            'success' => true,
            'token' => $token
        ]);
    }


    #[Route('GET', '/api/products')]
    public function products()
    {
        $user = $this->requireAuth();

        header('Content-Type: application/json');

        try {

            $products = $this->product->getProducts();

            http_response_code(200);

            echo json_encode([

                'success' => true,

                'data' => $products

            ]);

        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([

                'success' => false,

                'message' => 'Internal Server Error.'

            ]);

        }

    }

}


