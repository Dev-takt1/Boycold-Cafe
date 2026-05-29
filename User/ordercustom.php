<?php
session_start();
require_once '../config/db_config.php';

// Session guard — redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch fresh user data from DB (same pattern as account.php)
$stmt = $connect->prepare("SELECT Firstname, Lastname, email, avatar FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$fullName  = htmlspecialchars($user['Firstname'] . ' ' . $user['Lastname']);
$userEmail = htmlspecialchars($user['email']);
$avatar    = $user['avatar'] ? htmlspecialchars($user['avatar']) : '';

// Keep session in sync
$_SESSION['user_name']  = $user['Firstname'] . ' ' . $user['Lastname'];
$_SESSION['user_email'] = $user['email'];

// Product data passed from menu.php via URL query params
$productName  = isset($_GET['name'])  ? htmlspecialchars(strip_tags($_GET['name']))  : 'Unknown Product';
$productPrice = isset($_GET['price']) ? htmlspecialchars(strip_tags($_GET['price'])) : '0.00';
$productImage = isset($_GET['image']) ? htmlspecialchars(strip_tags($_GET['image'])) : '../picture/SC-Einspanner Latte _ 149 1.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ordercustom.css">
    <link rel="icon" href="../picture/icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Afacad:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Gaegu:wght@400;700&display=swap" rel="stylesheet">
    <title>BoyCold - Order Status</title>
</head>

<body>

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- SIDEBAR DRAWER -->
    <div class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a href="menu.php">MENU</a></li>
                <li><a href="../order/status.php">ORDER</a></li>
                <li><a href="../store/store.php">STORES</a></li>
                <li class="sidebar-nav-only-not"><a href="../order/status.php">ORDERS</a></li>
                <li class="sidebar-nav-only"><a href="favorites.php">FAVORITES</a></li>
                <li><a href="cart.php" class="cart-link">
                        <i class="fa-solid fa-cart-shopping fa-lg" style="color: rgb(0, 0, 0);"></i> CART
                    </a></li>
            </ul>
        </nav>
        <div class="sidebar-user">
            <a href="account.php" class="sidebar-avatar-link">
                <div class="sidebar-avatar" id="sidebarAvatarWrap">
                    <?php if ($avatar): ?>
                        <img id="sidebarAvatarImg" src="<?= $avatar ?>" alt="avatar" style="display:block;">
                        <i class="fa-solid fa-user" id="sidebarAvatarIcon" style="display:none;"></i>
                    <?php else: ?>
                        <img id="sidebarAvatarImg" src="" alt="avatar" style="display:none;">
                        <i class="fa-solid fa-user" id="sidebarAvatarIcon"></i>
                    <?php endif; ?>
                </div>
            </a>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name" id="display-fullname"><?= $fullName ?></span>
                <span class="sidebar-user-email"><?= $userEmail ?></span>
            </div>
        </div>
    </div>

    <!-- MAIN NAV -->
    <nav id="mainNav">
        <div class="nav-box"></div>
        <div class="nav-left-group">
            <div class="hamburger" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </div>
            <ul class="nav-links">
                <li><a href="home.php">HOME</a></li>
                <li><a href="menu.php">MENU</a></li>
                <li><a href="../order/status.php">ORDERS</a></li>
                <li><a href="favorites.php">FAVORITES</a></li>
            </ul>
        </div>
        <div class="logo">
            <img src="../picture/Boycold Logo 2.png" alt="BoyCold">
        </div>
        <div class="nav-right-group">
            <a href="cart.php" class="cart-link">
                <i class="fa-solid fa-cart-shopping fa-lg" style="color: rgb(0, 0, 0);"></i>
            </a>
            <div class="avatar-dropdown-wrap">
                <div class="sidebar-avatar" id="navAvatarBtn" onclick="toggleAvatarDropdown()">
                    <?php if ($avatar): ?>
                        <img id="navAvatarImg" src="<?= $avatar ?>" alt="avatar" style="display:block;">
                        <i class="fa-solid fa-user" id="navAvatarIcon" style="display:none;"></i>
                    <?php else: ?>
                        <img id="navAvatarImg" src="" alt="avatar" style="display:none;">
                        <i class="fa-solid fa-user" id="navAvatarIcon"></i>
                    <?php endif; ?>
                </div>
                <div class="avatar-dropdown" id="avatarDropdown">
                    <a href="account.php"><i class="fa-solid fa-user"></i> Account</a>
                    <hr>
                    <a href="../logout.php" class="dropdown-logout"><i class="fa-solid fa-right-from-bracket"></i> Log out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ORDER MAIN -->
    <main class="order-main">

        <div class="order-header">
            <h1>Customize Your Order</h1>
        </div>

        <!-- EMPTY STATE CARD -->
        <div class="product-box">
            <div class="left-side">
                <div class="product-image">
                    <img src="<?= $productImage ?>" alt="<?= $productName ?>" />
                </div>

                <div class="mini-card" id="miniCard" style="display:none;">
                    <div class="mini-image">
                        <img src="<?= $productImage ?>" alt="<?= $productName ?>" />
                    </div>
                    <div class="mini-info">
                        <h4><?= $productName ?></h4>
                        <p id="miniMilk">Original Milk</p>
                        <p id="miniAddons">No Add-ons • Pick-Up</p>
                        <p id="miniQty">Qty: 1</p>
                    </div>
                </div>
            </div>

            <div class="right-side">
                <h2 class="product-name"><?= $productName ?></h2>
                <div class="line"></div>
                <div class="price">₱<?= $productPrice ?></div>

                <!-- Milk Choice -->
                <div class="section" id="section-milk">

                    <div class="section-title">
                        <i class="fa-solid fa-bottle-water"></i>
                        Milk Choice
                    </div>

                    <div class="option-group">
                        <button class="option active">Original</button>
                        <button class="option">Oat Milk +₱15</button>
                    </div>

                </div>

                <!-- Add ons -->
                <div class="section" id="section-addons">

                    <div class="section-title">
                        <i class="fa-solid fa-circle-plus"></i>
                        Add-ons
                    </div>

                    <div class="option-group">
                        <button class="option">Espresso Shot +₱15</button>
                        <button class="option">Whipped Cream +₱15</button>
                        <button class="option">Chocolate Drizzle +₱15</button>
                    </div>

                </div>

                <div class="content-row">
                    <div>
                        <!-- Order Type -->
                        <div class="section" id="section-ordertype">
                            <div class="section-title">
                                <i class="fa-solid fa-bag-shopping"></i>
                                Order Type
                            </div>
                            <div class="option-group">
                                <button class="option active">Pick-Up</button>
                                <button class="option">Delivery</button>
                            </div>
                        </div>
                        <div class="section">
                            <div class="section-title">
                                <i class="fa-solid fa-utensils"></i>
                                Quantity
                            </div>
                            <div class="quantity-box">
                                <button class="qty-btn" id="qtyMinus">-</button>
                                <div class="qty-number" id="qtyValue">1</div>
                                <button class="qty-btn" id="qtyPlus">+</button>
                            </div>
                        </div>
                    </div>
                    <!-- Right -->
                    <div style="flex:1; min-width:250px;">
                        <div class="section">
                            <div class="section-title">
                                <input type="checkbox">
                                Special Instructions
                            </div>
                            <textarea placeholder="Any special request? (e.g less ice, less sugar)"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Bottom -->
                <div class="bottom-area">
                    <div class="total">
                        Total:
                        <span id="totalPrice">₱<?= $productPrice ?></span>
                    </div>
                    <div class="buttons">
                        <button class="btn cart-btn">
                            <i class="fa-solid fa-cart-shopping"></i>
                            Add to Cart
                        </button>
                        <button class="btn checkout-btn">
                            Proceed to Checkout
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer>
        <div>© 2026 BoyCold Cafe. All rights reserved.</div>
        <div class="footer-links">
            <a href="#">About Us</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Privacy</a>
        </div>
        <div class="footer-logo">
            BOYCOLD CAFE
        </div>
    </footer>

    <script>
        /* ── Product Data from PHP ── */
        const basePrice = parseFloat('<?= $productPrice ?>');
        let addOnTotal = 0;

        function recalcTotal() {
            const qty = parseInt(document.getElementById('qtyValue').textContent) || 1;
            const total = (basePrice + addOnTotal) * qty;
            document.getElementById('totalPrice').textContent = '₱' + total.toFixed(2);
        }

        function updateMiniCard() {
            // Show the mini-card on first interaction (with fade-in)
            const miniCard = document.getElementById('miniCard');
            if (miniCard.style.display === 'none' || miniCard.style.display === '') {
                miniCard.style.display = 'flex';
                miniCard.classList.remove('animate-in');
                void miniCard.offsetWidth; // force reflow
                miniCard.classList.add('animate-in');
            }

            // Milk — use ID so selector is exact
            const milkGroup = document.querySelector('#section-milk .option-group');
            const activeMilk = milkGroup ? milkGroup.querySelector('.option.active') : null;
            const milkText = activeMilk ? activeMilk.textContent.replace(/\s*\+₱\d+/, '').trim() : 'Original';
            document.getElementById('miniMilk').textContent = milkText + ' Milk';

            // Add-ons — use ID so all active add-on buttons are captured
            const activeAddons = [...document.querySelectorAll('#section-addons .option.active')];
            const addonText = activeAddons.length
                ? activeAddons.map(b => b.textContent.replace(/\s*\+₱\d+/, '').trim()).join(', ')
                : 'No Add-ons';

            // Order type — use ID
            const orderGroup = document.querySelector('#section-ordertype .option-group');
            const activeOrder = orderGroup ? orderGroup.querySelector('.option.active') : null;
            const orderText = activeOrder ? activeOrder.textContent.trim() : 'Pick-Up';
            document.getElementById('miniAddons').textContent = addonText + ' • ' + orderText;

            // Qty
            const qty = parseInt(document.getElementById('qtyValue').textContent) || 1;
            document.getElementById('miniQty').textContent = 'Qty: ' + qty;
        }

        /* ── Option Buttons (Milk / Order Type = single-select; Add-ons = multi-select) ── */
        document.querySelectorAll('.section .option-group').forEach(group => {
            const section = group.closest('.section');
            const isAddOn = section && section.id === 'section-addons';

            group.querySelectorAll('.option').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (isAddOn) {
                        this.classList.toggle('active');
                    } else {
                        group.querySelectorAll('.option').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                    }

                    // Recalculate add-on cost using the reliable ID selector
                    addOnTotal = 0;
                    document.querySelectorAll('#section-addons .option.active').forEach(b => {
                        const match = b.textContent.match(/\+₱(\d+)/);
                        if (match) addOnTotal += parseInt(match[1]);
                    });
                    recalcTotal();
                    updateMiniCard();
                });
            });
        });

        /* ── Quantity ── */
        document.getElementById('qtyMinus').addEventListener('click', function() {
            const el = document.getElementById('qtyValue');
            let q = parseInt(el.textContent);
            if (q > 1) { el.textContent = q - 1; recalcTotal(); updateMiniCard(); }
        });
        document.getElementById('qtyPlus').addEventListener('click', function() {
            const el = document.getElementById('qtyValue');
            el.textContent = parseInt(el.textContent) + 1;
            recalcTotal();
            updateMiniCard();
        });

        /* ── Nav Sidebar ── */
        const nav = document.getElementById('mainNav');

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isOpen = sidebar.classList.toggle('open');
            overlay.classList.toggle('open', isOpen);
            nav.classList.toggle('sidebar-open', isOpen);
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('open');
            nav.classList.remove('sidebar-open');
        }

        function toggleSearch() {
            const search = document.getElementById('navSearch');
            const btn = document.getElementById('searchIconBtn');
            const isOpen = search.classList.toggle('open');
            btn.classList.toggle('active', isOpen);
            if (isOpen) setTimeout(() => search.querySelector('input').focus(), 420);
            else search.querySelector('input').value = '';
        }

        document.addEventListener('click', function(e) {
            const search = document.getElementById('navSearch');
            const btn = document.getElementById('searchIconBtn');
            if (!search || !btn) return;
            if (!search.contains(e.target) && !btn.contains(e.target)) {
                search.classList.remove('open');
                btn.classList.remove('active');
                search.querySelector('input').value = '';
            }
        });
    </script>

</body>

</html>