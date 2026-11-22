<?php 

class Product
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Get all products
    public function getProducts()
    {
        $sql = "SELECT * FROM products ORDER BY id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $price, $stock)
    {
        $sql = "INSERT INTO products (name, price, stock)
        VALUES (:name, :price, :stock)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':stock' => $stock
        ]);
    }

    public function update($id, $name, $price, $stock)
    {
        $sql = "UPDATE products 
        SET name = :name,
            price = :price,
            stock = :stock
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':price' => $price,
            ':stock' => $stock
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function decreaseStock($id, $quantity)
    {
        $sql = "UPDATE products
        SET stock = stock - :quantity
        WHERE id = :id
        AND stock >= :quantity";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':quantity' => $quantity
        ]);

        return $stmt->rowCount() > 0;
    }

    public function getPaginated($limit, $offset, $sortBy, $sortOrder)
    {
        $allowedColumns = ['id', 'name', 'price', 'stock'];

        if (!in_array($sortBy, $allowedColumns, true)) {
            $sortBy = 'id';
        }

        $sortOrder = strtoupper($sortOrder);

        if (!in_array($sortOrder, ['ASC', 'DESC'], true)) {
            $sortOrder = 'DESC';
        }

        $sql = "SELECT * FROM products
                ORDER BY $sortBy $sortOrder
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->db->query($sql);

        return $stmt->fetchColumn();
    }

}

?>