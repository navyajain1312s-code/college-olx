<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'] ?? 'User';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Sell on UniMart</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Quicksand:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #FDF6F0;
            --card: #fff;
            --accent: #FF92A4;
            --muted: #7B7B86;
            --pill: #A2D2FF;
            --glass: rgba(255, 255, 255, 0.75);
        }

        * {
            box-sizing: border-box
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background: var(--bg);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center
        }

        .wrap {
            width: 100%;
            max-width: 980px
        }

        header {
            display:flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px
        }

        .brand {
            font-family: 'Fredoka';
            color: var(--accent);
            font-size: 1.4rem;
            text-decoration: none
        }

        .auth-btns {
            display: flex;
            gap: 8px;
            align-items: center
        }

        .btn {
            background: var(--accent);
            color: #fff;
            padding: 9px 12px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .btn.ghost {
            background: transparent;
            border: 2px solid var(--accent);
            color: var(--accent)
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 20px
        }

        .card {
            background: var(--card);
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06)
        }

        .h2 {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 8px;
            color: var(--muted)
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        label {
            font-size: 0.85rem;
            color: var(--muted)
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #eee;
            font-size: 0.95rem;
            outline: none;
            background: var(--glass)
        }

        .row {
            display: flex;
            gap: 10px
        }

        .small {
            font-size: 0.85rem;
            color: #666
        }

        .preview {
            width: 100%;
            height: 220px;
            background: #fafafa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px dashed #eee
        }

        .preview img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 360px;
            overflow: auto;
            padding-right: 6px
        }

        .list-item {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 8px;
            border-radius: 10px;
            border: 1px solid #f0f0f0
        }

        .thumb {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            flex-shrink: 0
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .item-meta {
            flex: 1
        }

        .muted {
            color: #888;
            font-size: 13px
        }

        .danger {
            background: #FF6B6B;
            color: #fff;
            border: none;
            padding: 6px 8px;
            border-radius: 8px;
            cursor: pointer
        }

        .popup {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #4a4af4;
            color: white;
            padding: 15px 22px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease, transform 0.4s ease;
            transform: translateY(20px);
            font-size: 15px;
            z-index: 1000;
        }

        .popup.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }

        @media (max-width:900px) {
            .grid {
                grid-template-columns: 1fr
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <header>
            <a class="brand" href="1.php">UniMart • Sell</a>
            <div class="auth-btns">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:14px;color:var(--muted)">👤 <?php echo htmlspecialchars($username); ?> (ID: <?php echo $_SESSION['user_id']; ?>)</span>
                </div>
                <a href="logout.php" class="btn ghost">Sign out</a>
            </div>
        </header>

        <main class="grid">
            <!-- left: form -->
            <section class="card">
                <div>
                    <div class="h2">List an item — quick & cute</div>
                    <p class="small">Fill in the details below to list your item on UniMart.</p>
                </div>

                <form id="sellForm">
                    <label>Title
                        <input id="title" type="text" required placeholder="e.g. Calculus book - good condition">
                    </label>

                    <div class="row">
                        <label style="flex:1">Price (₹)
                            <input id="price" type="number" min="0" step="1" placeholder="e.g. 250" required>
                        </label>
                        <label style="width:140px">Category
                            <select id="category">
                                <option>Books</option>
                                <option>Stationery</option>
                                <option>Electronics</option>
                                <option>Bags</option>
                            </select>
                        </label>
                    </div>

                    <label>Short description
                        <textarea id="desc" rows="3" placeholder="Condition, edition, any defects..."></textarea>
                    </label>

                    <label>Image URL (optional)
                        <input id="imageUrl" type="text" placeholder="https://example.com/image.jpg" />
                    </label>

                    <div class="preview" id="previewBox">
                        <div class="small">Paste an image URL above to preview</div>
                    </div>

                    <button id="postBtn" class="btn" type="submit">Post listing</button>

                    <div id="status" class="small" role="status" aria-live="polite"></div>
                </form>
            </section>

            <!-- right: my listings -->
            <aside class="card">
                <div class="h2">My listings</div>
                <div class="small">Products you've listed</div>
                <div id="list" class="list" style="margin-top:10px">
                    <div class="muted">Loading...</div>
                </div>
            </aside>
        </main>
    </div>

    <!-- Popup Notification -->
    <div id="popup" class="popup">
        <p id="popup-text"></p>
    </div>

    <script>
        const imageUrlInput = document.getElementById('imageUrl');
        const previewBox = document.getElementById('previewBox');
        const sellForm = document.getElementById('sellForm');
        const postBtn = document.getElementById('postBtn');
        const status = document.getElementById('status');
        const listEl = document.getElementById('list');
        const popup = document.getElementById("popup");
        const popupText = document.getElementById("popup-text");

        function showPopup(message, isError = false) {
            popupText.textContent = message;
            popup.style.background = isError ? '#dc2626' : '#4a4af4';
            popup.classList.add("show");
            setTimeout(() => {
                popup.classList.remove("show");
            }, 3000);
        }

        function showStatus(msg, err = false) {
            status.textContent = msg || '';
            status.style.color = err ? '#b30000' : '#333';
        }

        // Preview image URL
        imageUrlInput.addEventListener('input', () => {
            const url = imageUrlInput.value.trim();
            if (!url) {
                previewBox.innerHTML = '<div class="small">Paste an image URL above to preview</div>';
                return;
            }
            previewBox.innerHTML = `<img src="${url}" alt="preview" onerror="this.parentElement.innerHTML='<div class=\\'small\\'>Invalid image URL</div>'">`;
        });

        // Post listing
        sellForm.addEventListener('submit', async (ev) => {
            ev.preventDefault();

            const title = document.getElementById('title').value.trim();
            const price = document.getElementById('price').value;
            const category = document.getElementById('category').value;
            const description = document.getElementById('desc').value.trim();
            const imageUrl = imageUrlInput.value.trim();

            if (!title) {
                showStatus('Enter a title', true);
                return;
            }
            if (!price || price < 0) {
                showStatus('Enter a valid price', true);
                return;
            }

            showStatus('Posting...');
            postBtn.disabled = true;

            try {
                const response = await fetch('add_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ title, price, category, description, imageUrl })
                });

                const data = await response.json();

                if (data.success) {
                    showPopup(data.message);
                    showStatus('');
                    sellForm.reset();
                    previewBox.innerHTML = '<div class="small">Paste an image URL above to preview</div>';
                    loadMyListings();
                } else {
                    showPopup(data.message, true);
                    showStatus(data.message, true);
                }
            } catch (error) {
                console.error('Error:', error);
                showPopup('An error occurred. Please try again.', true);
                showStatus('Error posting listing', true);
            } finally {
                postBtn.disabled = false;
            }
        });

        // Load my listings
        async function loadMyListings() {
            listEl.innerHTML = '<div class="muted">Loading...</div>';
            try {
                const response = await fetch('get_my_products.php');
                const data = await response.json();

                if (data.success && data.products.length > 0) {
                    listEl.innerHTML = '';
                    data.products.forEach(product => {
                        const div = document.createElement('div');
                        div.className = 'list-item';
                        div.innerHTML = `
                            <div class="thumb"><img src="${product.image_url || 'https://via.placeholder.com/300'}" alt=""></div>
                            <div class="item-meta">
                                <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
                                    <div style="font-weight:700">${product.title}</div>
                                    <div style="min-width:60px;text-align:right;font-weight:700">₹${product.price}</div>
                                </div>
                                <div class="muted">${product.category || ''}</div>
                                <div class="muted" style="font-size:12px;margin-top:6px">${(product.description || '').slice(0, 80)}</div>
                            </div>
                            <div>
                                <button class="danger" data-id="${product.id}" title="Delete listing">Delete</button>
                            </div>
                        `;
                        listEl.appendChild(div);
                    });

                } else {
                    listEl.innerHTML = '<div class="muted">No listings yet. Post your first item!</div>';
                }
            } catch (e) {
                console.error(e);
                listEl.innerHTML = '<div class="muted">Failed to load listings.</div>';
            }
        }

        // Event delegation for delete buttons
        listEl.addEventListener('click', async (e) => {
            const btn = e.target.closest('.danger');
            if (!btn) return;

            if (!confirm('Delete this listing? This cannot be undone.')) return;
            
            const id = btn.dataset.id;
            console.log('Attempting to delete ID:', id);
            
            try {
                const response = await fetch('delete_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const data = await response.json();
                console.log('Server response:', data);
                
                if (data.success) {
                    showPopup('Product deleted');
                    loadMyListings();
                } else {
                    showPopup(data.message, true);
                }
            } catch (e) {
                console.error('Delete error:', e);
                showPopup('Delete failed', true);
            }
        });

        // Load on page load
        loadMyListings();
    </script>
</body>

</html>
