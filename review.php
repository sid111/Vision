<?php
$page_title = 'Write Review - FoodFinder Karachi';
include 'config/database.php';
$conn = getConnection();

$type = $_GET['type'] ?? ($_POST['type'] ?? '');
$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

$items = [
    'restaurant' => ['table' => 'restaurants', 'column' => 'restaurant_id', 'detail' => 'restaurant.php?id=', 'label' => 'Restaurant'],
    'cafe' => ['table' => 'cafes', 'column' => 'cafe_id', 'detail' => 'cafe.php?id=', 'label' => 'Cafe'],
    'food_street' => ['table' => 'food_streets', 'column' => 'food_street_id', 'detail' => 'food-street.php?id=', 'label' => 'Food Street'],
];

if (!isset($items[$type]) || $id <= 0) {
    header('Location: index.php');
    exit();
}

$itemConfig = $items[$type];
$stmt = $conn->prepare("SELECT * FROM {$itemConfig['table']} WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();

if (!$item) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $errors[] = 'Please select a rating between 1 and 5 stars.';
    }
    if ($comment === '') {
        $errors[] = 'Please enter your review comment.';
    }

    if (empty($errors)) {
        $userId = isLoggedIn() ? $_SESSION['user_id'] : null;
        $restaurantId = null;
        $cafeId = null;
        $foodStreetId = null;

        if ($type === 'restaurant') {
            $restaurantId = $id;
        } elseif ($type === 'cafe') {
            $cafeId = $id;
        } elseif ($type === 'food_street') {
            $foodStreetId = $id;
        }

        $insertStmt = $conn->prepare("INSERT INTO reviews (user_id, restaurant_id, cafe_id, food_street_id, rating, comment) VALUES (?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param('iiiiis', $userId, $restaurantId, $cafeId, $foodStreetId, $rating, $comment);
        $insertStmt->execute();
        $insertStmt->close();

        $success = true;
        header('Location: ' . $itemConfig['detail'] . intval($id) . '?review_submitted=1');
        exit();
    }
}

$pageLabel = $itemConfig['label'];
$itemName = $item['name'] ?? $item['location'] ?? $itemConfig['label'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
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
            <h1 class="hero-title">Write a Review for <?php echo htmlspecialchars($itemName); ?></h1>
            <p class="hero-subtitle">Share your experience and help other food lovers discover the best spots in Karachi.</p>
        </div>
    </section>
    <div class="container my-5">
        <div class="glass-card p-4 mx-auto" style="max-width: 720px;">
            <div class="mb-4">
                <h2 class="section-title mb-1"><?php echo htmlspecialchars($pageLabel); ?> Review</h2>
                <p class="section-subtitle mb-0"><?php echo htmlspecialchars($itemName); ?></p>
            </div>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <input type="hidden" name="id" value="<?php echo intval($id); ?>">
                <div class="mb-4">
                    <label class="form-label">Your Rating</label>
                    <select name="rating" class="form-select">
                        <option value="0">Select rating</option>
                        <?php for ($star = 5; $star >= 1; $star--): ?>
                            <option value="<?php echo $star; ?>" <?php echo (isset($_POST['rating']) && intval($_POST['rating']) === $star) ? 'selected' : ''; ?>>
                                <?php echo str_repeat('★', $star); ?> (<?php echo $star; ?>)
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Your Review</label>
                    <textarea name="comment" class="form-control" rows="6"><?php echo htmlspecialchars($_POST['comment'] ?? ''); ?></textarea>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-gold">Submit Review</button>
                    <a href="<?php echo htmlspecialchars($itemConfig['detail'] . intval($id)); ?>" class="btn btn-outline-gold">Back to details</a>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <div class="container text-center py-4">
            <p class="mb-1">FoodFinder Karachi</p>
            <small>Thanks for helping others choose the best experience.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>