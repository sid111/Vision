<?php
$page_title = "Search Results - FoodFinder Karachi";
include 'config/database.php';
$conn = getConnection();

$search = trim($_GET['search'] ?? '');
$location = trim($_GET['location'] ?? '');

$locations = getLocations($conn);
$restaurantResults = [];
$cafeResults = [];
$streetResults = [];
$groupedResults = [];

if (isset($_GET['submitted']) || $search || $location) {
    $restaurantSql = "SELECT * FROM restaurants WHERE 1=1";
    $restaurantParams = [];
    $restaurantTypes = '';

    if ($search) {
        $restaurantSql .= " AND (name LIKE ? OR description LIKE ? OR cuisine LIKE ? OR location LIKE ?)";
        $restaurantParams[] = "%$search%";
        $restaurantParams[] = "%$search%";
        $restaurantParams[] = "%$search%";
        $restaurantParams[] = "%$search%";
        $restaurantTypes .= 'ssss';
    }
    if ($location) {
        $restaurantSql .= " AND location LIKE ?";
        $restaurantParams[] = "%$location%";
        $restaurantTypes .= 's';
    }
    $restaurantSql .= " ORDER BY rating DESC LIMIT 12";
    $restaurantStmt = $conn->prepare($restaurantSql);
    if (!empty($restaurantParams)) {
        $restaurantStmt->bind_param($restaurantTypes, ...$restaurantParams);
    }
    $restaurantStmt->execute();
    $restaurantResult = $restaurantStmt->get_result();
    while ($row = $restaurantResult->fetch_assoc()) {
        $restaurantResults[] = $row;
    }
    $restaurantStmt->close();

    $cafeSql = "SELECT * FROM cafes WHERE 1=1";
    $cafeParams = [];
    $cafeTypes = '';
    if ($search) {
        $cafeSql .= " AND (name LIKE ? OR description LIKE ? OR coffee_types LIKE ? OR location LIKE ?)";
        $cafeParams[] = "%$search%";
        $cafeParams[] = "%$search%";
        $cafeParams[] = "%$search%";
        $cafeParams[] = "%$search%";
        $cafeTypes .= 'ssss';
    }
    if ($location) {
        $cafeSql .= " AND location LIKE ?";
        $cafeParams[] = "%$location%";
        $cafeTypes .= 's';
    }
    $cafeSql .= " ORDER BY rating DESC LIMIT 12";
    $cafeStmt = $conn->prepare($cafeSql);
    if (!empty($cafeParams)) {
        $cafeStmt->bind_param($cafeTypes, ...$cafeParams);
    }
    $cafeStmt->execute();
    $cafeResult = $cafeStmt->get_result();
    while ($row = $cafeResult->fetch_assoc()) {
        $cafeResults[] = $row;
    }
    $cafeStmt->close();

    $streetSql = "SELECT * FROM food_streets WHERE 1=1";
    $streetParams = [];
    $streetTypes = '';
    if ($search) {
        $streetSql .= " AND (name LIKE ? OR description LIKE ? OR famous_for LIKE ? OR location LIKE ?)";
        $streetParams[] = "%$search%";
        $streetParams[] = "%$search%";
        $streetParams[] = "%$search%";
        $streetParams[] = "%$search%";
        $streetTypes .= 'ssss';
    }
    if ($location) {
        $streetSql .= " AND (location LIKE ? OR name LIKE ?)";
        $streetParams[] = "%$location%";
        $streetParams[] = "%$location%";
        $streetTypes .= 'ss';
    }
    $streetSql .= " ORDER BY rating DESC LIMIT 12";
    $streetStmt = $conn->prepare($streetSql);
    if (!empty($streetParams)) {
        $streetStmt->bind_param($streetTypes, ...$streetParams);
    }
    $streetStmt->execute();
    $streetResult = $streetStmt->get_result();
    while ($row = $streetResult->fetch_assoc()) {
        $streetResults[] = $row;
    }
    $streetStmt->close();
} else {
    foreach ($locations as $loc) {
        $groupedResults[$loc] = ['restaurant' => null, 'cafe' => null, 'street' => null];
        $likeLoc = "%$loc%";

        $stmt = $conn->prepare("SELECT * FROM restaurants WHERE location LIKE ? ORDER BY rating DESC LIMIT 1");
        $stmt->bind_param('s', $likeLoc);
        $stmt->execute();
        $result = $stmt->get_result();
        $groupedResults[$loc]['restaurant'] = $result->fetch_assoc() ?: null;
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM cafes WHERE location LIKE ? ORDER BY rating DESC LIMIT 1");
        $stmt->bind_param('s', $likeLoc);
        $stmt->execute();
        $result = $stmt->get_result();
        $groupedResults[$loc]['cafe'] = $result->fetch_assoc() ?: null;
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM food_streets WHERE location LIKE ? OR name LIKE ? ORDER BY rating DESC LIMIT 1");
        $stmt->bind_param('ss', $likeLoc, $loc);
        $stmt->execute();
        $result = $stmt->get_result();
        $groupedResults[$loc]['street'] = $result->fetch_assoc() ?: null;
        $stmt->close();
    }
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
    <link rel="stylesheet" href="assets/style.css?v=20260722_v4">
</head>

<body>
    <?php include 'includes/nav.php'; ?>
    <div style="height:76px"></div>
    <section class="hero text-center">
        <div class="container">
            <h1 class="hero-title">Search Restaurants, Cafes & Food Streets</h1>
            <p class="hero-subtitle">Find the best dining spots in Karachi by name, location, cuisine or street.</p>
        </div>
    </section>
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="glass-card p-4 sidebar sticky-top" style="top:100px;">
                    <h4 class="mb-3">Filters</h4>
                    <form method="GET">
                        <input type="hidden" name="submitted" value="1">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search restaurants, cafes, or streets" value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <select name="location" class="form-select">
                                <option value="">All Locations</option>
                                <?php foreach ($locations as $loc): ?>
                                    <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo $location === $loc ? 'selected' : ''; ?>><?php echo htmlspecialchars($loc); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-gold w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="glass-card p-3 mb-4">
                    <h5 class="mb-0"><?php echo ($search || $location) ? (count($restaurantResults) + count($cafeResults) + count($streetResults)) . ' results' : 'Search across restaurants, cafes, and food streets.'; ?></h5>
                </div>

                <?php if ($search || $location): ?>
                    <?php if (empty($restaurantResults) && empty($cafeResults) && empty($streetResults)): ?>
                        <div class="glass-card p-4 text-center mb-4">
                            <p class="mb-0">No matches found. Try a broader search term or select another location.</p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($restaurantResults)): ?>
                        <h3 class="section-title text-white mb-3">Restaurants</h3>
                        <div class="row g-4 mb-5">
                            <?php foreach ($restaurantResults as $restaurant): ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="glass-card restaurant-card">
                                        <div class="restaurant-image" style="background-image: url('<?php echo htmlspecialchars(ff_restaurant_list_image($restaurant['name'], $restaurant['image'] ?? ''), ENT_QUOTES); ?>'); background-size: cover; background-position: center;"></div>
                                        <div class="p-3">
                                            <h5><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                                            <div class="rating mb-2"><i class="fas fa-star"></i> <?php echo number_format($restaurant['rating'], 1); ?></div>
                                            <p class="small text-secondary mb-2"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($restaurant['location']); ?></p>
                                            <p class="small text-secondary mb-2"><?php echo htmlspecialchars($restaurant['cuisine']); ?></p>
                                            <a href="restaurant.php?id=<?php echo intval($restaurant['id']); ?>" class="btn btn-outline-gold w-100">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($cafeResults)): ?>
                        <h3 class="section-title text-white mb-3">Cafes</h3>
                        <div class="row g-4 mb-5">
                            <?php foreach ($cafeResults as $cafe): ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="glass-card place-card">
                                        <div class="restaurant-image" style="background-image: url('<?php echo htmlspecialchars(ff_cafe_list_image($cafe['name'], $cafe['image'] ?? ''), ENT_QUOTES); ?>'); background-size: cover; background-position: center;"></div>
                                        <div class="p-3">
                                            <h5><?php echo htmlspecialchars($cafe['name']); ?></h5>
                                            <div class="rating mb-2"><i class="fas fa-star"></i> <?php echo number_format($cafe['rating'], 1); ?></div>
                                            <p class="small text-secondary mb-2"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($cafe['location']); ?></p>
                                            <p class="small text-secondary mb-2"><?php echo htmlspecialchars($cafe['coffee_types']); ?></p>
                                            <a href="cafe.php?id=<?php echo intval($cafe['id']); ?>" class="btn btn-outline-gold w-100">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($streetResults)): ?>
                        <h3 class="section-title text-white mb-3">Food Streets</h3>
                        <div class="row g-4 mb-5">
                            <?php foreach ($streetResults as $street): ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="glass-card food-street-card">
                                        <div class="restaurant-image" style="background-image: url('<?php echo htmlspecialchars(ff_street_list_image($street['name'], $street['image'] ?? ''), ENT_QUOTES); ?>'); background-size: cover; background-position: center;"></div>
                                        <div class="p-3">
                                            <h5><?php echo htmlspecialchars($street['name']); ?></h5>
                                            <div class="rating mb-2"><i class="fas fa-star"></i> <?php echo number_format($street['rating'], 1); ?></div>
                                            <p class="small text-secondary mb-2"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($street['location']); ?></p>
                                            <p class="small text-secondary mb-2"><?php echo htmlspecialchars($street['famous_for']); ?></p>
                                            <a href="food-street.php?id=<?php echo intval($street['id']); ?>" class="btn btn-outline-gold w-100">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="glass-card p-4 text-center">
                        <h5 class="mb-3">Start your search</h5>
                        <p class="mb-0">Use the filters on the left to find restaurants, cafes, or food streets.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="container text-center py-4">
            <p class="mb-1">FoodFinder Karachi</p>
            <small>Search across all dining categories in Karachi.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>