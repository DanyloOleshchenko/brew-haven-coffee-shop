<?php

require 'db.php';

$stmt = $pdo->prepare("
    SELECT product_id, name, description, price, image
    FROM products
    WHERE category = 'specialty'
    ORDER BY RAND()
    LIMIT 3
");
$stmt->execute();
$specials = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Brew Haven — Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="home.css">
</head>
<body>

  <header class="bar">
    <div class="container flex">
      <a class="logo" href="home.php">
        <span class="mug">☕</span>
        <span>Brew Haven</span>
      </a>

      <ul class="links">
        <li><a class="active" href="home.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
      </ul>

      <a class="act" href="order.php">Place Order</a>
    </div>
  </header>

  <section class="hero-box">
    <div class="hero-img">
      <img src="img/main.jpg" alt="Cozy coffee shop interior" class="hero-photo">
    </div>

    <div class="container hero-content">
      <h1>Welcome to Brew Haven</h1>
      <p>Where every cup tells a story</p>

      <div class="hero-buttons">
        <a class="btn-main" href="menu.php">View Menu</a>
      </div>
    </div>
  </section>

  <section class="why">
    <div class="container">
      <h2 class="title">Why Choose Brew Haven</h2>
      <p class="subtitle">
        We're more than just a coffee shop – we're a community dedicated to excellence
      </p>

      <div class="why-grid">
        <div class="why-item">
          <div class="why-icon">☕</div>
          <h3>Premium Beans</h3>
          <p>Ethically sourced from the finest coffee regions worldwide</p>
        </div>

        <div class="why-item">
          <div class="why-icon">🎖️</div>
          <h3>Expert Baristas</h3>
          <p>Trained professionals crafting each cup to perfection</p>
        </div>

        <div class="why-item">
          <div class="why-icon">👥</div>
          <h3>Community Hub</h3>
          <p>A welcoming space for friends, work, and relaxation</p>
        </div>

        <div class="why-item">
          <div class="why-icon">❤️</div>
          <h3>Made with Love</h3>
          <p>Every drink prepared with care and passion</p>
        </div>
      </div>
    </div>
  </section>

  <section class="featured">
    <div class="container">
      <h2 class="title">Featured Favorites</h2>
      <p class="subtitle">
        Discover our specialty items curated just for today
      </p>

      <?php if (!empty($specials)): ?>
        <div class="cards">
          <?php foreach ($specials as $item): ?>
            <article class="card">
              <div class="ph">
                <?php
                  $imgPath = !empty($item['image']) ? $item['image'] : 'img/placeholder.png';
                ?>
                <img src="<?= htmlspecialchars($imgPath) ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>"
                     class="card-photo">
              </div>

              <div class="card-body">
                <div class="card-top">
                  <h3 class="item"><?= htmlspecialchars($item['name']) ?></h3>
                  <span class="price">$<?= number_format($item['price'], 2) ?></span>
                </div>
                <p class="desc">
                  <?= htmlspecialchars($item['description'] ?? '') ?>
                </p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="subtitle">
          No specialty items available right now. Please check back later. ☕
        </p>
      <?php endif; ?>

      <div class="center">
        <a class="btn-small" href="menu.php">
          View Full Menu →
        </a>
      </div>
    </div>
  </section>

  <section class="reviews">
    <div class="container">
      <h2 class="title light">What Our Customers Say</h2>
      <p class="subtitle light">
        Don't just take our word for it - hear from our community
      </p>

      <div class="reviews-grid">
        <div class="review">
          <div class="stars">★★★★★</div>
          <p>The best coffee in town! The atmosphere is perfect for working or catching up with friends.</p>
          <div class="who">- Sarah Mitchell</div>
        </div>

        <div class="review">
          <div class="stars">★★★★★</div>
          <p>Amazing latte art and the baristas really know their craft. My daily stop!</p>
          <div class="who">- James Chen</div>
        </div>

        <div class="review">
          <div class="stars">★★★★★</div>
          <p>Love the cozy vibe and the quality of their beans. You can really taste the difference.</p>
          <div class="who">- Emily Rodriguez</div>
        </div>
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
          <li><a href="menu.php">Menu</a></li>
          <li><a href="order.php">Place Order</a></li>
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
          <a class="ico" href="#" aria-label="Instagram">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm5 5.5a5.5 5.5 0 1 0 0 11a5.5 5.5 0 0 0 0-11zm6-1a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"/>
            </svg>
          </a>

          <a class="ico" href="#" aria-label="Facebook">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path d="M13 22v-8h3l.5-3H13V9.25C13 8 13.4 7.5 15 7.5h1.5V5c-1 0-2.1-.1-2.8-.1C10.9 4.9 9 6.2 9 8.8V11H6v3h3v8h4z"/>
            </svg>
          </a>

          <a class="ico" href="#" aria-label="Twitter">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path d="M22 5.8c-.7.3-1.5.5-2.3.6a3.8 3.8 0 0 0 1.7-2.1a7.6 7.6 0 0 1-2.4.9a3.8 3.8 0 0 0-6.5 3.5a10.8 10.8 0 0 1-7.8-4a3.8 3.8 0 0 0 1.2 5a3.7 3.7 0 0 1-1.7-.5a3.8 3.8 0 0 0 3 3.7a3.9 3.9 0 0 1-1 .1c-.2 0-.5 0-.7-.1a3.8 3.8 0 0 0 3.6 2.7A7.7 7.7 0 0 1 2 18.6a10.9 10.9 0 0 0 5.9 1.7c7.1 0 11-5.9 11-11v-.5A7.8 7.8 0 0 0 22 5.8z"/>
            </svg>
          </a>
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

</body>
</html>
