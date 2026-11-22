<?php 

require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../libraries/Route.php";
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../helpers/ProductValidator.php";
require_once __DIR__ . "/../models/Transaction.php";


class ProductsController
{
    private $db;
    private $product;
    private $transaction;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->product = new Product($pdo);
        $this->transaction = new Transaction($pdo);
    }

    private function validateProduct($name, $price, $stock)
    {

        return ProductValidator::validate($name,$price,$stock);

    }

    #[Route('GET', '/products')]
    public function index()
    {
        requireLogin();

        $page = $_GET['page'] ?? 1;
        $sortBy = $_GET['sort'] ?? 'id';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $data = $this->getPaginatedProducts(
            $page,
            $sortBy,
            $sortOrder
        );

        $products = $data['products'];
        $currentPage = $data['currentPage'];
        $totalPages = $data['totalPages'];
        $totalProducts = $data['totalProducts'];

        require_once __DIR__ . '/../views/products/index.php';
    }

    #[Route('GET', '/products/create')]
    public function create()
    {
        requireAdmin();

        require_once __DIR__ . '/../views/products/create.php';
    }

    #[Route('POST', '/products')]
    public function store()
    {

        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        $name = trim($_POST['name'] ?? '');

        $price = $_POST['price'] ?? '';

        $stock = $_POST['stock'] ?? '';

        $errors = $this->validateProduct($name, $price, $stock);

        if (!empty($errors)) {

            http_response_code(422);

            echo implode('<br>', array_map('htmlspecialchars', $errors));

            return;

        }

        $result = $this->product->create($name, $price, $stock);

        if ($result) {

            header('Location: /vendor_machine/public/products?success=created');

            exit;

        }

        http_response_code(500);

        echo "Failed to create product.";

    }

    #[Route('GET', '/products/{id}/edit')]
    public function edit($id)
    {
        requireAdmin();

        $product = $this->product->getById($id);

        if (!$product) {
            http_response_code(404);
            exit('Product not found.');
        }

        require_once __DIR__ . '/../views/products/edit.php';
    }

    #[Route('POST', '/products/{id}/update')]
    public function update($id)
    {

        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {

            http_response_code(400);

            exit("Invalid product ID.");

        }

        $name = trim($_POST['name'] ?? '');

        $price = $_POST['price'] ?? '';

        $stock = $_POST['stock'] ?? '';

        $errors = $this->validateProduct($name, $price, $stock);

        if (!empty($errors)) {

            http_response_code(422);

            echo implode('<br>', array_map('htmlspecialchars', $errors));

            return;

        }

        if (!$this->product->getById($id)) {

            http_response_code(404);

            exit("Product not found.");

        }

        $result = $this->product->update($id, $name, $price, $stock);

        if ($result) {

            header('Location: /vendor_machine/public/products?success=updated');

            exit;

        }

        http_response_code(500);

        echo "Failed to update product.";

    }

    #[Route('POST', '/products/{id}/delete')]
    public function delete($id)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
            return false;
        }

        $result = $this->product->delete($id);

        if ($result) {
            header('Location: /vendor_machine/public/products?success=deleted');
            exit;
        }

        return false;
    }

    public function decreaseStock($id, $quantity)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
            return false;
        }

        if (!filter_var($quantity, FILTER_VALIDATE_INT) || $quantity <= 0) {
            return false;
        }

        return $this->product->decreaseStock($id, $quantity);
    }

    public function getPaginatedProducts($page = 1, $sortBy = 'id', $sortOrder = 'ASC')
    {
        $limit = 5;

        $totalProducts = $this->product->countAll();

        $totalPages = (int) ceil($totalProducts / $limit);

        $page = max(1, min((int) $page, max(1, $totalPages)));

        $offset = ($page - 1) * $limit;

        $products = $this->product->getPaginated(
            $limit,
            $offset,
            $sortBy,
            $sortOrder
        );

        return [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ];
    }

    #[Route('POST', '/products/purchase')]
    public function purchase()
    {
        requireLogin();

        $productId = $_POST['product_id'] ?? '';
        $quantity = $_POST['quantity'] ?? '';

        if (!filter_var($productId, FILTER_VALIDATE_INT) || $productId <= 0) {
            die("Invalid product.");
        }

        if (!filter_var($quantity, FILTER_VALIDATE_INT) || $quantity <= 0) {
            die("Quantity must be greater than zero.");
        }

        $userId = $_SESSION['user_id'];

        $product = $this->product->getById($productId);

        if (!$product) {
            die("Product not found.");
        }

        $unitPrice = $product['price'];
        $totalPrice = $unitPrice * $quantity;

        try {

            $this->db->beginTransaction();

            $stockUpdated = $this->product->decreaseStock($productId, $quantity);

            if (!$stockUpdated) {
                $this->db->rollBack();
                die("Insufficient stock.");
            }

            $result = $this->transaction->create(
                $userId,
                $productId,
                $quantity,
                $unitPrice,
                $totalPrice
            );

            if (!$result) {
                throw new Exception("Transaction saving failed.");
            }

            $this->db->commit();

            header('Location: /vendor_machine/public/products?success=purchased');

            exit;

        } catch (Exception $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            die("Purchase failed. Please try again.");

        }

    }

    #[Route('GET', '/products/purchase')]
    public function purchaseForm()
    {
        requireLogin();

        $products = $this->product->getProducts();

        require_once __DIR__ . '/../views/products/purchase.php';
    }
    
}

?>