<?php
$user = function_exists('getCurrentUser') ? getCurrentUser() : null;
$currentPage = basename($_SERVER['PHP_SELF']);
$restaurantPages = ['restaurants.php', 'restaurant.php'];
$cafePages = ['cafes.php', 'cafe.php'];
$foodStreetPages = ['food-streets.php', 'food-street.php'];
?>
<style>
    :root {
        --gold: #F5B041;
        --gold-dark: #D4A017;
        --nav-bg: rgba(8, 10, 13, 0.96);
    }

    .vision-site-nav {
        min-height: 76px;
        background: var(--nav-bg);
        border-bottom: 1px solid rgba(245, 176, 65, 0.22);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        padding: 0.75rem 0;
        z-index: 1050;
    }

    .vision-site-nav .navbar-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
        color: #fff;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-decoration: none;
    }

    .vision-site-nav .brand-mark {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        border: 2px solid var(--gold);
        border-radius: 50%;
        font-size: 1.25rem;
        line-height: 1;
    }

    .vision-site-nav .brand-text {
        display: grid;
        line-height: 1.05;
    }

    .vision-site-nav .brand-text small {
        color: var(--gold);
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.08em;
    }

    .vision-site-nav .nav-link {
        position: relative;
        color: rgba(255, 255, 255, 0.84) !important;
        font-size: 0.94rem;
        font-weight: 600;
        margin: 0 0.35rem;
        padding: 0.55rem 0.25rem;
        transition: color 0.2s ease;
    }

    .vision-site-nav .nav-link:hover,
    .vision-site-nav .nav-link.active {
        color: var(--gold) !important;
    }

    .vision-site-nav .nav-link.active::after {
        content: '';
        position: absolute;
        left: 0.25rem;
        right: 0.25rem;
        bottom: 0.25rem;
        height: 2px;
        background: var(--gold);
        border-radius: 999px;
    }

    .vision-site-nav .navbar-toggler {
        border: 1px solid rgba(245, 176, 65, 0.45);
        box-shadow: none;
    }

    .vision-site-nav .navbar-toggler-icon {
        filter: invert(1);
    }

    .vision-site-nav .btn-gold,
    .vision-site-nav .btn-outline-gold {
        border-radius: 8px;
        padding: 0.45rem 0.85rem;
        font-size: 0.86rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .vision-site-nav .btn-gold {
        background: linear-gradient(95deg, #F5B041, #E67E22);
        border: 1px solid transparent;
        color: #0A0C10;
    }

    .vision-site-nav .btn-outline-gold {
        background: transparent;
        border: 1px solid rgba(245, 176, 65, 0.7);
        color: var(--gold);
    }

    .vision-site-nav .btn-outline-gold:hover,
    .vision-site-nav .btn-gold:hover {
        background: var(--gold);
        border-color: var(--gold);
        color: #0A0C10;
    }

    .vision-site-nav .nav-auth-actions {
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    @media (max-width: 991.98px) {
        .vision-site-nav .navbar-collapse {
            margin-top: 0.9rem;
            padding: 1rem;
            background: rgba(13, 15, 15, 0.96);
            border: 1px solid rgba(245, 176, 65, 0.18);
            border-radius: 10px;
        }

        .vision-site-nav .navbar-nav {
            flex-wrap: wrap;
            justify-content: center;
        }

        .vision-site-nav .nav-item {
            width: 100%;
            text-align: center;
        }

        .vision-site-nav .nav-link {
            margin: 0;
            padding: 0.72rem 0;
        }

        .vision-site-nav .nav-link.active::after {
            left: 0;
            right: auto;
            width: 42px;
            bottom: 0.45rem;
        }

        .vision-site-nav .nav-auth-actions {
            justify-content: center;
            margin-top: 0.8rem;
            width: 100%;
        }
    }
</style>

<nav class="navbar navbar-expand-lg fixed-top vision-site-nav">
    <div class="container">
        <a class="navbar-brand" href="index.php" aria-label="FoodFinder Karachi home">
            <span class="brand-mark">V</span>
            <span class="brand-text">
                <span>FoodFinder</span>
                <small>KARACHI</small>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo in_array($currentPage, $restaurantPages) ? 'active' : ''; ?>" href="restaurants.php">Restaurants</a></li>
                <li class="nav-item"><a class="nav-link <?php echo in_array($currentPage, $cafePages) ? 'active' : ''; ?>" href="cafes.php">Cafes</a></li>
                <li class="nav-item"><a class="nav-link <?php echo in_array($currentPage, $foodStreetPages) ? 'active' : ''; ?>" href="food-streets.php">Food Streets</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'favorites.php' ? 'active' : ''; ?>" href="favorites.php">Favorites</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'about.php' ? 'active' : ''; ?>" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2 nav-auth-actions">
                <?php if ($user): ?>
                    <?php $displayName = ($user['role'] === 'admin') ? 'Admin' : $user['name']; ?>
                    <span class="text-light small me-2">Hi, <?php echo htmlspecialchars($displayName); ?></span>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="admin-panel.php" class="btn btn-outline-gold btn-sm">Admin</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-outline-gold btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-gold btn-sm">Login</a>
                    <a href="signup.php" class="btn btn-gold btn-sm">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>