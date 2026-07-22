<?php
$page_title = 'Food Street Details - FoodFinder Karachi';
include 'config/database.php';
include 'includes/food-catalog.php';
$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: food-streets.php');
    exit();
}

$stmt = $conn->prepare('SELECT * FROM food_streets WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$street = $result->fetch_assoc();
if (!$street) {
    header('Location: food-streets.php');
    exit();
}

$streetCatalog = ff_get_street_catalog($street['name'], $street['location']);

$favoriteAdded = false;
$favoriteRemoved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'])) {
    if ($_POST['favorite_action'] === 'add') {
        addFavoriteItem('food_street', $street['id'], ['name' => $street['name'], 'url' => 'food-street.php?id=' . $street['id'], 'subtitle' => $street['location']]);
        $favoriteAdded = true;
    }
    if ($_POST['favorite_action'] === 'remove') {
        removeFavoriteItem('food_street', $street['id']);
        $favoriteRemoved = true;
    }
}

$isFavorite = isFavoriteItem('food_street', $street['id']);
$heroImage = $streetCatalog['hero_image'];
$locationImage = $streetCatalog['location_image'] ?? $heroImage;
$galleryImages = $streetCatalog['gallery_images'];
$menuItems = $streetCatalog['items'];
$streetSummary = $streetCatalog['summary'];
$streetLabel = $streetCatalog['label'];

$reviews = [
    ['rating' => 5, 'comment' => 'Best street food vibe. Everything feels fresh, busy and full of flavor.', 'created_at' => date('Y-m-d', strtotime('-2 days')), 'user_name' => 'Danish Ahmed'],
    ['rating' => 5, 'comment' => 'Perfect late night spot for rolls, bun kabab and chaat.', 'created_at' => date('Y-m-d', strtotime('-1 week')), 'user_name' => 'Areeba Noor']
];

$mapQuery = urlencode(trim($street['name'] . ' ' . $street['location']));


$reviews = [];
$reviewStmt = $conn->prepare('SELECT r.rating, r.comment, r.created_at, COALESCE(u.name, "Guest") AS user_name FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.food_street_id = ? ORDER BY r.created_at DESC LIMIT 3');
$reviewStmt->bind_param('i', $id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();
while ($review = $reviewResult->fetch_assoc()) {
    $reviews[] = $review;
}
$reviewStmt->close();

$reviewSubmitted = isset($_GET['review_submitted']);

if (empty($reviews)) {
    $reviews = [
        ['rating' => 5, 'comment' => 'Best street food vibe. Everything feels fresh, busy and full of flavor.', 'created_at' => date('Y-m-d', strtotime('-2 days')), 'user_name' => 'Danish Ahmed'],
        ['rating' => 5, 'comment' => 'Perfect late night spot for rolls, bun kabab and chaat.', 'created_at' => date('Y-m-d', strtotime('-1 week')), 'user_name' => 'Areeba Noor']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($street['name']) . ' - FoodFinder Karachi'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=20260712">
    <?php include 'includes/detail-style.php'; ?>
</head>

<body class="detail-page">
    <?php include 'includes/nav.php'; ?>
    <div style="height:76px"></div>
    <section class="rd-hero" style="background-image: url('<?php echo htmlspecialchars($heroImage, ENT_QUOTES); ?>');">
        <div class="container py-5">
            <div class="row align-items-end g-4">
                <div class="col-lg-8">
                    <a href="food-streets.php" class="rd-back"><i class="fas fa-arrow-left"></i> Back to Food Streets</a>
                    <div class="mt-3"><span class="rd-chip"><i class="fas fa-star" style="color:#F5B041"></i><?php echo number_format($street['rating'], 1); ?> (900+ Reviews)</span></div>
                    <h1 class="rd-title"><?php echo htmlspecialchars($street['name']); ?></h1>
                    <div class="rd-kicker"><span><?php echo htmlspecialchars($street['famous_for']); ?></span><span>&bull;</span><span><i class="fas fa-map-marker-alt rd-icon"></i><?php echo htmlspecialchars($street['location']); ?></span></div>
                    <div class="rd-meta-row"><span class="rd-status open"><i class="fas fa-circle"></i> Open Now</span><span class="rd-chip"><i class="fas fa-fire rd-icon"></i><?php echo htmlspecialchars($streetLabel); ?></span><span class="rd-chip"><i class="fas fa-wallet rd-icon"></i>Budget Friendly</span></div>
                    <div class="rd-action-row">
                        <a href="#menu" class="rd-btn"><i class="fas fa-utensils"></i> View Food Items</a>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapQuery; ?>" class="rd-outline-btn" target="_blank" rel="noopener"><i class="fas fa-location-arrow"></i> Directions</a>
                        <form method="POST"><button type="submit" name="favorite_action" value="<?php echo $isFavorite ? 'remove' : 'add'; ?>" class="rd-outline-btn"><i class="<?php echo $isFavorite ? 'fas' : 'far'; ?> fa-heart"></i><?php echo $isFavorite ? 'Saved' : 'Save'; ?></button></form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rd-thumbs">
                        <?php foreach (array_slice($galleryImages, 1, 4) as $thumb): ?>
                            <div class="rd-thumb" style="background-image: url('<?php echo htmlspecialchars($thumb, ENT_QUOTES); ?>');"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <main class="container rd-main">
        <div class="row g-4">
            <section class="col-lg-8">
                <div class="rd-panel">
                    <div class="rd-about-grid">
                        <div class="rd-logo-box">
                            <div><i class="fas fa-road"></i><strong><?php echo htmlspecialchars($street['name']); ?></strong><span class="rd-muted small">Food Street</span></div>
                        </div>
                        <div>
                            <h2 class="rd-panel-title">About <?php echo htmlspecialchars($street['name']); ?></h2>
                            <p class="rd-muted mb-0"><?php echo htmlspecialchars($streetSummary); ?></p>
                            <div class="rd-amenities">
                                <span><i class="fas fa-fire rd-icon"></i><?php echo htmlspecialchars($street['famous_for']); ?></span>
                                <span><i class="fas fa-moon rd-icon"></i>Late Night</span>
                                <span><i class="fas fa-users rd-icon"></i>Family Friendly</span>
                                <span><i class="fas fa-wallet rd-icon"></i>Affordable</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rd-panel" id="menu">
                    <h2 class="rd-panel-title">Popular Carts & Stalls</h2>
                    <div class="rd-tabs">
                        <?php foreach ($menuItems as $index => $item): ?>
                            <a class="<?php echo $index === 0 ? 'active' : ''; ?>" href="food-detail.php?type=street&id=<?php echo intval($street['id']); ?>&item=<?php echo urlencode($item['slug']); ?>"><?php echo htmlspecialchars($item['tag']); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <div class="rd-menu-grid">
                        <?php foreach ($menuItems as $item): ?>
                            <article class="rd-menu-card">
                                <div class="rd-menu-image" style="background-image: url('<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>');"><span class="rd-menu-tag"><?php echo htmlspecialchars($item['tag']); ?></span></div>
                                <div class="rd-menu-body">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="rd-price"><?php echo htmlspecialchars($item['price']); ?></span>
                                        <a class="rd-soft-link" href="food-detail.php?type=street&id=<?php echo intval($street['id']); ?>&item=<?php echo urlencode($item['slug']); ?>">Details</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <aside class="col-lg-4">
                <div class="position-sticky" style="top:100px;">
                    <div class="rd-panel">
                        <?php if ($favoriteAdded): ?><div class="alert alert-success">Added to favorites.</div><?php elseif ($favoriteRemoved): ?><div class="alert alert-warning">Removed from favorites.</div><?php endif; ?>
                        <h2 class="rd-panel-title">Food Street Information</h2>
                        <div class="rd-info-list">
                            <div class="rd-info-line"><i class="fas fa-map-marker-alt rd-icon"></i><span><strong>Location</strong><?php echo htmlspecialchars($street['location']); ?></span></div>
                            <div class="rd-info-line"><i class="fas fa-fire rd-icon"></i><span><strong>Famous For</strong><?php echo htmlspecialchars($street['famous_for']); ?></span></div>
                            <div class="rd-info-line"><i class="fas fa-star rd-icon"></i><span><strong>Rating</strong><?php echo number_format($street['rating'], 1); ?> / 5.0</span></div>
                        </div>
                    </div>
                    <div class="rd-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <h2 class="rd-panel-title mb-0">Customer Reviews</h2>
                            <a href="review.php?type=food_street&id=<?php echo intval($street['id']); ?>" class="rd-soft-link">Write Review</a>
                        </div>
                        <?php if (!empty($reviewSubmitted)): ?>
                            <div class="alert alert-success">Thank you! Your review has been posted.</div>
                        <?php endif; ?>
                        <div class="rd-review-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="rd-review-line">
                                    <div class="rd-review-head">
                                        <div class="rd-review-user"><span class="rd-avatar"><?php echo htmlspecialchars(strtoupper(substr($review['user_name'], 0, 1))); ?></span><strong><?php echo htmlspecialchars($review['user_name']); ?></strong></div>
                                        <span><?php for ($i = 1; $i <= 5; $i++): ?><i class="fas fa-star" style="color: <?php echo $i <= intval($review['rating']) ? '#F5B041' : 'rgba(255,255,255,.22)'; ?>;"></i><?php endfor; ?></span>
                                    </div>
                                    <p class="rd-muted small mb-1"><?php echo htmlspecialchars($review['comment']); ?></p>
                                    <small class="rd-muted"><?php echo htmlspecialchars(date('M j, Y', strtotime($review['created_at']))); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="rd-panel">
                        <h2 class="rd-panel-title">Location</h2>
                        <div class="mb-3" style="min-height:170px;border-radius:8px;overflow:hidden;border:1px solid rgba(245,176,65,.22);background-image:url('<?php echo htmlspecialchars($locationImage, ENT_QUOTES); ?>');background-size:cover;background-position:center;"></div>
                        <div class="rd-map mb-3">
                            <div class="rd-map-card"><i class="fas fa-location-dot me-1"></i><strong><?php echo htmlspecialchars($street['name']); ?></strong><br><?php echo htmlspecialchars($street['location']); ?></div>
                        </div>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapQuery; ?>" class="rd-btn w-100" target="_blank" rel="noopener">Open in Google Maps</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>
    <footer>
        <div class="container text-center">
            <p class="mb-1">FoodFinder Karachi</p><small>Discover street food destinations across Karachi.</small>
        </div>
    </footer>
</body>

</html>
<?php $stmt->close();
$conn->close(); ?>
