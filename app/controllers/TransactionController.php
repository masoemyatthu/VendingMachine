<?php

require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Product.php';

class TransactionController
{
    private $db;
    private $transaction;
    private $product;

    public function __construct($pdo)
    {
        $this->db = $pdo;

        $this->transaction = new Transaction($pdo);
        $this->product = new Product($pdo);
    }

    public function purchase($userId, $productId, $quantity)
    {
        if (!filter_var($userId, FILTER_VALIDATE_INT) || $userId <= 0) {
            return "Invalid user.";
        }

        if (!filter_var($productId, FILTER_VALIDATE_INT) || $productId <= 0) {
            return "Invalid product.";
        }

        if (!filter_var($quantity, FILTER_VALIDATE_INT) || $quantity <= 0) {
            return "Quantity must be greater than zero.";
        }

        $product = $this->product->getById($productId);

        if (!$product) {
            return "Product not found.";
        }

        $unitPrice = $product['price'];
        $totalPrice = $unitPrice * $quantity;

        try {

            $this->db->beginTransaction();

            $stockUpdated = $this->product->decreaseStock(
                $productId,
                $quantity
            );

            if (!$stockUpdated) {
                $this->db->rollBack();
                return "Insufficient stock.";
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

            return "Purchase successful!";

        } catch (Exception $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return "Purchase failed. Please try again.";
        }
    }


    #[Route('GET', '/transactions')]
    public function index()
    {
        requireAdmin();

        $transactions = $this->transaction->getAll();

        require __DIR__ . '/../views/transactions/index.php';
    }

}