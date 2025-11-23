<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? '';

// Fetch products from database
require_once 'db_connect.php';
$products = [];
try {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $stmt = $conn->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error silently, products will be empty
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniMart - Pastel Edition</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Quicksand:wght@400;600;700&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="chat.css" />

    <style>
        /* --- EXACTLY the same CSS you provided. DO NOT EDIT --- */
        :root {
            --bg-cream: #FDF6F0;
            --soft-pink: #FFC3C3;
            --mint-green: #C1E1C1;
            --baby-blue: #A2D2FF;
            --text-dark: #5D576B;
            --white: #ffffff;
            --shadow: 4px 4px 0px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Quicksand', sans-serif;
        }

        body {
            background-color: var(--bg-cream);
            color: var(--text-dark);
        }

        /* --- NAVBAR --- */
        nav {
            background: var(--white);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--baby-blue);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-family: 'Fredoka', sans-serif;
            font-size: 1.8rem;
            color: #FF92A4;
            letter-spacing: 1px;
            text-decoration: none;
        }

        /* Search Box */
        .search-box {
            position: relative;
            width: 35%;
        }

        .search-bar {
            width: 100%;
            background: var(--bg-cream);
            border: 2px solid var(--soft-pink);
            border-radius: 25px;
            padding: 10px 45px 10px 20px;
            outline: none;
            color: var(--text-dark);
            transition: 0.3s;
            font-size: 0.95rem;
        }

        .search-bar:focus {
            border-color: var(--baby-blue);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-45%);
            width: 18px;
            height: 18px;
            fill: var(--text-dark);
            opacity: 0.6;
            pointer-events: none;
        }

        /* Nav Buttons Group */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Wishlist Link */
        .wishlist-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.2s;
            font-size: 0.9rem;
        }

        .wishlist-link:hover {
            color: #FF92A4;
        }

        .wishlist-icon {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* --- NEW CUTE LOGIN BUTTON --- */
        .btn-login {
            background: var(--soft-pink);
            color: #8e4d4d;
            /* Dark Red Text */
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            border: 2px solid white;
            /* Sticker look */
            box-shadow: 0 4px 0px rgba(255, 183, 178, 0.6);
            /* Cute 3D Shadow */
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(2px);
            /* Press Down Effect */
            box-shadow: 0 2px 0px rgba(255, 183, 178, 0.6);
        }

        /* Sell Button */
        .btn-sell {
            background: var(--mint-green);
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            color: #4A7c59;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 4px 0px rgba(171, 209, 171, 0.6);
            transition: 0.2s;
            white-space: nowrap;
        }

        .btn-sell:hover {
            transform: translateY(2px);
            box-shadow: 0 2px 0px rgba(171, 209, 171, 0.6);
        }

        /* --- REST OF CSS --- */
        .hero {
            text-align: center;
            padding: 3rem 1rem;
            background: linear-gradient(180deg, #fff 0%, var(--bg-cream) 100%);
        }

        .hero h1 {
            font-family: 'Fredoka', sans-serif;
            font-size: 3rem;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 2rem;
        }

        .categories {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .pill {
            background: var(--white);
            padding: 8px 20px;
            border-radius: 15px;
            font-weight: 600;
            border: 2px solid var(--baby-blue);
            color: var(--text-dark);
            cursor: pointer;
            transition: 0.2s;
        }

        .pill:hover {
            background: var(--baby-blue);
            color: white;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 20px 80px 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
        }

        .card {
            background: var(--white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid #eee;
            transition: 0.3s;
            animation: fadeIn 0.5s ease-in;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 8px 8px 0px var(--soft-pink);
        }

        .img-frame {
            padding: 15px;
            background: #FFF0F3;
        }

        .img-box {
            height: 180px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
        }

        .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 15px 20px 20px 20px;
        }

        .price-badge {
            background: var(--baby-blue);
            color: white;
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 8px;
        }

        .title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .seller {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 15px;
        }

        .action-btn {
            width: 100%;
            padding: 12px;
            background: var(--soft-pink);
            color: #8e4d4d;
            border: none;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .action-btn:hover {
            background: #ffb1b1;
            color: white;
        }



        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-counter {
            position: relative;
            display: inline-block
        }

        .cart-counter .count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #FF6363;
            color: white;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 12px;
            font-weight: 700
        }

        /* footer */
        footer {
            margin-top: auto;
            background: #fff;
            border-top: 1px solid #eee;
            padding: 30px 5%;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap
        }

        .footer-col {
            flex: 1;
            min-width: 200px
        }

        .footer-brand {
            font-family: 'Fredoka';
            font-size: 1.35rem;
            color: #FF7A9A;
            margin-bottom: 6px
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .footer-links a {
            color: var(--text-dark);
            text-decoration: none
        }

        .socials {
            display: flex;
            gap: 12px;
            margin-top: 10px
        }

        .small {
            font-size: .9rem;
            color: #777;
            margin-top: 10px
        }
    </style>
</head>

<body>

    <nav>
        <a href="#" class="logo">UniMart 🌸</a>

        <div class="search-box">
            <input id="searchInput" type="text" class="search-bar" placeholder="Search cute stationery...">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42zM11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7z" />
            </svg>
        </div>

        <div class="nav-actions">
            <a href="#" class="wishlist-link">
                <svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                Wishlist
            </a>

            <!-- User Info and Logout -->
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="color: var(--text-dark); font-weight:600;">👤 <?php echo htmlspecialchars($username); ?></span>
                <a href="logout.php" class="btn-login" style="background: #ffc3c3;">Logout</a>
            </div>

            <a href="admins.php" class="btn-sell" style="background: #667eea;">💬 My Messages</a>

            <a href="sell.php" class="btn-sell">✨ Sell Item</a>

            <!-- CART ICON (persistent) -->
            <a id="cartBtn" href="cart.html" class="cart-counter" title="View cart" style="margin-left:6px; text-decoration:none; color:inherit;">
                🛒 <span class="count" id="cartCount" style="display:none">0</span>
            </a>
        </div>
    </nav>

    <section class="hero">
        <h1>Your Campus Store</h1>
        <p>Buy books, notes & cute stuff from seniors!</p>

        <div class="categories">
            <span class="pill">📚 Books</span>
            <span class="pill">🎀 Accessories</span>
            <span class="pill">🖊️ Stationery</span>
            <span class="pill">🎒 Bags</span>
        </div>
    </section>

    <div class="container">
        <div class="grid" id="productGrid">

            <?php foreach ($products as $index => $product): ?>
            <div class="card" data-id="<?php echo $product['id']; ?>">
                <div class="img-frame">
                    <div class="img-box">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/300'); ?>" alt="Product">
                    </div>
                </div>
                <div class="card-body">
                    <span class="price-badge">₹<?php echo htmlspecialchars($product['price']); ?></span>
                    <div class="title"><?php echo htmlspecialchars($product['title']); ?></div>
                    <div class="seller">From: <?php echo htmlspecialchars($product['seller_name'] ?: 'Seller'); ?></div>
                    <button class="action-btn" onclick="event.stopPropagation(); window.openChatWidget('seller-<?php echo $product['user_id']; ?>', '<?php echo htmlspecialchars($product['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($username, ENT_QUOTES); ?>')">💬 Chat</button>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- FOOTER (matching your theme; uses the existing footer CSS) -->
    <footer>
        <div class="footer-col">
            <div class="footer-brand">UniMart 🌸</div>
            <div class="small">Campus marketplace for books, stationery & more.<br/>Made with ❤️ by students.</div>
            <div class="socials" aria-hidden="true">
                <a href="#" title="Instagram">IG</a>
                <a href="#" title="Twitter">TW</a>
                <a href="#" title="Facebook">FB</a>
            </div>
        </div>

        <div class="footer-col">
            <div style="font-weight:700;margin-bottom:8px">Helpful</div>
            <div class="footer-links">
                <a href="#">Contact</a>
                <a href="#">How it works</a>
                <a href="#">Shipping & Returns</a>
            </div>
        </div>

        <div class="footer-col">
            <div style="font-weight:700;margin-bottom:8px">Community</div>
            <div class="footer-links">
                <a href="#">Campus Ambassadors</a>
                <a href="#">Sell on UniMart</a>
                <a href="#">Safety Tips</a>
            </div>
        </div>

        <div class="footer-col">
            <div style="font-weight:700;margin-bottom:8px">Download</div>
            <div class="small">Get the app (coming soon)</div>
        </div>
    </footer>

    <!-- Minimal, necessary JavaScript only -->
    <script>
        /* Minimal product model derived from DOM - keeps HTML as single source of truth */
        const productCards = Array.from(document.querySelectorAll('.grid .card'));
        const products = productCards.map(card => {
            const i = card.dataset.id || '';
            const title = (card.querySelector('.title') || {}).textContent || '';
            const seller = (card.querySelector('.seller') || {}).textContent || '';
            const priceText = (card.querySelector('.price-badge') || {}).textContent || '';
            const imgEl = card.querySelector('.img-box img');
            const img = imgEl ? imgEl.src : (card.querySelector('.img-box img') || {}).getAttribute('src') || '';
            // normalize price (convert "₹250" -> number 250), treat non-numeric (WANTED) as null
            const numeric = priceText.replace(/[^0-9]/g, '');
            const price = numeric ? Number(numeric) : null;
            return { id: Number(i), title: title.trim(), seller: seller.trim(), price, img };
        });

        // attach click -> product view
        productCards.forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', e => {
                // avoid when clicking the chat/CTA buttons inside the card
                if (e.target.closest('.action-btn')) return;
                const id = card.dataset.id;
                window.location.href = 'product.php?id=' + encodeURIComponent(id);
            });
        });

        // search: filter by title/seller
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', e => {
            const q = e.target.value.trim().toLowerCase();
            productCards.forEach(card => {
                const title = (card.querySelector('.title') || {}).textContent || '';
                const seller = (card.querySelector('.seller') || {}).textContent || '';
                const show = (!q) || title.toLowerCase().includes(q) || seller.toLowerCase().includes(q);
                card.style.display = show ? '' : 'none';
            });
        });

        // CART: localStorage key + cart icon update
        const CART_KEY = 'unimart_cart_v1';
        function readCart() {
            try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
            catch { return []; }
        }
        function writeCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); updateCartUI(); }
        function updateCartUI() {
            const cart = readCart();
            const totalQty = cart.reduce((s, i) => s + (i.qty || 0), 0);
            const countEl = document.getElementById('cartCount');
            if (totalQty > 0) { countEl.style.display = 'inline-block'; countEl.textContent = totalQty; }
            else { countEl.style.display = 'none'; }
        }
        // Expose addToCart for product page to use via window
        window.addToCart = function (item) {
            const cart = readCart();
            const idx = cart.findIndex(i => i.id === item.id);
            if (idx > -1) cart[idx].qty += item.qty;
            else cart.push(item);
            writeCart(cart);
        };

        // initialize cart UI
        updateCartUI();
    </script>
    <!-- chat widget (listing page) -->
<div id="chat-root" aria-live="polite"></div>
<script>
  // Optional: set a default shop id for listing page interactions
  window.SHOP_ID = 'shop-123';
</script>
<script type="module" src="chat.js"></script>
</body>

</html>
