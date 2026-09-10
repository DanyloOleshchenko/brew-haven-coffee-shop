<?php
session_start();

require 'db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$stmt = $pdo->query("
    SELECT product_id, name, description, price, category
    FROM products
    ORDER BY category, name
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productsById = [];
foreach ($products as $p) {
    $productsById[$p['product_id']] = $p;
}

$successMessage = '';
$errorMessage   = '';

$preselectId = isset($_GET['add']) ? (int)$_GET['add'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['customerName']  ?? '');
    $phone = trim($_POST['customerPhone'] ?? '');
    $email = trim($_POST['customerEmail'] ?? '');
    $qty   = $_POST['qty'] ?? [];
    $submittedCsrfToken = $_POST['csrf_token'] ?? '';
    $csrfIsValid = is_string($submittedCsrfToken)
        && hash_equals($_SESSION['csrf_token'], $submittedCsrfToken);

    $emailIsValid = $email === '' || filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$csrfIsValid) {
        $errorMessage = 'Your session expired. Please refresh the page and try again.';
    } elseif ($name === '' || $phone === '') {
        $errorMessage = 'Please fill in name and phone.';
    } elseif (strlen($name) > 100 || strlen($phone) > 30) {
        $errorMessage = 'Name or phone number is too long.';
    } elseif (!$emailIsValid || strlen($email) > 100) {
        $errorMessage = 'Please enter a valid email address.';
    } else {

        $items       = [];
        $totalAmount = 0;

        foreach ($qty as $productId => $q) {
            $productId = (int)$productId;
            $q = (int)$q;
            if ($q > 0 && $q <= 100 && isset($productsById[$productId])) {
                $price = (float)$productsById[$productId]['price'];
                $lineTotal = $price * $q;
                $items[] = [
                    'product_id' => $productId,
                    'quantity'   => $q,
                    'price_each' => $price,
                    'line_total' => $lineTotal,
                ];
                $totalAmount += $lineTotal;
            }
        }

        if (empty($items)) {
            $errorMessage = 'Please add at least one product to your order.';
        } else {
            try {
                $pdo->beginTransaction();


                $emailNormalized = ($email !== '') ? $email : null;

                if ($emailNormalized !== null) {
                    $stmt = $pdo->prepare("
                        SELECT customer_id, orders_count
                        FROM customers
                        WHERE name = :name
                          AND phone = :phone
                          AND email = :email
                        LIMIT 1
                    ");
                    $stmt->execute([
                        ':name'  => $name,
                        ':phone' => $phone,
                        ':email' => $emailNormalized,
                    ]);
                } else {

                    $stmt = $pdo->prepare("
                        SELECT customer_id, orders_count
                        FROM customers
                        WHERE name = :name
                          AND phone = :phone
                          AND email IS NULL
                        LIMIT 1
                    ");
                    $stmt->execute([
                        ':name'  => $name,
                        ':phone' => $phone,
                    ]);
                }

                $existingCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingCustomer) {

                    $customerId = (int)$existingCustomer['customer_id'];

                    $stmt = $pdo->prepare("
                        UPDATE customers
                        SET orders_count = orders_count + 1
                        WHERE customer_id = :id
                    ");
                    $stmt->execute([':id' => $customerId]);
                } else {

                    $stmt = $pdo->prepare("
                        INSERT INTO customers (name, phone, email, orders_count)
                        VALUES (:name, :phone, :email, :orders_count)
                    ");
                    $stmt->execute([
                        ':name'         => $name,
                        ':phone'        => $phone,
                        ':email'        => $emailNormalized,
                        ':orders_count' => 1,
                    ]);
                    $customerId = (int)$pdo->lastInsertId();
                }


                $stmt = $pdo->prepare("
                    INSERT INTO orders (customer_id, total_amount)
                    VALUES (:customer_id, :total_amount)
                ");
                $stmt->execute([
                    ':customer_id'  => $customerId,
                    ':total_amount' => $totalAmount,
                ]);
                $orderId = (int)$pdo->lastInsertId();


                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price_each, line_total)
                    VALUES (:order_id, :product_id, :quantity, :price_each, :line_total)
                ");
                foreach ($items as $it) {
                    $stmt->execute([
                        ':order_id'   => $orderId,
                        ':product_id' => $it['product_id'],
                        ':quantity'   => $it['quantity'],
                        ':price_each' => $it['price_each'],
                        ':line_total' => $it['line_total'],
                    ]);
                }

                $pdo->commit();
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $successMessage = 'Your order has been placed! Thank you 🙌';
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log('Order creation failed: ' . $e->getMessage());
                $errorMessage = 'The order could not be saved. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Brew Haven — Order</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="order.css">
</head>
<body>

  <header class="bar">
  <div class="container flex">
    <a class="logo" href="home.php">
      <span class="mug">☕️</span>
      <span>Brew Haven</span>
    </a>

    <ul class="links">
      <li><a href="home.php">Home</a></li>
      <li><a href="menu.php">Menu</a></li>

    </ul>

    <a class="act" href="order.php">Reset Form</a>
  </div>
</header>

  <main class="main container">
    <h1 class="page-title">Place Your Order</h1>


    <?php if ($successMessage): ?>
      <p style="color: green; font-weight: 600; text-align: center;"><?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
      <p style="color: red; font-weight: 600; text-align: center;"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <form class="order-form" id="orderForm" method="post" action="order.php">
      <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
      >
      <div class="form-section">
        <h2 class="section-title">Customer Information</h2>
        <div class="form-group">
          <label for="customerName">Full Name *</label>
          <input type="text" id="customerName" name="customerName" required>
        </div>
        <div class="form-group">
          <label for="customerPhone">Phone Number *</label>
          <input type="tel" id="customerPhone" name="customerPhone" required>
        </div>
        <div class="form-group">
          <label for="customerEmail">Email Address</label>
          <input type="email" id="customerEmail" name="customerEmail">
        </div>
      </div>

      <div class="form-section">
        <h2 class="section-title">Select Products</h2>
        <div class="product-grid" id="productGrid">
          <?php foreach ($products as $p):
              $id = (int)$p['product_id'];
              $defaultQty = ($preselectId === $id) ? 1 : 0;
          ?>
            <div class="product-card">
              <div class="product-info">
                <p class="product-name"><?= htmlspecialchars($p['name']) ?></p>
                <p class="product-description"><?= htmlspecialchars($p['description'] ?? '') ?></p>
                <p class="product-price">$<?= number_format($p['price'], 2) ?></p>
                <div class="quantity-selector">
                  <button type="button" class="quantity-btn minus" data-product-id="<?= $id ?>">-</button>
                  <input
                    type="number"
                    class="quantity-input"
                    id="qty-<?= $id ?>"
                    name="qty[<?= $id ?>]"
                    min="0"
                    max="100"
                    value="<?= $defaultQty ?>"
                    data-name="<?= htmlspecialchars($p['name']) ?>"
                    data-price="<?= htmlspecialchars($p['price']) ?>"
                  >
                  <button type="button" class="quantity-btn plus" data-product-id="<?= $id ?>">+</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-section">
        <h2 class="section-title">Order Summary</h2>
        <table class="summary-table">
        <thead>
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody id="summaryBody">
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="3">Total Amount</td>
            <td id="totalAmount">$0.00</td>
          </tr>
        </tfoot>
      </table>
      <button type="submit" form="orderForm" class="submit-btn">Place Order</button>
    </div>
  </main>

  <footer class="foot">
    <div class="container foot-grid">
      <div>
        <div class="logo foot-logo">
          <span class="mug">☕</span>
          <span>Brew Haven</span>
        </div>
        <p class="foot-text">
          Freshly brewed coffee and cozy atmosphere. Visit us or order online for your daily dose of caffeine.
        </p>
      </div>
      <div>
        <h3 class="foot-title">Contact</h3>
        <p class="foot-text">123 Coffee Street, Bean City</p>
        <p class="foot-text">Phone: +1 (555) 123-4567</p>
        <p class="foot-text">Email: hello@brewhaven.com</p>
      </div>
      <div>
        <h3 class="foot-title">Opening Hours</h3>
        <div class="foot-text">
          <div>Mon–Fri: 7AM - 8PM</div>
          <div>Sat–Sun: 8AM - 9PM</div>
        </div>
      </div>
    </div>

    <div class="foot-bottom">
      <p>© 2025 Brew Haven. All rights reserved.</p>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
