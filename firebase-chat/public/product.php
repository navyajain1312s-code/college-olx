<?php
session_start();
require_once 'db_connect.php';

// Get product ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$product = null;
if ($id > 0) {
    try {
        $sql = "SELECT * FROM products WHERE id = " . $id;
        $stmt = $conn->query($sql);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error
    }
}

// If product not found, redirect or show error
if (!$product) {
    // For now, just redirect to home
    header('Location: 1.php');
    exit();
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?php echo htmlspecialchars($product['title']); ?> — UniMart</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Quicksand:wght@400;600;700&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="chat.css" />

    <style>
        :root {
            --bg-cream: #FDF6F0;
            --text-dark: #5D576B;
            --accent: #FF92A4
        }

        * {
            box-sizing: border-box;
            font-family: 'Quicksand', sans-serif
        }

        body {
            background: var(--bg-cream);
            color: var(--text-dark);
            margin: 0;
            padding: 26px
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px
        }

        .logo {
            font-family: 'Fredoka';
            color: var(--accent);
            text-decoration: none;
            font-size: 20px
        }

        .wrap {
            max-width: 980px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .05)
        }

        .img-box {
            width: 100%;
            height: 420px;
            border-radius: 8px;
            overflow: hidden;
            background: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .img-box img {
            width: 100%;
            height: 100%;
            object-fit: contain
        }

        .title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 6px
        }

        .seller {
            color: #777;
            margin-bottom: 10px
        }

        .price {
            font-weight: 800;
            font-size: 1.4rem;
            color: #2b7a7a;
            margin-bottom: 8px
        }

        .row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin: 12px 0
        }

        .btn {
            background: var(--accent);
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 700
        }

        .qty-controls {
            display: flex;
            gap: 8px;
            align-items: center
        }

        .qty-controls button {
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid #eee;
            background: #fafafa;
            cursor: pointer
        }

        .cart-counter {
            margin-left: 8px
        }

        .small {
            color: #666;
            font-size: 13px
        }

        .desc {
            margin-top: 20px;
            line-height: 1.6;
            color: #555;
        }

        @media (max-width:900px) {
            .wrap {
                grid-template-columns: 1fr
            }
        }
    </style>
</head>

<body>
    <div class="top">
        <a class="logo" href="1.php">← UniMart</a>
        <a id="cartLink" href="cart.html" style="text-decoration:none;color:inherit">🛒 <span id="cartCountSmall"
                style="font-weight:700;display:none"></span></a>
    </div>

    <div class="wrap">
        <div class="card" id="imgCard">
            <div class="img-box">
                <img id="productImage" src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/400'); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            </div>
        </div>

        <div class="card" id="infoCard">
            <div class="title" id="title"><?php echo htmlspecialchars($product['title']); ?></div>
            <div class="seller" id="seller">From: <?php echo htmlspecialchars($product['seller_name'] ?: 'Seller'); ?></div>
            <div class="price" id="price">₹<?php echo htmlspecialchars($product['price']); ?></div>

            <div class="row">
                <div class="qty-controls">
                    <button id="dec">-</button>
                    <input id="qty" value="1"
                        style="width:54px;text-align:center;padding:6px;border-radius:6px;border:1px solid #eee">
                    <button id="inc">+</button>
                </div>
                <button class="btn" id="addCart">Add to cart</button>
            </div>

            <div class="small">Meet in person to exchange; this is a student-to-student marketplace.</div>
            
            <?php if (!empty($product['description'])): ?>
            <div class="desc">
                <strong>Description:</strong><br>
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div id="chat-root" aria-live="polite"></div>

<script type="module" src="chat.js"></script>

    <script>
        // Set shop ID based on seller's user_id for unique chat rooms per seller
        window.SHOP_ID = 'seller-<?php echo $product['user_id']; ?>';
        
        // Product data for JS
        const product = {
            id: <?php echo $product['id']; ?>,
            title: "<?php echo addslashes($product['title']); ?>",
            price: <?php echo $product['price']; ?>,
            img: "<?php echo addslashes($product['image_url'] ?: 'https://via.placeholder.com/400'); ?>",
            seller: "<?php echo addslashes($product['seller_name'] ?: 'Seller'); ?>",
            sellerId: <?php echo $product['user_id']; ?>
        };

        // qty controls
        const qtyInput = document.getElementById('qty');
        document.getElementById('inc').addEventListener('click', () => qtyInput.value = Number(qtyInput.value || 1) + 1);
        document.getElementById('dec').addEventListener('click', () => { qtyInput.value = Math.max(1, Number(qtyInput.value || 1) - 1); });

        // cart helper (same key as main page)
        const CART_KEY = 'unimart_cart_v1';
        function readCart() { try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } catch { return []; } }
        function writeCart(c) { localStorage.setItem(CART_KEY, JSON.stringify(c)); updateCartIndicator(); }

        document.getElementById('addCart').addEventListener('click', () => {
            const qty = Math.max(1, Number(qtyInput.value || 1));
            const cart = readCart();
            const idx = cart.findIndex(i => i.id === product.id);
            if (idx > -1) cart[idx].qty += qty;
            else cart.push({ id: product.id, title: product.title, price: product.price, img: product.img, seller: product.seller, qty });
            writeCart(cart);
            // tiny feedback
            alert('Added to cart');
        });

        // cart indicator
        function updateCartIndicator() {
            const cart = readCart();
            const totalQty = cart.reduce((s, i) => s + (i.qty || 0), 0);
            const el = document.getElementById('cartCountSmall');
            if (totalQty > 0) { el.style.display = 'inline-block'; el.textContent = ' ' + totalQty; } else { el.style.display = 'none'; }
        }
        updateCartIndicator();
    </script>
</body>

</html>
