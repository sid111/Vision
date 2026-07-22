<?php
$page_title = "Food Streets - FoodFinder Karachi";
include 'config/database.php';
include 'includes/food-catalog.php';
$conn = getConnection();

$favoriteAdded = false;
$favoriteRemoved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'], $_POST['street_id'])) {
    $streetId = intval($_POST['street_id']);
    if ($_POST['favorite_action'] === 'add') {
        $streetStmt = $conn->prepare("SELECT * FROM food_streets WHERE id = ?");
        $streetStmt->bind_param('i', $streetId);
        $streetStmt->execute();
        $streetResult = $streetStmt->get_result();
        $streetRow = $streetResult->fetch_assoc();
        if ($streetRow) {
            addFavoriteItem('food_street', $streetRow['id'], [
                'name' => $streetRow['name'],
                'url' => 'food-street.php?id=' . $streetRow['id'],
                'subtitle' => $streetRow['location']
            ]);
            $favoriteAdded = true;
        }
        $streetStmt->close();
    }
    if ($_POST['favorite_action'] === 'remove') {
        removeFavoriteItem('food_street', $streetId);
        $favoriteRemoved = true;
    }
    header('Location: food-streets.php');
    exit();
}

$foodStreets = getFoodStreets($conn);
$featuredStreets = array_slice($foodStreets, 0, 6);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=20260712">
    <style>
        body.food-street-list-page {
            background:
                radial-gradient(circle at top left, rgba(245, 176, 65, 0.08), transparent 28rem),
                #080a0b;
        }

        body.food-street-list-page .fs-hero {
            padding: 2rem 0 1rem;
        }

        body.food-street-list-page .fs-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(360px, 0.9fr);
            gap: 1.25rem;
            align-items: stretch;
        }

        body.food-street-list-page .fs-hero-copy,
        body.food-street-list-page .fs-hero-gallery,
        body.food-street-list-page .fs-street-card {
            background: rgba(17, 18, 16, 0.92);
            border: 1px solid rgba(245, 176, 65, 0.22);
            border-radius: 8px;
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.28);
        }

        body.food-street-list-page .fs-hero-copy {
            padding: 1.6rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
        }

        body.food-street-list-page .fs-hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: #F5B041;
            font-weight: 800;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        body.food-street-list-page .fs-hero-title {
            color: #fff;
            font-size: clamp(2rem, 4vw, 3.4rem);
            font-weight: 800;
            line-height: 1.05;
            margin: 0.5rem 0 0.9rem;
        }

        body.food-street-list-page .fs-hero-text {
            color: rgba(255, 255, 255, 0.78);
            max-width: 58ch;
            margin-bottom: 1rem;
        }

        body.food-street-list-page .fs-hero-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        body.food-street-list-page .fs-hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            min-height: 34px;
            padding: 7px 12px;
            border-radius: 8px;
            border: 1px solid rgba(245, 176, 65, 0.28);
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.86);
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
        }

        body.food-street-list-page .fs-hero-gallery {
            padding: 1rem;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        body.food-street-list-page .fs-hero-tile {
            min-height: 140px;
            border-radius: 8px;
            border: 1px solid rgba(245, 176, 65, 0.2);
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        body.food-street-list-page .fs-hero-tile::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 10, 11, 0.08), rgba(8, 10, 11, 0.76));
        }

        body.food-street-list-page .fs-hero-tile span {
            position: absolute;
            left: 0.85rem;
            right: 0.85rem;
            bottom: 0.8rem;
            z-index: 1;
            color: #fff;
            font-weight: 800;
            line-height: 1.15;
        }

        body.food-street-list-page .fs-list {
            display: grid;
            gap: 1rem;
        }

        body.food-street-list-page .fs-street-card {
            display: grid;
            grid-template-columns: minmax(280px, 360px) minmax(0, 1fr);
            overflow: hidden;
        }

        body.food-street-list-page .fs-street-image {
            min-height: 240px;
            background-size: cover;
            background-position: center;
        }

        body.food-street-list-page .fs-street-body {
            padding: 1.15rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.9rem;
        }

        body.food-street-list-page .fs-street-top {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: start;
        }

        body.food-street-list-page .fs-street-name {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
        }

        body.food-street-list-page .fs-street-meta {
            color: rgba(255, 255, 255, 0.74);
            font-size: 0.94rem;
        }

        body.food-street-list-page .fs-street-desc {
            color: rgba(255, 255, 255, 0.74);
            margin: 0.35rem 0 0;
        }

        body.food-street-list-page .fs-street-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
        }

        body.food-street-list-page .fs-street-actions .btn-gold,
        body.food-street-list-page .fs-street-actions .btn-outline-gold {
            height: 56px;
            width: 182px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            border-radius: 8px;
            line-height: 1;
            font-size: 1rem;
        }

        @media (max-width: 991.98px) {

            body.food-street-list-page .fs-hero-grid,
            body.food-street-list-page .fs-street-card {
                grid-template-columns: 1fr;
            }

            body.food-street-list-page .fs-hero-gallery {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            body.food-street-list-page .fs-hero-gallery {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="food-street-list-page">
    <?php include 'includes/nav.php'; ?>
    <div style="height:76px"></div>
    <section class="fs-hero">
        <div class="container">
            <div class="fs-hero-grid">
                <div class="fs-hero-copy">
                    <div>
                        <div class="fs-hero-kicker"><i class="fas fa-map-pin"></i> Karachi food streets</div>
                        <h1 class="fs-hero-title">Explore Karachi's best food streets</h1>
                        <p class="fs-hero-text">Browse the city's street food lanes with their own pictures, famous items, and location-specific carts. Each street opens its own detail page with the right image, menu, and highlights.</p>
                    </div>
                    <div class="fs-hero-chips">
                        <?php foreach (['Burns Road', 'Do Darya', 'Boat Basin', 'Hussainabad', 'Bahadurabad', 'Zamzama'] as $label): ?>
                            <span class="fs-hero-chip"><i class="fas fa-location-dot"></i><?php echo htmlspecialchars($label); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="fs-hero-gallery">
                    <?php foreach ($featuredStreets as $street): ?>
                        <?php $streetCatalog = ff_get_street_catalog($street['name'], $street['location'], $street['image'] ?? ''); ?>
                        <a class="fs-hero-tile" href="food-street.php?id=<?php echo intval($street['id']); ?>" style="background-image:url('<?php echo htmlspecialchars($streetCatalog['hero_image'], ENT_QUOTES); ?>');">
                            <span><?php echo htmlspecialchars($street['name']); ?><br><small style="color:rgba(255,255,255,.78);font-weight:600;"><?php echo htmlspecialchars($street['famous_for']); ?></small></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <div class="container pb-5">
        <div class="fs-list">
            <?php if (empty($foodStreets)): ?>
                <div class="glass-card p-4 text-center">
                    <p class="mb-0">No food streets are available right now.</p>
                </div>
            <?php else: ?>
                <?php foreach ($foodStreets as $street): ?>
                    <?php $streetCatalog = ff_get_street_catalog($street['name'], $street['location'], $street['image'] ?? ''); ?>
                    <article class="fs-street-card glass-card">
                        <div class="fs-street-image" style="background-image:url('<?php echo htmlspecialchars($streetCatalog['hero_image'], ENT_QUOTES); ?>');"></div>
                        <div class="fs-street-body">
                            <div class="fs-street-top">
                                <div>
                                    <h2 class="fs-street-name"><?php echo htmlspecialchars($street['name']); ?></h2>
                                    <div class="fs-street-meta"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($street['location']); ?></div>
                                    <p class="fs-street-desc"><?php echo htmlspecialchars($street['famous_for']); ?></p>
                                </div>
                                <div class="rating"><i class="fas fa-star"></i> <?php echo number_format($street['rating'], 1); ?></div>
                            </div>
                            <div class="fs-street-actions">
                                <form method="POST" class="mb-3">
                                    <input type="hidden" name="street_id" value="<?php echo intval($street['id']); ?>">
                                    <?php if (isFavoriteItem('food_street', $street['id'])): ?>
                                        <button type="submit" name="favorite_action" value="remove"
                                            class="btn btn-outline-gold">Remove Favorite</button>
                                    <?php else: ?>
                                        <button type="submit" name="favorite_action" value="add" class="btn btn-gold">Add Favorite</button>
                                    <?php endif; ?>
                                </form>
                                <a href="food-street.php?id=<?php echo $street['id']; ?>" class="btn btn-outline-gold">View Details</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <div class="container text-center">
            <p class="mb-1">FoodFinder Karachi</p>
            <small>Discover popular street food corridors across the city.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>