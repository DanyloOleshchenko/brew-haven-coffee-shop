# Brew Haven Coffee Shop

A database-driven coffee shop website built as a university group project. My contribution focused on backend development, relational database design, and connecting the PHP application to MySQL.

**Originally developed:** 20 November 2025.

## Features

- Dynamic home page with randomly selected specialty products
- Menu grouped by coffee, specialty drinks, and food
- Featured products based on order history and catalogue data
- Interactive order form with a live JavaScript summary
- Server-side price calculation to avoid trusting browser totals
- Customer, order, order-item, and product relationships in MySQL
- Transactional order creation using PDO prepared statements
- CSRF protection and server-side validation for submitted orders
- Responsive HTML and CSS interface

## Technology

- PHP and PDO
- MySQL or MariaDB
- SQL
- HTML and CSS
- JavaScript

## Run locally

1. Install PHP 8 and MySQL or MariaDB.
2. Create a database named `brew_haven`.
3. Import `database.sql` into the database.
4. Set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` if your local credentials differ from the development defaults in `db.php`.
5. Start the PHP development server from the repository root:

```bash
php -S localhost:8000
```

6. Open `http://localhost:8000/home.php`.

## Data and security notes

The public sample database contains product catalogue data only. Customer details and historical orders from the coursework copy were removed before publication. Database credentials are read from environment variables, prices are recalculated on the server, and PDO prepared statements are used for user-provided values.

This is an educational demonstration and is not intended to process real customer orders. Image assets were used for coursework; confirm their redistribution rights before reusing them elsewhere.
