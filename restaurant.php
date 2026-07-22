<?php
$page_title = "Restaurant Details - FoodFinder Karachi";
include 'config/database.php';
include 'includes/place-catalog.php';
$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: restaurants.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$restaurant = $result->fetch_assoc();
if (!$restaurant) {
    header('Location: restaurants.php');
    exit();
}

$favoriteAdded = false;
$favoriteRemoved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'])) {
    if ($_POST['favorite_action'] === 'add') {
        addFavoriteItem('restaurant', $restaurant['id'], [
            'name' => $restaurant['name'],
            'url' => 'restaurant.php?id=' . $restaurant['id'],
            'subtitle' => $restaurant['cuisine'] . ' - ' . $restaurant['location']
        ]);
        $favoriteAdded = true;
    }
    if ($_POST['favorite_action'] === 'remove') {
        removeFavoriteItem('restaurant', $restaurant['id']);
        $favoriteRemoved = true;
    }
}

$isFavorite = isFavoriteItem('restaurant', $restaurant['id']);
$heroImage = !empty($restaurant['image']) ? $restaurant['image'] : 'https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?w=1800';
$galleryImages = [
    $heroImage,
    'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=900',
    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=900',
    'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=900',
    'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=900'
];

$menuTabs = ['Best Sellers', 'BBQ', 'Biryani', 'Karahi', 'Fast Food', 'Chinese', 'Desserts', 'Drinks'];
$menuItems = [
    [
        'tag' => 'Best Seller',
        'name' => 'Special BBQ Platter',
        'description' => 'Chicken, beef, mutton, seekh kebab and smoky boti with chutneys.',
        'price' => 'Rs. 2,850',
        'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400'
    ],
    [
        'tag' => 'Chef Special',
        'name' => 'Chicken Handi',
        'description' => 'Boneless chicken cooked in a rich creamy handi gravy.',
        'price' => 'Rs. 1,650',
        'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400'
    ],
    [
        'tag' => 'Most Ordered',
        'name' => 'Matka Biryani',
        'description' => 'Aromatic matka biryani with raita and spicy gravy.',
        'price' => 'Rs. 1,250',
        'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=1400'
    ],
    [
        'tag' => 'Signature',
        'name' => 'Mutton Ribs',
        'description' => 'Tender ribs glazed with house BBQ sauce.',
        'price' => 'Rs. 2,350',
        'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1400'
    ],
    [
        'tag' => 'Fast Food',
        'name' => 'Zinger Burger Meal',
        'description' => 'Crispy burger, fries and dip served as a full meal.',
        'price' => 'Rs. 950',
        'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1400'
    ],
    [
        'tag' => 'Karahi',
        'name' => 'Chicken Karahi',
        'description' => 'Fresh karahi with tomato, ginger and green chili.',
        'price' => 'Rs. 1,850',
        'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400'
    ],
    [
        'tag' => 'Desserts',
        'name' => 'Molten Lava Cake',
        'description' => 'Warm chocolate cake with vanilla ice cream.',
        'price' => 'Rs. 720',
        'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=1400'
    ],
    [
        'tag' => 'Drinks',
        'name' => 'Mint Margarita',
        'description' => 'Refreshing mint and lemon cooler over crushed ice.',
        'price' => 'Rs. 420',
        'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=1400'
    ]
];

$placeCatalog = ff_get_restaurant_catalog($restaurant['name'], $restaurant['image'] ?? '');
$heroImage = $placeCatalog['hero_image'];
$locationImage = $placeCatalog['location_image'] ?? $heroImage;
$galleryImages = !empty($placeCatalog['gallery_images']) ? $placeCatalog['gallery_images'] : $galleryImages;
$menuItems = !empty($placeCatalog['menu_items']) ? $placeCatalog['menu_items'] : $menuItems;
$menuTabs = array_map(function ($item) {
    return $item['tag'];
}, $menuItems);
$restaurantSummary = $placeCatalog['summary'];

$reviews = [];
$reviewStmt = $conn->prepare("SELECT r.rating, r.comment, r.created_at, COALESCE(u.name, 'Guest') AS user_name FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.restaurant_id = ? ORDER BY r.created_at DESC LIMIT 3");
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
        ['rating' => 5, 'comment' => 'Best BBQ in Karachi. Ambience and food quality are outstanding.', 'created_at' => date('Y-m-d', strtotime('-2 days')), 'user_name' => 'Ahmad Raza'],
        ['rating' => 5, 'comment' => 'Loved the seating and service. A perfect family dinner spot.', 'created_at' => date('Y-m-d', strtotime('-1 week')), 'user_name' => 'Sana Farooq']
    ];
}

$similarRestaurants = [];
$similarStmt = $conn->prepare("SELECT * FROM restaurants WHERE id <> ? ORDER BY rating DESC LIMIT 4");
$similarStmt->bind_param('i', $id);
$similarStmt->execute();
$similarResult = $similarStmt->get_result();
while ($similar = $similarResult->fetch_assoc()) {
    $similarRestaurants[] = $similar;
}
$similarStmt->close();

$mapQuery = urlencode(trim($restaurant['name'] . ' ' . $restaurant['address'] . ' ' . $restaurant['location']));
function foodSlug($text)
{
    return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text)), '-');
}

function ff_restaurant_detail_image($name, $imageName = '', $fallback = '')
{
    $slug = foodSlug($name);
    $images = [
        'kolachi' => 'assets/images/restaurant 1.png',
        'bbq-tonight' => 'assets/images/restaurant 2.png',
        'al-bustan' => 'assets/images/restaurant 3.png',
        'saltanat' => 'assets/images/restaurant 4.png',
        'ginsoy' => 'assets/images/restaurant 5.png',
    ];

    $imageMap = [
        'kolachi.jpg' => 'kolachi',
        'bbq-tonight.jpg' => 'bbq-tonight',
        'bbq tonight.jpg' => 'bbq-tonight',
        'al-bustan.jpg' => 'al-bustan',
        'saltanat.jpg' => 'saltanat',
        'ginsoy.jpg' => 'ginsoy',
    ];

    $imageKey = $slug;
    $imageBase = strtolower(basename(str_replace('\\', '/', (string) $imageName)));
    if ($imageBase !== '' && isset($imageMap[$imageBase])) {
        $imageKey = $imageMap[$imageBase];
    }

    if (isset($images[$imageKey])) {
        return str_replace(' ', '%20', $images[$imageKey]);
    }

    return !empty($fallback) ? $fallback : str_replace(' ', '%20', 'assets/images/restaurant 1.png');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurant['name']) . ' - FoodFinder Karachi'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=20260712">
    <style>
        :root {
            --rd-bg: #080a0b;
            --rd-card: rgba(17, 18, 16, 0.92);
            --rd-card-soft: rgba(255, 255, 255, 0.055);
            --rd-border: rgba(245, 176, 65, 0.22);
            --rd-gold: #F5B041;
            --rd-text: #f6f2e9;
            --rd-muted: rgba(255, 255, 255, 0.68);
        }

        * {
            box-sizing: border-box;
        }

        body.restaurant-detail-page {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, rgba(245, 176, 65, 0.08), transparent 34rem), var(--rd-bg);
            color: var(--rd-text);
        }

        .rd-icon {
            color: var(--rd-gold);
            width: 20px;
            text-align: center;
        }

        .rd-muted {
            color: var(--rd-muted);
        }

        .rd-hero {
            position: relative;
            min-height: 520px;
            display: flex;
            align-items: end;
            background-size: cover;
            background-position: center;
            border-bottom: 1px solid var(--rd-border);
            overflow: hidden;
        }

        .rd-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(8, 10, 11, 0.98), rgba(8, 10, 11, 0.58), rgba(8, 10, 11, 0.84)), linear-gradient(180deg, rgba(8, 10, 11, 0.1), #080a0b 96%);
        }

        .rd-hero .container {
            position: relative;
            z-index: 2;
        }

        .rd-back,
        .rd-chip,
        .rd-status,
        .rd-soft-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            min-height: 34px;
            padding: 7px 12px;
            color: var(--rd-text);
            background: rgba(7, 8, 8, 0.72);
            border: 1px solid rgba(245, 176, 65, 0.35);
            border-radius: 8px;
            font-size: 0.86rem;
            font-weight: 800;
            text-decoration: none;
        }

        .rd-back,
        .rd-soft-link {
            color: var(--rd-gold);
        }

        .rd-back:hover,
        .rd-soft-link:hover {
            color: #111;
            background: var(--rd-gold);
        }

        .rd-status.open {
            color: #43e68b;
            border-color: rgba(67, 230, 139, 0.35);
        }

        .rd-title {
            max-width: 820px;
            color: #fff;
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 800;
            line-height: 1.02;
            margin: 0.85rem 0 0.65rem;
        }

        .rd-kicker,
        .rd-meta-row,
        .rd-action-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.85rem;
        }

        .rd-kicker {
            color: rgba(255, 255, 255, 0.84);
            font-size: 1.02rem;
            margin-bottom: 1rem;
        }

        .rd-action-row {
            margin-top: 1.35rem;
        }

        .rd-action-row form {
            margin: 0;
        }

        .rd-btn,
        .rd-outline-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 8px;
            padding: 10px 22px;
            font-weight: 800;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .rd-btn {
            color: #111;
            background: linear-gradient(95deg, #F5B041, #E67E22);
        }

        .rd-outline-btn {
            color: var(--rd-text);
            background: rgba(0, 0, 0, 0.35);
            border-color: rgba(245, 176, 65, 0.45);
        }

        .rd-outline-btn:hover,
        .rd-btn:hover {
            color: #111;
            background: var(--rd-gold);
        }

        .rd-thumbs {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .rd-thumb {
            min-height: 96px;
            border: 1px solid rgba(245, 176, 65, 0.36);
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4);
        }

        .rd-thumb.more {
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 900;
            background: linear-gradient(rgba(0, 0, 0, 0.62), rgba(0, 0, 0, 0.62)), url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500') center/cover;
        }

        .rd-main {
            padding: 1.25rem 0 3rem;
        }

        .rd-panel {
            background: var(--rd-card);
            border: 1px solid var(--rd-border);
            border-radius: 8px;
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.26);
            padding: 1.25rem;
        }

        .rd-panel+.rd-panel {
            margin-top: 1rem;
        }

        .rd-panel-title {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .rd-about-grid {
            display: grid;
            grid-template-columns: minmax(142px, 180px) 1fr;
            gap: 1.25rem;
            align-items: center;
        }

        .rd-logo-box {
            min-height: 132px;
            display: grid;
            place-items: center;
            text-align: center;
            border: 1px solid rgba(245, 176, 65, 0.28);
            border-radius: 8px;
            background: var(--rd-card-soft);
        }

        .rd-logo-box i {
            color: var(--rd-gold);
            font-size: 2.3rem;
            margin-bottom: 0.55rem;
        }

        .rd-logo-box strong {
            display: block;
            color: #fff;
            font-size: 1rem;
            line-height: 1.15;
        }

        .rd-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem 1rem;
            margin-top: 1rem;
        }

        .rd-amenities span {
            color: rgba(255, 255, 255, 0.77);
            font-size: 0.9rem;
        }

        .rd-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
            margin-bottom: 1rem;
        }

        .rd-tabs span,
        .rd-tabs a {
            border-radius: 8px;
            padding: 9px 17px;
            color: rgba(255, 255, 255, 0.78);
            background: rgba(255, 255, 255, 0.09);
            font-size: 0.86rem;
            font-weight: 800;
            text-decoration: none;
        }

        .rd-tabs .active {
            color: #111;
            background: var(--rd-gold);
        }

        .rd-menu-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .rd-menu-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            border: 1px solid rgba(245, 176, 65, 0.18);
            border-radius: 8px;
            background: var(--rd-card-soft);
        }

        .rd-menu-image {
            position: relative;
            min-height: 0;
            aspect-ratio: 16 / 10;
            background-size: cover;
            background-position: center;
        }

        .rd-menu-tag {
            position: absolute;
            top: 9px;
            left: 9px;
            color: #111;
            background: var(--rd-gold);
            border-radius: 6px;
            padding: 4px 7px;
            font-size: 0.66rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .rd-menu-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0.85rem;
        }

        .rd-menu-body h4 {
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .rd-menu-body p {
            flex: 1;
            min-height: 0;
            color: var(--rd-muted);
            font-size: 0.82rem;
            margin-bottom: 0.85rem;
        }

        .rd-price {
            color: var(--rd-gold);
            font-weight: 900;
        }

        .rd-heart {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--rd-gold);
            border: 1px solid rgba(245, 176, 65, 0.42);
            border-radius: 50%;
        }

        .rd-gallery {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .rd-gallery-tile {
            min-height: 98px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-size: cover;
            background-position: center;
        }

        .rd-similar-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .rd-similar-item {
            display: grid;
            grid-template-columns: 72px 1fr;
            align-items: stretch;
            gap: 0.75rem;
            color: inherit;
            text-decoration: none;
            border: 1px solid rgba(245, 176, 65, 0.16);
            border-radius: 8px;
            padding: 0.55rem;
            background: var(--rd-card-soft);
        }

        .rd-similar-image {
            width: 72px;
            min-height: 72px;
            height: 72px;
            border-radius: 7px;
            background-size: cover;
            background-position: center;
        }

        .rd-similar-item span:last-child {
            min-width: 0;
        }

        .rd-similar-item h4,
        .rd-similar-item small {
            word-break: break-word;
        }

        .rd-similar-item h4 {
            color: #fff;
            font-size: 0.92rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .rd-info-list,
        .rd-review-list {
            display: grid;
            gap: 1rem;
        }

        .rd-info-line {
            display: grid;
            grid-template-columns: 26px 1fr;
            gap: 0.65rem;
        }

        .rd-info-line strong {
            display: block;
            color: var(--rd-gold);
            font-size: 0.9rem;
        }

        .rd-info-line span {
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.92rem;
        }

        .rd-review-line {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
        }

        .rd-review-line:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .rd-review-head {
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 0.6rem;
        }

        .rd-review-user {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .rd-avatar {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            color: #111;
            background: linear-gradient(135deg, #f7d37c, #f5a941);
            border-radius: 50%;
            font-weight: 900;
        }

        .rd-map {
            min-height: 190px;
            position: relative;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: linear-gradient(35deg, rgba(111, 168, 220, 0.55), transparent 34%), linear-gradient(135deg, #dceee7 0%, #edf2e7 48%, #cbdff1 100%);
        }

        .rd-map::before,
        .rd-map::after {
            content: '';
            position: absolute;
            width: 140%;
            height: 4px;
            background: rgba(255, 255, 255, 0.86);
            transform: rotate(-18deg);
        }

        .rd-map::after {
            transform: rotate(28deg);
            background: rgba(245, 176, 65, 0.62);
        }

        .rd-map-card {
            position: relative;
            z-index: 2;
            max-width: 230px;
            color: #111;
            background: #fff;
            border-radius: 8px;
            padding: 0.75rem 0.9rem;
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.24);
            font-size: 0.82rem;
            text-align: left;
        }

        .rd-map-card i {
            color: #e4473f;
        }

        footer {
            padding: 34px 0;
            color: #aaa;
            background: #080a0b;
            border-top: 1px solid rgba(245, 176, 65, 0.15);
        }

        @media (max-width: 1199px) {

            .rd-menu-grid,
            .rd-similar-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.98px) {
            .rd-thumbs {
                margin-top: 1.25rem;
            }

            .rd-about-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .rd-hero {
                min-height: auto;
                padding: 3.5rem 0 2.5rem;
            }

            .rd-menu-grid,
            .rd-gallery,
            .rd-similar-grid {
                grid-template-columns: 1fr;
            }

            .rd-thumbs {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .rd-action-row .rd-btn,
            .rd-action-row .rd-outline-btn,
            .rd-action-row form {
                width: 100%;
            }
        }
    </style>
</head>

<body class="restaurant-detail-page">
    <?php include 'includes/nav.php'; ?>

    <div style="height:76px"></div>

    <section class="rd-hero" style="background-image: url('<?php echo htmlspecialchars($heroImage, ENT_QUOTES); ?>');">
        <div class="container py-5">
            <div class="row align-items-end g-4">
                <div class="col-lg-8">
                    <a href="restaurants.php" class="rd-back"><i class="fas fa-arrow-left"></i> Back to Restaurants</a>
                    <div class="mt-3">
                        <span class="rd-chip"><i class="fas fa-star" style="color: var(--rd-gold);"></i><?php echo number_format($restaurant['rating'], 1); ?> (1250+ Reviews)</span>
                    </div>
                    <h1 class="rd-title"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                    <div class="rd-kicker">
                        <span><?php echo htmlspecialchars($restaurant['cuisine']); ?></span>
                        <span>&bull;</span>
                        <span><i class="fas fa-map-marker-alt rd-icon"></i><?php echo htmlspecialchars($restaurant['location']); ?></span>
                    </div>
                    <div class="rd-meta-row">
                        <span class="rd-status open"><i class="fas fa-circle"></i> Open Now</span>
                        <span class="rd-chip"><i class="far fa-clock rd-icon"></i><?php echo htmlspecialchars($restaurant['opening_hours']); ?></span>
                        <span class="rd-chip"><i class="fas fa-wallet rd-icon"></i><?php echo htmlspecialchars($restaurant['price_range']); ?></span>
                    </div>
                    <div class="rd-action-row">
                        <a href="#menu" class="rd-btn"><i class="fas fa-utensils"></i> View Menu</a>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapQuery; ?>" class="rd-outline-btn" target="_blank" rel="noopener"><i class="fas fa-location-arrow"></i> Directions</a>
                        <form method="POST">
                            <button type="submit" name="favorite_action" value="<?php echo $isFavorite ? 'remove' : 'add'; ?>" class="rd-outline-btn">
                                <i class="<?php echo $isFavorite ? 'fas' : 'far'; ?> fa-heart"></i><?php echo $isFavorite ? 'Saved' : 'Save'; ?>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rd-thumbs">
                        <?php foreach (array_slice($galleryImages, 1, 3) as $thumb): ?>
                            <div class="rd-thumb" style="background-image: url('<?php echo htmlspecialchars($thumb, ENT_QUOTES); ?>');"></div>
                        <?php endforeach; ?>
                        <div class="rd-thumb more">+25</div>
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
                            <div>
                                <i class="fas fa-fire-flame-curved"></i>
                                <strong><?php echo htmlspecialchars($restaurant['name']); ?></strong>
                                <span class="rd-muted small">Restaurant</span>
                            </div>
                        </div>
                        <div>
                            <h2 class="rd-panel-title">About <?php echo htmlspecialchars($restaurant['name']); ?></h2>
                            <p class="rd-muted mb-0"><?php echo nl2br(htmlspecialchars($restaurantSummary)); ?></p>
                            <div class="rd-amenities">
                                <span><i class="fas fa-water rd-icon"></i>Sea View</span>
                                <span><i class="fas fa-users rd-icon"></i>Family Friendly</span>
                                <span><i class="fas fa-car rd-icon"></i>Valet Parking</span>
                                <span><i class="fas fa-chair rd-icon"></i>Outdoor Seating</span>
                                <span><i class="fas fa-wifi rd-icon"></i>WiFi Available</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rd-panel" id="menu">
                    <h2 class="rd-panel-title">Menu</h2>
                    <div class="rd-tabs">
                        <?php foreach ($menuTabs as $index => $tab): ?>
                            <a class="<?php echo $index === 0 ? 'active' : ''; ?>" href="food-detail.php?type=restaurant&id=<?php echo intval($restaurant['id']); ?>&item=<?php echo urlencode(foodSlug($tab)); ?>"><?php echo htmlspecialchars($tab); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <div class="rd-menu-grid">
                        <?php foreach ($menuItems as $item): ?>
                            <article class="rd-menu-card">
                                <div class="rd-menu-image" style="background-image: url('<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>');">
                                    <span class="rd-menu-tag"><?php echo htmlspecialchars($item['tag']); ?></span>
                                </div>
                                <div class="rd-menu-body">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="rd-price"><?php echo htmlspecialchars($item['price']); ?></span>
                                        <a class="rd-soft-link" href="food-detail.php?type=restaurant&id=<?php echo intval($restaurant['id']); ?>&item=<?php echo urlencode(foodSlug($item['name'])); ?>">Details</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="rd-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="rd-panel-title mb-0">Gallery</h2>
                        <span class="rd-soft-link">View All</span>
                    </div>
                    <div class="rd-gallery">
                        <?php foreach ($galleryImages as $image): ?>
                            <div class="rd-gallery-tile" style="background-image: url('<?php echo htmlspecialchars($image, ENT_QUOTES); ?>');"></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (!empty($similarRestaurants)): ?>
                    <div class="rd-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="rd-panel-title mb-0">Similar Restaurants</h2>
                            <a href="restaurants.php" class="rd-soft-link">View All</a>
                        </div>
                        <div class="rd-similar-grid">
                            <?php foreach ($similarRestaurants as $index => $similar): ?>
                                <?php $similarImage = ff_restaurant_detail_image($similar['name'], $similar['image'] ?? '', $galleryImages[$index % count($galleryImages)]); ?>
                                <a class="rd-similar-item" href="restaurant.php?id=<?php echo intval($similar['id']); ?>">
                                    <span class="rd-similar-image" style="background-image: url('<?php echo htmlspecialchars($similarImage, ENT_QUOTES); ?>');"></span>
                                    <span>
                                        <h4><?php echo htmlspecialchars($similar['name']); ?></h4>
                                        <span style="color: var(--rd-gold);"><i class="fas fa-star"></i> <?php echo number_format($similar['rating'], 1); ?></span>
                                        <small class="d-block rd-muted"><?php echo htmlspecialchars($similar['cuisine']); ?></small>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <aside class="col-lg-4">
                <div class="position-sticky" style="top:100px;">
                    <div class="rd-panel">
                        <?php if ($favoriteAdded): ?>
                            <div class="alert alert-success">Added to favorites.</div>
                        <?php elseif ($favoriteRemoved): ?>
                            <div class="alert alert-warning">Removed from favorites.</div>
                        <?php endif; ?>
                        <h2 class="rd-panel-title">Restaurant Information</h2>
                        <div class="rd-info-list">
                            <div class="rd-info-line">
                                <i class="fas fa-map-marker-alt rd-icon"></i>
                                <span><strong>Address</strong><?php echo htmlspecialchars($restaurant['address']); ?></span>
                            </div>
                            <div class="rd-info-line">
                                <i class="fas fa-phone rd-icon"></i>
                                <span><strong>Phone</strong><?php echo htmlspecialchars($restaurant['phone']); ?></span>
                            </div>
                            <div class="rd-info-line">
                                <i class="far fa-clock rd-icon"></i>
                                <span><strong>Opening Hours</strong><?php echo htmlspecialchars($restaurant['opening_hours']); ?></span>
                            </div>
                            <div class="rd-info-line">
                                <i class="fas fa-square-parking rd-icon"></i>
                                <span><strong>Parking</strong>Valet parking available</span>
                            </div>
                            <div class="rd-info-line">
                                <i class="fas fa-credit-card rd-icon"></i>
                                <span><strong>Payment Methods</strong>Cash, card, Easypaisa</span>
                            </div>
                        </div>
                    </div>

                    <div class="rd-panel">
                        <h2 class="rd-panel-title">Customer Reviews</h2>
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <span style="color: var(--rd-gold); font-weight: 800;"><i class="fas fa-star"></i> <?php echo number_format($restaurant['rating'], 1); ?> average</span>
                            <a href="review.php?type=restaurant&id=<?php echo intval($restaurant['id']); ?>" class="rd-soft-link">Write Review</a>
                        </div>
                        <?php if (!empty($reviewSubmitted)): ?>
                            <div class="alert alert-success">Thank you! Your review has been posted.</div>
                        <?php endif; ?>
                        <div class="rd-review-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="rd-review-line">
                                    <div class="rd-review-head">
                                        <div class="rd-review-user">
                                            <span class="rd-avatar"><?php echo htmlspecialchars(strtoupper(substr($review['user_name'], 0, 1))); ?></span>
                                            <strong><?php echo htmlspecialchars($review['user_name']); ?></strong>
                                        </div>
                                        <span>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star" style="color: <?php echo $i <= intval($review['rating']) ? 'var(--rd-gold)' : 'rgba(255,255,255,0.22)'; ?>;"></i>
                                            <?php endfor; ?>
                                        </span>
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
                            <div class="rd-map-card">
                                <i class="fas fa-location-dot me-1"></i>
                                <strong><?php echo htmlspecialchars($restaurant['name']); ?></strong><br>
                                <?php echo htmlspecialchars($restaurant['location']); ?>
                            </div>
                        </div>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapQuery; ?>" class="rd-btn w-100" target="_blank" rel="noopener">Open in Google Maps</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <footer>
        <div class="container text-center">
            <p class="mb-1">FoodFinder Karachi</p>
            <small>Discover the best dining spots across Karachi.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>
