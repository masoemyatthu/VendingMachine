
# Vending Machine System

A PHP and MySQL based Vending Machine application.

## Features

- User Registration and Login
- Password Hashing
- Admin and User Roles
- Product CRUD Management
- Inventory / Stock Management
- Purchase Transactions
- Database Transaction with Rollback
- Input Validation
- Pagination
- REST API
- JWT Authentication
- PHPUnit Testing

## Technologies

- PHP 8.3+
- MySQL
- PDO
- Composer
- PHPUnit
- Firebase PHP-JWT

## Installation

1. Clone or copy this project into your web server directory.
2. Create a MySQL database named `vending_machine`.
3. Configure database credentials in `app/config/database.php`.
4. Install dependencies:

```bash
composer install
```

5. Start Apache and MySQL.
6. Open:

```text
http://localhost/vendor_machine/public/
```

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | /api/login | Login and get JWT token |
| GET | /api/products | Get products (JWT required) |

## Running Tests

```bash
./vendor/bin/phpunit tests/
```

## Security

- Passwords are hashed using password_hash().
- Passwords are verified using password_verify().
- Prepared statements are used for database queries.
- JWT protects API endpoints.
- Admin-only operations require authorization.
- Purchase transactions use database transactions.

## Products

- Coke: 3.99 USD
- Pepsi: 6.885 USD
- Water: 0.5 USD
