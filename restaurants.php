<?php
$page_title = "Restaurants - FoodFinder Karachi";
include 'config/database.php';
$conn = getConnection();

$favoriteAdded = false;
$favoriteRemoved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'], $_POST['restaurant_id'])) {
    $restaurantId = intval($_POST['restaurant_id']);

    if ($_POST['favorite_action'] === 'add') {
        $itemStmt = $conn->prepare("SELECT * FROM restaurants WHERE id = ?");
        $itemStmt->bind_param('i', $restaurantId);
        $itemStmt->execute();
        $itemResult = $itemStmt->get_result();
        $itemRow = $itemResult->fetch_assoc();
        if ($itemRow) {
            addFavoriteItem('restaurant', $itemRow['id'], [
                'name' => $itemRow['name'],
                'url' => 'restaurant.php?id=' . $itemRow['id'],
                'subtitle' => $itemRow['cuisine'] . ' Â· ' . $itemRow['location']
            ]);
        }
        $itemStmt->close();
        $favoriteAdded = true;
    }

    if ($_POST['favorite_action'] === 'remove') {
        removeFavoriteItem('restaurant', $restaurantId);
        $favoriteRemoved = true;
    }

    $redirectUrl = 'restaurants.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    header('Location: ' . $redirectUrl);
    exit();
}

$search = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$cuisine = $_GET['cuisine'] ?? '';

$sql = "SELECT * FROM restaurants WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}

if (!empty($location)) {
    $sql .= " AND location = ?";
    $params[] = $location;
    $types .= "s";
}

if (!empty($cuisine)) {
    $sql .= " AND cuisine LIKE ?";
    $params[] = "%$cuisine%";
    $types .= "s";
}

$sql .= " ORDER BY rating DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$locations = [];
$locResult = $conn->query("SELECT DISTINCT location FROM restaurants ORDER BY location");
while ($row = $locResult->fetch_assoc()) {
    $locations[] = $row['location'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=20260712">
</head>

<body>

    <?php include 'includes/nav.php'; ?>

    <div style="height:76px"></div>

    <section class="hero text-center">
        <div class="container">
            <h1 class="hero-title">Discover Karachi's Best Restaurants</h1>
            <p class="lead">Explore top rated dining destinations across the city.</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-4">

            <div class="col-lg-3">
                <div class="glass-card p-4 sidebar sticky-top" style="top:100px;">
                    <h4 class="mb-3">Filters</h4>

                    <form method="GET">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <select name="location" class="form-select">
                                <option value="">All Locations</option>
                                <?php foreach ($locations as $loc): ?>
                                    <option value="<?php echo $loc; ?>" <?php echo $location == $loc ? 'selected' : ''; ?>>
                                        <?php echo $loc; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cuisine</label>
                            <input type="text" name="cuisine" class="form-control" value="<?php echo htmlspecialchars($cuisine); ?>">
                        </div>

                        <button class="btn btn-gold w-100">Apply Filters</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">

                <div class="glass-card p-3 mb-4">
                    <h5 class="mb-0">
                        <?php echo $result->num_rows; ?> Restaurant(s) Found
                    </h5>
                </div>

                <div class="row g-4">

                    <?php while ($restaurant = $result->fetch_assoc()): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="glass-card restaurant-card">

                                <div class="restaurant-image" style="background-image: url('<?php echo !empty($restaurant['image']) ? htmlspecialchars($restaurant['image'], ENT_QUOTES) : 'https://images.unsplash.com/photo-1529205274511-6f7d5a6d8f30?w=1200'; ?>'); background-size: cover; background-position: center;"></div>

                                <div class="p-3">
                                    <h5><?php echo htmlspecialchars($restaurant['name']); ?></h5>

                                    <div class="rating mb-2">
                                        <i class="fas fa-star"></i>
                                        <?php echo number_format($restaurant['rating'], 1); ?>
                                    </div>

                                    <p class="small text-secondary mb-2">
                                        <?php echo htmlspecialchars($restaurant['cuisine']); ?>
                                    </p>

                                    <p class="small mb-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($restaurant['location']); ?>
                                    </p>

                                    <p class="small mb-3">
                                        <?php echo htmlspecialchars($restaurant['price_range']); ?>
                                    </p>

                                    <form method="POST" class="mb-3">
                                        <input type="hidden" name="restaurant_id" value="<?php echo intval($restaurant['id']); ?>">
        kolachi       <?php if (isFavoriteItem('restaurant', $restaurant['id'])): ?>
                                            <button type="submit" name="favorite_action" value="remove" class="btn btn-outline-gold w-100 mb-2">Remove Favorite</button>
                                        <?php else: ?>
                                            <button type="submit" name="favorite_action" value="add" class="btn btn-gold w-100 mb-2">Add Favorite</button>
                                        <?php endif; ?>
                                    </form>

                                    <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>"
                                        class="btn btn-outline-gold w-100">
                                        View Details
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container text-center">
            <p class="mb-1">FoodFinder Karachi</p>
            <small>Discover the finest restaurants and cafes across the city.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>
