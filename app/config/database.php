<?php

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(
            dirname(__DIR__, 2)
        );
        $dotenv->safeLoad();

        $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
        $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 3306;
        $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
        $username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
        $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            // Enable TLS only for remote database
            if (!in_array($host, ['localhost', '127.0.0.1'])) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = true;
            }

            $this->connection = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                $options
            );

        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Database Connection Failed.");
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

$pdo = Database::getInstance()->getConnection();
