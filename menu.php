<?php
require 'db.php';

$stmt = $pdo->query("
    SELECT product_id, name, description, full_description, price, category, image
    FROM products
    ORDER BY category, name
");
$allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productsByCategory = [
    'coffee'    => [],
    'specialty' => [],
    'food'      => [],
];

foreach ($allProducts as $p) {
    if (isset($productsByCategory[$p['category']])) {
        $productsByCategory[$p['category']][] = $p;
    }
}

$featuredCards = [];
$usedIds = [];
$bestsellerStmt = $pdo->query("
    SELECT
        p.product_id,
        p.name,
        p.description,
        p.full_description,
        p.price,
        p.category,
        p.image,
        COUNT(oi.product_id) AS total_orders
    FROM products p
    JOIN order_items oi ON oi.product_id = p.product_id
    GROUP BY p.product_id
    ORDER BY total_orders DESC
    LIMIT 1
");
$bestseller = $bestsellerStmt->fetch(PDO::FETCH_ASSOC);

if ($bestseller) {
    $bestseller['badge'] = 'Bestseller';
    $featuredCards[] = $bestseller;
    $usedIds[] = (int)$bestseller['product_id'];
}

$newStmt = $pdo->query("
    SELECT product_id, name, description, full_description, price, category, image
    FROM products
    ORDER BY product_id DESC
    LIMIT 1
");
$newProduct = $newStmt->fetch(PDO::FETCH_ASSOC);

if ($newProduct) {
    $isDifferent = true;
    foreach ($usedIds as $id) {
        if ((int)$newProduct['product_id'] === $id) {
            $isDifferent = false;
            break;
        }
    }

    if ($isDifferent) {
        $newProduct['badge'] = 'New';
        $featuredCards[] = $newProduct;
        $usedIds[] = (int)$newProduct['product_id'];
    }
}

$randomSql = "
    SELECT product_id, name, description, full_description, price, category, image
    FROM products
";

if (!empty($usedIds)) {

    $ids = implode(',', array_map('intval', $usedIds));
    $randomSql .= " WHERE product_id NOT IN ($ids)";
}

$randomSql .= " ORDER BY RAND() LIMIT 1";

$cozyStmt = $pdo->query($randomSql);
$cozy = $cozyStmt->fetch(PDO::FETCH_ASSOC);

if ($cozy) {
    $cozy['badge'] = 'Cozy choice';
    $featuredCards[] = $cozy;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Brew Haven — Menu</title>
  <link rel="stylesheet" href="menu.css">
</head>
<body>

  <header class="topbar">
    <div class="container row">
      <a class="brand" href="#">
        <span class="cup">☕</span>
        <span>Brew Haven</span>
      </a>

      <ul class="nav">
        <li><a href="home.php">Home</a></li>
        <li><a class="active" href="menu.php">Menu</a></li>
      </ul>

      <a class="act" href="order.php">Place Order</a>
    </div>
  </header>

  <section class="hero">
    <div class="wrap center">
      <h1>Our Menu</h1>
      <p>
        Carefully crafted beverages and freshly prepared food
        made with the finest<br>
        ingredients
      </p>
    </div>
  </section>

  <section class="tabs">
    <div class="wrap">
      <div class="seg">
        <button class="seg-item" type="button" data-category="coffee">
          Coffee
        </button>
        <button class="seg-item" type="button" data-category="specialty">
          Specialty
        </button>
        <button class="seg-item" type="button" data-category="food">
          Food
        </button>
      </div>

      <p class="menu-hint">
        To see the full menu, please select a category above.
      </p>
    </div>
  </section>

  <section class="featured-section">
    <div class="wrap">
      <div class="featured-header center">
        <span class="featured-eyebrow">Featured this week</span>
        <h2 class="featured-title">Barista’s picks for you</h2>
        <p class="featured-sub">
          Discover our most loved drinks before you dive into the full menu.
        </p>
      </div>

      <?php if (!empty($featuredCards)): ?>
        <div class="featured-grid">
          <?php foreach ($featuredCards as $p): ?>
            <article class="featured-card">
              <div class="featured-pill">
                <?= htmlspecialchars($p['badge']) ?>
              </div>

              <div class="featured-main">
                <div>
                  <h3 class="featured-name">
                    <?= htmlspecialchars($p['name']) ?>
                  </h3>
                  <p class="featured-tagline">
                    <?= htmlspecialchars($p['description'] ?? '') ?>
                  </p>
                </div>

                <span class="featured-price">
                  $<?= number_format($p['price'], 2) ?>
                </span>
              </div>

              <div class="featured-meta">
                <span class="featured-meta-pill">
                  <?= htmlspecialchars(ucfirst($p['category'])) ?>
                </span>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="center">No featured items yet — add some products and orders first.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="section menu-block" data-category="coffee">
    <div class="wrap">
      <h2>Classic Coffee</h2>
      <p class="sub">
        Our signature coffee drinks,
        available hot or iced
      </p>

      <div class="cards">
        <?php if (!empty($productsByCategory['coffee'])): ?>
          <?php foreach ($productsByCategory['coffee'] as $p): ?>
            <article class="card">
              <div class="card-top">
                <h3 class="item"><?= htmlspecialchars($p['name']) ?></h3>
                <span class="price">$<?= number_format($p['price'], 2) ?></span>
              </div>

              <div class="card-img">
                <?php
                  $imgPath = !empty($p['image']) ? $p['image'] : 'img/placeholder.png';
                ?>
                <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
              </div>

              <p class="desc">
                <?= htmlspecialchars($p['description'] ?? '') ?>
              </p>

              <div class="tags">
                <span class="tag">Coffee</span>
              </div>

              <div class="card-more">
                <h4><?= htmlspecialchars($p['name']) ?></h4>
                <p><?= nl2br(htmlspecialchars($p['full_description'] ?? '')) ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No coffee items yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section menu-block" data-category="specialty">
    <div class="wrap">
      <h2>Specialty Drinks</h2>
      <p class="sub">
        Signature creations, seasonal flavors, and drinks with a twist
      </p>

      <div class="cards">
        <?php if (!empty($productsByCategory['specialty'])): ?>
          <?php foreach ($productsByCategory['specialty'] as $p): ?>
            <article class="card">
              <div class="card-top">
                <h3 class="item"><?= htmlspecialchars($p['name']) ?></h3>
                <span class="price">$<?= number_format($p['price'], 2) ?></span>
              </div>

              <div class="card-img">
                <?php
                  $imgPath = !empty($p['image']) ? $p['image'] : 'img/placeholder.png';
                ?>
                <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
              </div>

              <p class="desc">
                <?= htmlspecialchars($p['description'] ?? '') ?>
              </p>

              <div class="tags">
                <span class="tag">Specialty</span>
              </div>

              <div class="card-more">
                <h4><?= htmlspecialchars($p['name']) ?></h4>
                <p><?= nl2br(htmlspecialchars($p['full_description'] ?? '')) ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No specialty items yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section menu-block" data-category="food">
    <div class="wrap">
      <h2>Fresh Food</h2>
      <p class="sub">
        Baked in-house daily and prepared to pair perfectly with your coffee
      </p>

      <div class="cards">
        <?php if (!empty($productsByCategory['food'])): ?>
          <?php foreach ($productsByCategory['food'] as $p): ?>
            <article class="card">
              <div class="card-top">
                <h3 class="item"><?= htmlspecialchars($p['name']) ?></h3>
                <span class="price">$<?= number_format($p['price'], 2) ?></span>
              </div>

              <div class="card-img">
                <?php
                  $imgPath = !empty($p['image']) ? $p['image'] : 'img/placeholder.png';
                ?>
                <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
              </div>

              <p class="desc">
                <?= htmlspecialchars($p['description'] ?? '') ?>
              </p>

              <div class="tags">
                <span class="tag">Food</span>
              </div>

              <div class="card-more">
                <h4><?= htmlspecialchars($p['name']) ?></h4>
                <p><?= nl2br(htmlspecialchars($p['full_description'] ?? '')) ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No food items yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <footer class="foot">
    <div class="container foot-grid">
      <div>
        <div class="logo foot-logo">
          <span class="mug">☕</span>
          <span>Brew Haven</span>
        </div>
        <p class="foot-note">
          Crafting the perfect cup since 2010.
          Your neighborhood coffee sanctuary.
        </p>
      </div>

      <div>
        <div class="foot-title">Quick Links</div>
        <ul class="foot-list">
          <li><a href="home.php">Home</a></li>
          <li><a href="order.php">Order</a></li>
        </ul>
      </div>

      <div>
        <div class="foot-title">Contact</div>
        <ul class="foot-list">
          <li>123 Coffee Street, Brew City</li>
          <li>(555) 123-4567</li>
          <li>
            <a href="mailto:hello@brewhaven.com">
              hello@brewhaven.com
            </a>
          </li>
        </ul>
      </div>

      <div>
        <div class="foot-title">Connect</div>
        <div class="socials">
        </div>
        <div class="hours">
          <div>Mon–Fri: 7AM - 8PM</div>
          <div>Sat–Sun: 8AM - 9PM</div>
        </div>
      </div>
    </div>

    <div class="foot-bottom">
      <p>© 2025 Brew Haven. All rights reserved.</p>
    </div>
  </footer>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.seg-item');
    const blocks = document.querySelectorAll('.menu-block');
    const hint = document.querySelector('.menu-hint');
    const featured = document.querySelector('.featured-section');

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const category = btn.dataset.category;

        buttons.forEach(b => b.classList.toggle('is-active', b === btn));
        blocks.forEach(block => {
          block.style.display = block.dataset.category === category ? 'block' : 'none';
        });

        if (hint) hint.style.display = 'none';
        if (featured) featured.style.display = 'none';
      });
    });
  });
  </script>

</body>
</html>
