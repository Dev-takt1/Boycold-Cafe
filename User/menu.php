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
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="icon" href="../picture/icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Afacad:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Gaegu:wght@400;700&display=swap" rel="stylesheet">
    <title>BoyCold - Menu</title>
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
                <li class="sidebar-nav-only"><a href="#">FAVORITES</a></li>
                <li><a href="../order/cart.php" class="cart-link">
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
                <span class="sidebar-user-name"><?= $fullName ?></span>
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
                <li><a href="menu.php" class="active">MENU</a></li>
                <li><a href="../order/status.php">ORDERS</a></li>
                <li><a href="#">FAVORITES</a></li>
            </ul>
            
        </div>

        <!-- CENTER: logo -->
        <div class="logo">
             <img src="../picture/Boycold Logo 2.png" alt="BoyCold logo">
        </div>

        <div class="nav-right-group">
            <div class="nav-search" id="navSearch">
                <i class="fa-solid fa-magnifying-glass" id="searchIconBtn" onclick="toggleSearch()"></i>
                <input type="text" placeholder="Search coffee and more">
            </div>
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
    <header>
        <div class="background"></div>
        <div class="box">
            <ul>
                <li><a href="#" class="active">Popular</a></li>
                <li><a href="#">Coffee</a></li>
                <li><a href="#">Non-Coffee</a></li>
                <li><a href="#">Special Coffee</a></li>
                <li><a href="#">Matcha Fusion</a></li>
                <li><a href="#">Fruit Shake</a></li>
                <li><a href="#">Frappe Series</a></li>
                <li><a href="#">Waffles</a></li>
                <li><a href="#">Bites</a></li>
                <li><a href="#">Quesadilla</a></li>
            </ul>
        </div>
        <section class="menu-section">
            <div class="menu-content">
                <div class="product-grid" id="productGrid">
    
                    <!-- Card 1 -->
                    <div class="product-card" data-name="White Smore" data-price="129.00" data-image="../picture/FRP-White Smore CB 129 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-White Smore CB 129 1.png" alt="White Smore">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">White Smore</p>
                            <div class="card-footer">
                                <span class="card-price">₱129.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 2 -->
                    <div class="product-card" data-name="Einspanner Latte" data-price="149.00" data-image="../picture/SC-Einspanner Latte _ 149 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/SC-Einspanner Latte _ 149 1.png" alt="Einspanner Latte">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Einspanner Latte</p>
                            <div class="card-footer">
                                <span class="card-price">₱149.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 3 -->
                    <div class="product-card" data-name="Hershey Delight" data-price="95.00" data-image="../picture/FRP-Hershey Delight _ 95 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-Hershey Delight _ 95 1.png" alt="Hershey Delight">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Hershey Delight</p>
                            <div class="card-footer">
                                <span class="card-price">₱95.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 4 -->
                    <div class="product-card" data-name="Oreo Frappe" data-price="129.00" data-image="../picture/FRP-Oreo Frappe _ 105 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-Oreo Frappe _ 105 1.png" alt="Oreo Frappe">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Oreo Frappe</p>
                            <div class="card-footer">
                                <span class="card-price">₱129.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <div class="product-card" data-name="White Smore" data-price="129.00" data-image="../picture/FRP-White Smore CB 129 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-White Smore CB 129 1.png" alt="White Smore">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">White Smore</p>
                            <div class="card-footer">
                                <span class="card-price">₱129.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 2 -->
                    <div class="product-card" data-name="Einspanner Latte" data-price="149.00" data-image="../picture/SC-Einspanner Latte _ 149 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/SC-Einspanner Latte _ 149 1.png" alt="Einspanner Latte">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Einspanner Latte</p>
                            <div class="card-footer">
                                <span class="card-price">₱149.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 3 -->
                    <div class="product-card" data-name="Hershey Delight" data-price="95.00" data-image="../picture/FRP-Hershey Delight _ 95 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-Hershey Delight _ 95 1.png" alt="Hershey Delight">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Hershey Delight</p>
                            <div class="card-footer">
                                <span class="card-price">₱95.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 4 -->
                    <div class="product-card" data-name="Oreo Frappe" data-price="129.00" data-image="../picture/FRP-Oreo Frappe _ 105 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-Oreo Frappe _ 105 1.png" alt="Oreo Frappe">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Oreo Frappe</p>
                            <div class="card-footer">
                                <span class="card-price">₱129.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="product-card" data-name="White Smore" data-price="129.00" data-image="../picture/FRP-White Smore CB 129 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-White Smore CB 129 1.png" alt="White Smore">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">White Smore</p>
                            <div class="card-footer">
                                <span class="card-price">₱129.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 2 -->
                    <div class="product-card" data-name="Einspanner Latte" data-price="149.00" data-image="../picture/SC-Einspanner Latte _ 149 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/SC-Einspanner Latte _ 149 1.png" alt="Einspanner Latte">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Einspanner Latte</p>
                            <div class="card-footer">
                                <span class="card-price">₱149.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 3 -->
                    <div class="product-card" data-name="Hershey Delight" data-price="95.00" data-image="../picture/FRP-Hershey Delight _ 95 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-Hershey Delight _ 95 1.png" alt="Hershey Delight">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Hershey Delight</p>
                            <div class="card-footer">
                                <span class="card-price">₱95.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                    <!-- Card 4 -->
                    <div class="product-card" data-name="Oreo Frappe" data-price="129.00" data-image="../picture/FRP-Oreo Frappe _ 105 1.png">
                        <div class="card-image">
                            <div class="card-image-placeholder">
                                <div class="card-top">
                                    <span class="card-badge">Popular<i class="fa-solid fa-star"></i></span>
                                    <button class="card-heart"><i class="fa-solid fa-heart"></i></button>
                                </div>
                                <img src="../picture/FRP-Oreo Frappe _ 105 1.png" alt="Oreo Frappe">
                            </div>
                        </div>
                        <div class="card-info">
                            <p class="card-name">Oreo Frappe</p>
                            <div class="card-footer">
                                <span class="card-price">₱129.00</span>
                                <button class="card-add"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </section>
    </header>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="../picture/icon2.png" alt="BoyCold logo">
                <h1>BOYCOLD CAFE</h1>
                <p>© 2024 BoyCold Cafe. All rights reserved.</p>
            </div>
            <div class="footer-links">
                <ul>
                    <li><a href="#">Contact Information</a></li>
                    <li><a href="#">Customer Links</a></li>
                    <li><a href="#">Company Information</a></li>
                    <li><a href="#">Legal Links</a></li>
                    <li><a href="#">Social Media Links</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
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

        function toggleAvatarDropdown() {
            document.getElementById('avatarDropdown').classList.toggle('open');
        }

        document.addEventListener('click', function(e) {
            const drop = document.getElementById('avatarDropdown');
            const btn  = document.getElementById('navAvatarBtn');
            if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) {
                drop.classList.remove('open');
            }
        });

        function toggleSearch() {
            const search = document.getElementById('navSearch');
            const btn = document.getElementById('searchIconBtn');
            const isOpen = search.classList.toggle('open');
            btn.classList.toggle('active', isOpen);
            if (isOpen) {
                setTimeout(() => search.querySelector('input').focus(), 420);
            } else {
                search.querySelector('input').value = '';
            }
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
        // Category filter active state
        document.querySelectorAll('.box ul li a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.box ul li a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Heart toggle
        document.querySelectorAll('.card-heart').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isLiked = icon.style.color === 'rgb(229, 57, 53)';
                if (isLiked) {
                    icon.style.color = 'transparent';
                    icon.style.webkitTextStroke = '1.5px #e53935';
                } else {
                    icon.style.color = '#e53935';
                    icon.style.webkitTextStroke = '0';
                }
            });
        });

        // add button — pass product data to ordercustom.php
        document.querySelectorAll('.card-add').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.product-card');
                const params = new URLSearchParams({
                    name:  card.dataset.name,
                    price: card.dataset.price,
                    image: card.dataset.image
                });
                window.location.href = 'ordercustom.php?' + params.toString();
            });
        });
    </script>
</body>
</html>