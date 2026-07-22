<?php
$page_title = "Cafe Details - FoodFinder Karachi";
include 'config/database.php';
include 'includes/place-catalog.php';
$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: cafes.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM cafes WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$cafe = $result->fetch_assoc();
if (!$cafe) {
    header('Location: cafes.php');
    exit();
}

$favoriteAdded = false;
$favoriteRemoved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'])) {
    if ($_POST['favorite_action'] === 'add') {
        addFavoriteItem('cafe', $cafe['id'], [
            'name' => $cafe['name'],
            'url' => 'cafe.php?id=' . $cafe['id'],
            'subtitle' => $cafe['coffee_types'] . ' - ' . $cafe['location']
        ]);
        $favoriteAdded = true;
    }
    if ($_POST['favorite_action'] === 'remove') {
        removeFavoriteItem('cafe', $cafe['id']);
        $favoriteRemoved = true;
    }
}

$isFavorite = isFavoriteItem('cafe', $cafe['id']);
$heroImage = !empty($cafe['image']) ? $cafe['image'] : 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1800';
$galleryImages = [
    $heroImage,
    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
    'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900',
    'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900',
    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900'
];
$menuItems = [
    [
        'slug' => 'signature-coffee',
        'tag' => 'Signature',
        'name' => 'Signature Cappuccino',
        'description' => 'Rich espresso with silky foam and caramel notes.',
        'price' => 'Rs. 650',
        'rating' => 4.8,
        'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'
    ],
    [
        'slug' => 'latte',
        'tag' => 'Coffee',
        'name' => 'Vanilla Latte',
        'description' => 'Creamy latte with vanilla syrup and smooth milk.',
        'price' => 'Rs. 680',
        'rating' => 4.7,
        'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=1400'
    ],
    [
        'slug' => 'cold-brew',
        'tag' => 'Cold',
        'name' => 'Cold Brew',
        'description' => 'Slow brewed coffee served chilled and smooth.',
        'price' => 'Rs. 720',
        'rating' => 4.7,
        'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400'
    ],
    [
        'slug' => 'mocha',
        'tag' => 'Coffee',
        'name' => 'Mocha Frappe',
        'description' => 'Coffee, cocoa and milk blended into a chilled frappe.',
        'price' => 'Rs. 760',
        'rating' => 4.6,
        'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=1400'
    ],
    [
        'slug' => 'croissant',
        'tag' => 'Bakery',
        'name' => 'Butter Croissant',
        'description' => 'Flaky, buttery croissant baked fresh every morning.',
        'price' => 'Rs. 390',
        'rating' => 4.5,
        'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'
    ],
    [
        'slug' => 'breakfast',
        'tag' => 'Breakfast',
        'name' => 'English Breakfast',
        'description' => 'Eggs, toast, sausages, beans and hash browns.',
        'price' => 'Rs. 1,450',
        'rating' => 4.6,
        'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400'
    ],
    [
        'slug' => 'desserts',
        'tag' => 'Dessert',
        'name' => 'Lotus Cheesecake',
        'description' => 'Creamy cheesecake with lotus crumb and caramel.',
        'price' => 'Rs. 820',
        'rating' => 4.8,
        'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400'
    ],
    [
        'slug' => 'sandwich',
        'tag' => 'Snack',
        'name' => 'Club Sandwich',
        'description' => 'Toasted sandwich packed with chicken, cheese and fries.',
        'price' => 'Rs. 980',
        'rating' => 4.5,
        'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400'
    ]
];

$cafeCatalog = ff_get_cafe_catalog($cafe['name'], $cafe['image'] ?? '');
$heroImage = $cafeCatalog['hero_image'];
$locationImage = $cafeCatalog['location_image'] ?? $heroImage;
$galleryImages = !empty($cafeCatalog['gallery_images']) ? $cafeCatalog['gallery_images'] : $galleryImages;
$menuItems = !empty($cafeCatalog['menu_items']) ? $cafeCatalog['menu_items'] : $menuItems;
$menuTabs = array_map(function ($item) {
    return $item['tag'];
}, $menuItems);
$cafeSummary = $cafeCatalog['summary'];

$reviews = [];
$reviewStmt = $conn->prepare("SELECT r.rating, r.comment, r.created_at, COALESCE(u.name, 'Guest') AS user_name FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.cafe_id = ? ORDER BY r.created_at DESC LIMIT 3");
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
        ['rating' => 5, 'comment' => 'Cozy space, excellent coffee and desserts worth coming back for.', 'created_at' => date('Y-m-d', strtotime('-3 days')), 'user_name' => 'Maha Khan'],
        ['rating' => 5, 'comment' => 'Perfect place for work, friends and late evening coffee.', 'created_at' => date('Y-m-d', strtotime('-1 week')), 'user_name' => 'Hassan Ali']
    ];
}

$similarCafes = [];
$similarStmt = $conn->prepare("SELECT * FROM cafes WHERE id <> ? ORDER BY rating DESC LIMIT 4");
$similarStmt->bind_param('i', $id);
$similarStmt->execute();
$similarResult = $similarStmt->get_result();
while ($similar = $similarResult->fetch_assoc()) {
    $similarCafes[] = $similar;
}
$similarStmt->close();
$mapQuery = urlencode(trim($cafe['name'] . ' ' . $cafe['address'] . ' ' . $cafe['location']));

function ff_cafe_detail_image($name, $imageName = '', $fallback = '')
{
    $slug = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $name)), '-');
    $images = [
        'coffee-wagon' => 'assets/images/cafe 3.png',
        'espresso' => 'assets/images/cafe 2.png',
        'cafe-aylanto' => 'assets/images/cafe 5.png',
        'ginoxy' => 'assets/images/cafe 8.png',
        'evergreen-cafe' => 'assets/images/cafe 6.png',
        'big-tree-house-cafe' => 'assets/images/cafe 4.png',
        'cafe-flo' => 'assets/images/cafe 7.png',
        'cote-rotie' => 'assets/images/cafe 1.png',
    ];

    $imageMap = [
        'coffee-wagon.jpg' => 'coffee-wagon',
        'espresso.jpg' => 'espresso',
        'cafe-aylanto.jpg' => 'cafe-aylanto',
        'ginoxy-cafe.jpg' => 'ginoxy',
        'evergreen.jpg' => 'evergreen-cafe',
        'big-tree.jpg' => 'big-tree-house-cafe',
        'cafe-flo.jpg' => 'cafe-flo',
        'cote-rotie.jpg' => 'cote-rotie',
    ];

    $imageKey = $slug;
    $imageBase = strtolower(basename(str_replace('\\', '/', (string) $imageName)));
    if ($imageBase !== '' && isset($imageMap[$imageBase])) {
        $imageKey = $imageMap[$imageBase];
    }

    if (isset($images[$imageKey])) {
        return str_replace(' ', '%20', $images[$imageKey]);
    }

    return !empty($fallback) ? $fallback : str_replace(' ', '%20', 'assets/images/cafe 1.png');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cafe['name']) . ' - FoodFinder Karachi'; ?></title>
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
                    <a href="cafes.php" class="rd-back"><i class="fas fa-arrow-left"></i> Back to Cafes</a>
                    <div class="mt-3"><span class="rd-chip"><i class="fas fa-star" style="color:#F5B041"></i><?php echo number_format($cafe['rating'], 1); ?> (850+ Reviews)</span></div>
                    <h1 class="rd-title"><?php echo htmlspecialchars($cafe['name']); ?></h1>
                    <div class="rd-kicker"><span><?php echo htmlspecialchars($cafe['coffee_types']); ?></span><span>&bull;</span><span><i class="fas fa-map-marker-alt rd-icon"></i><?php echo htmlspecialchars($cafe['location']); ?></span></div>
                    <div class="rd-meta-row"><span class="rd-status open"><i class="fas fa-circle"></i> Open Now</span><span class="rd-chip"><i class="far fa-clock rd-icon"></i><?php echo htmlspecialchars($cafe['opening_hours']); ?></span><span class="rd-chip"><i class="fas fa-mug-hot rd-icon"></i>Specialty Coffee</span></div>
                    <div class="rd-action-row">
                        <a href="#menu" class="rd-btn"><i class="fas fa-mug-hot"></i> View Menu</a>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapQuery; ?>" class="rd-outline-btn" target="_blank" rel="noopener"><i class="fas fa-location-arrow"></i> Directions</a>
                        <form method="POST"><button type="submit" name="favorite_action" value="<?php echo $isFavorite ? 'remove' : 'add'; ?>" class="rd-outline-btn"><i class="<?php echo $isFavorite ? 'fas' : 'far'; ?> fa-heart"></i><?php echo $isFavorite ? 'Saved' : 'Save'; ?></button></form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rd-thumbs"><?php foreach (array_slice($galleryImages, 1, 4) as $thumb): ?><div class="rd-thumb" style="background-image: url('<?php echo htmlspecialchars($thumb, ENT_QUOTES); ?>');"></div><?php endforeach; ?></div>
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
                            <div><i class="fas fa-mug-hot"></i><strong><?php echo htmlspecialchars($cafe['name']); ?></strong><span class="rd-muted small">Cafe</span></div>
                        </div>
                        <div>
                            <h2 class="rd-panel-title">About <?php echo htmlspecialchars($cafe['name']); ?></h2>
                            <p class="rd-muted mb-0"><?php echo nl2br(htmlspecialchars($cafeSummary)); ?></p>
                            <div class="rd-amenities"><span><i class="fas fa-wifi rd-icon"></i>WiFi</span><span><i class="fas fa-laptop rd-icon"></i>Work Friendly</span><span><i class="fas fa-cake-candles rd-icon"></i>Desserts</span><span><i class="fas fa-chair rd-icon"></i>Cozy Seating</span></div>
                        </div>
                    </div>
                </div>
                <div class="rd-panel" id="menu">
                    <h2 class="rd-panel-title">Cafe Menu</h2>
                    <div class="rd-tabs"><?php foreach ($menuItems as $index => $item): ?><a class="<?php echo $index === 0 ? 'active' : ''; ?>" href="food-detail.php?type=cafe&id=<?php echo intval($cafe['id']); ?>&item=<?php echo urlencode($item['slug']); ?>"><?php echo htmlspecialchars($item['tag']); ?></a><?php endforeach; ?></div>
                    <div class="rd-menu-grid"><?php foreach ($menuItems as $item): ?><article class="rd-menu-card">
                                <div class="rd-menu-image" style="background-image: url('<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>');"><span class="rd-menu-tag"><?php echo htmlspecialchars($item['tag']); ?></span></div>
                                <div class="rd-menu-body">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                    <div class="d-flex align-items-center justify-content-between"><span class="rd-price"><?php echo htmlspecialchars($item['price']); ?></span><a class="rd-soft-link" href="food-detail.php?type=cafe&id=<?php echo intval($cafe['id']); ?>&item=<?php echo urlencode($item['slug']); ?>">Details</a></div>
                                </div>
                            </article><?php endforeach; ?></div>
                </div>
                <div class="rd-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="rd-panel-title mb-0">Gallery</h2><span class="rd-soft-link">View All</span>
                    </div>
                    <div class="rd-gallery"><?php foreach ($galleryImages as $image): ?><div class="rd-gallery-tile" style="background-image: url('<?php echo htmlspecialchars($image, ENT_QUOTES); ?>');"></div><?php endforeach; ?></div>
                </div>
                <?php if (!empty($similarCafes)): ?><div class="rd-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="rd-panel-title mb-0">Similar Cafes</h2><a href="cafes.php" class="rd-soft-link">View All</a>
                        </div>
                                <div class="rd-similar-grid"><?php foreach ($similarCafes as $index => $similar): ?><?php $similarImage = ff_cafe_detail_image($similar['name'], $similar['image'] ?? '', $galleryImages[$index % count($galleryImages)]); ?><a class="rd-similar-item" href="cafe.php?id=<?php echo intval($similar['id']); ?>"><span class="rd-similar-image" style="background-image: url('<?php echo htmlspecialchars($similarImage, ENT_QUOTES); ?>');"></span><span>
                                    <h4><?php echo htmlspecialchars($similar['name']); ?></h4><span style="color:#F5B041"><i class="fas fa-star"></i> <?php echo number_format($similar['rating'], 1); ?></span><small class="d-block rd-muted"><?php echo htmlspecialchars($similar['coffee_types']); ?></small>
                                </span></a><?php endforeach; ?></div>
                    </div><?php endif; ?>
            </section>
            <aside class="col-lg-4">
                <div class="position-sticky" style="top:100px;">
                    <div class="rd-panel"><?php if ($favoriteAdded): ?><div class="alert alert-success">Added to favorites.</div><?php elseif ($favoriteRemoved): ?><div class="alert alert-warning">Removed from favorites.</div><?php endif; ?><h2 class="rd-panel-title">Cafe Information</h2>
                        <div class="rd-info-list">
                            <div class="rd-info-line"><i class="fas fa-map-marker-alt rd-icon"></i><span><strong>Address</strong><?php echo htmlspecialchars($cafe['address']); ?></span></div>
                            <div class="rd-info-line"><i class="fas fa-phone rd-icon"></i><span><strong>Phone</strong><?php echo htmlspecialchars($cafe['phone']); ?></span></div>
                            <div class="rd-info-line"><i class="far fa-clock rd-icon"></i><span><strong>Opening Hours</strong><?php echo htmlspecialchars($cafe['opening_hours']); ?></span></div>
                            <div class="rd-info-line"><i class="fas fa-mug-hot rd-icon"></i><span><strong>Specialty</strong><?php echo htmlspecialchars($cafe['coffee_types']); ?></span></div>
                        </div>
                    </div>
                    <div class="rd-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <h2 class="rd-panel-title mb-0">Customer Reviews</h2>
                            <a href="review.php?type=cafe&id=<?php echo intval($cafe['id']); ?>" class="rd-soft-link">Write Review</a>
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
                            <div class="rd-map-card"><i class="fas fa-location-dot me-1"></i><strong><?php echo htmlspecialchars($cafe['name']); ?></strong><br><?php echo htmlspecialchars($cafe['location']); ?></div>
                        </div><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapQuery; ?>" class="rd-btn w-100" target="_blank" rel="noopener">Open in Google Maps</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>
    <footer>
        <div class="container text-center">
            <p class="mb-1">FoodFinder Karachi</p><small>Find your perfect cafe in Karachi with ease.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $stmt->close();
$conn->close(); ?>
