<?php

class Transaction
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function create($userId, $productId, $quantity, $unitPrice, $totalPrice)
    {
        $sql = "INSERT INTO transactions
                (user_id, product_id, quantity, unit_price, total_price)
                VALUES
                (:user_id, :product_id, :quantity, :unit_price, :total_price)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
            ':unit_price' => $unitPrice,
            ':total_price' => $totalPrice
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT transactions.*,
            users.name AS user_name,
            products.name AS product_name
        FROM transactions
        INNER JOIN users
        ON transactions.user_id = users.id
        INNER JOIN products
        ON transactions.product_id = products.id
        ORDER BY transactions.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT transactions.*,
        users.name AS user_name,
        products.name AS product_name
        FROM transactions
        INNER JOIN users ON transactions.user_id = users.id
        INNER JOIN products ON transactions.product_id = products.id
        WHERE transactions.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM transactions WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

}