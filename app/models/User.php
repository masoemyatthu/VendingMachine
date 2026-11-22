<?php 

    require_once __DIR__ . '/../config/database.php';

    class User
    {
        private $db;
        
        public function __construct($pdo)
        {
            $this->db = $pdo;
        }

        public function register($name, $email, $password)
        {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name,email,password,role)
                    VALUES (:name, :email, :password, :role)";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => 'user',
            ]);
        }

        
        public function login($email, $password)
        {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':email' => $email
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return "email_not_found";
            }

            if (!password_verify($password, $user['password'])) {
                return "wrong_password";
            }

            return $user;
        }

        public function getAll()
        {

            $sql = "SELECT id, name, email, role FROM users ORDER BY id DESC";

            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        }

        public function getById($id)
        {

            $stmt = $this->db->prepare(

                "SELECT id, name, email, role FROM users WHERE id = :id"

            );

            $stmt->execute([':id' => $id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        }

        public function update($id, $name, $email, $role)
        {

            $stmt = $this->db->prepare(

                "UPDATE users

                SET name = :name, email = :email, role = :role

                WHERE id = :id"

            );

            return $stmt->execute([

                ':id' => $id,

                ':name' => $name,

                ':email' => $email,

                ':role' => $role

            ]);

        }

        public function delete($id)
        {

            $stmt = $this->db->prepare(

                "DELETE FROM users WHERE id = :id"

            );

            return $stmt->execute([':id' => $id]);

        }
        
    }

?>