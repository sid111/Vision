<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodfinder_karachi');

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create connection function
function getConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
    ensureDatabaseSchema($conn);
    return $conn;
}

// Base URL (update if your local setup differs)
define('BASE_URL', 'http://localhost/Vision/');

function ensureDatabaseSchema($conn)
{
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS restaurants (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        address VARCHAR(255),
        location VARCHAR(100),
        cuisine VARCHAR(100),
        price_range VARCHAR(50),
        rating DECIMAL(2,1) DEFAULT 0,
        opening_hours VARCHAR(255),
        phone VARCHAR(50),
        image VARCHAR(255),
        featured BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS cafes (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        address VARCHAR(255),
        location VARCHAR(100),
        coffee_types TEXT,
        rating DECIMAL(2,1) DEFAULT 0,
        opening_hours VARCHAR(255),
        phone VARCHAR(50),
        image VARCHAR(255),
        featured BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS food_streets (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        location VARCHAR(255),
        famous_for TEXT,
        image VARCHAR(255),
        rating DECIMAL(2,1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS reviews (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NULL,
        restaurant_id INT NULL,
        cafe_id INT NULL,
        food_street_id INT NULL,
        rating INT CHECK (rating >= 1 AND rating <= 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
        FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE,
        FOREIGN KEY (food_street_id) REFERENCES food_streets(id) ON DELETE CASCADE
    )");

    $fieldExists = $conn->query("SHOW COLUMNS FROM reviews LIKE 'food_street_id'");
    if (!$fieldExists || $fieldExists->num_rows === 0) {
        $conn->query("ALTER TABLE reviews ADD COLUMN food_street_id INT NULL");
    }

    $conn->query("CREATE TABLE IF NOT EXISTS favorites (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        item_type VARCHAR(50) NOT NULL,
        item_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        url VARCHAR(255) NOT NULL,
        subtitle VARCHAR(255) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user_item (user_id, item_type, item_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS locations (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS site_stats (
        id INT PRIMARY KEY AUTO_INCREMENT,
        total_restaurants INT DEFAULT 0,
        total_cafes INT DEFAULT 0,
        total_users INT DEFAULT 0,
        total_reviews INT DEFAULT 0,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    ensureDefaultAdmin($conn);
    ensureDefaultLocations($conn);
    refreshSiteStats($conn);
}

function ensureDefaultAdmin($conn)
{
    $email = 'admin@vision.com';
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $name = 'Default Admin';

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $insertStmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $insertStmt->bind_param('sss', $name, $email, $hashedPassword);
        $insertStmt->execute();
        $insertStmt->close();
    } else {
        $updateStmt = $conn->prepare("UPDATE users SET name = ?, password = ?, role = 'admin' WHERE email = ?");
        $updateStmt->bind_param('sss', $name, $hashedPassword, $email);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $stmt->close();
}

function ensureDefaultLocations($conn)
{
    $defaultLocations = ['Clifton', 'DHA', 'Gulshan', 'Do Darya', 'Bahadurabad', 'Zamzama'];
    foreach ($defaultLocations as $location) {
        $stmt = $conn->prepare("INSERT IGNORE INTO locations (name) VALUES (?)");
        $stmt->bind_param('s', $location);
        $stmt->execute();
        $stmt->close();
    }
}

function getLocations($conn)
{
    $locations = [];
    $query = "SELECT DISTINCT location FROM (
        SELECT location FROM restaurants
        UNION
        SELECT location FROM cafes
        UNION
        SELECT location FROM food_streets
        UNION
        SELECT name as location FROM food_streets
    ) AS all_locations
    WHERE location <> 'Karachi' AND location IS NOT NULL
    ORDER BY FIELD(LOWER(TRIM(REPLACE(REPLACE(LOWER(location), ' karachi', ''), ', karachi', ''))), 'burns road', 'do darya', 'boat basin', 'hussainabad', 'bahadurabad', 'zamzama', 'clifton', 'dha', 'gulshan'), location";

    $result = $conn->query($query);
    if ($result) {
        $seen = [];
        while ($row = $result->fetch_assoc()) {
            $location = trim($row['location']);
            // Remove " Karachi", " karachi", ", Karachi", ", karachi" suffixes for normalization
            $location_key = strtolower(preg_replace('/\s*,?\s*karachi\s*$/i', '', $location));

            if ($location !== '' && !in_array($location_key, $seen)) {
                $locations[] = $location;
                $seen[] = $location_key;
            }
        }
    }

    if (empty($locations)) {
        $locations = ['Burns Road', 'Do Darya', 'Boat Basin', 'Hussainabad', 'Bahadurabad', 'Zamzama'];
    }
    return $locations;
}

function refreshSiteStats($conn)
{
    $conn->query("INSERT INTO site_stats (total_restaurants, total_cafes, total_users, total_reviews)
        SELECT (SELECT COUNT(*) FROM restaurants), (SELECT COUNT(*) FROM cafes), (SELECT COUNT(*) FROM users), (SELECT COUNT(*) FROM reviews)
        WHERE NOT EXISTS (SELECT 1 FROM site_stats)");

    $conn->query("UPDATE site_stats SET total_restaurants = (SELECT COUNT(*) FROM restaurants),
        total_cafes = (SELECT COUNT(*) FROM cafes),
        total_users = (SELECT COUNT(*) FROM users),
        total_reviews = (SELECT COUNT(*) FROM reviews),
        updated_at = CURRENT_TIMESTAMP WHERE id = 1");
}

function getCurrentUser()
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    global $conn;
    if (!isset($conn)) {
        $conn = getConnection();
    }

    $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    return $user;
}

function isLoggedIn()
{
    return !empty($_SESSION['user_id']);
}

function isAdmin()
{
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

function requireLogin($redirect = 'login.php')
{
    if (!isLoggedIn()) {
        header('Location: ' . $redirect);
        exit();
    }
}

function requireAdmin($redirect = 'index.php')
{
    if (!isAdmin()) {
        header('Location: ' . $redirect);
        exit();
    }
}

// Function to get dynamic stats
function getSiteStats($conn)
{
    $result = $conn->query("SELECT total_restaurants, total_cafes, total_users, total_reviews FROM site_stats LIMIT 1");
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return ['total_restaurants' => 0, 'total_cafes' => 0, 'total_users' => 0, 'total_reviews' => 0];
}

// Function to get featured restaurants
function getFeaturedRestaurants($conn, $limit = 4)
{
    $result = $conn->query("SELECT * FROM restaurants WHERE featured = 1 ORDER BY rating DESC LIMIT $limit");
    $restaurants = [];
    while ($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
    return $restaurants;
}

// Function to get featured cafes
function getFeaturedCafes($conn, $limit = 4)
{
    $result = $conn->query("SELECT * FROM cafes WHERE featured = 1 ORDER BY rating DESC LIMIT $limit");
    $cafes = [];
    while ($row = $result->fetch_assoc()) {
        $cafes[] = $row;
    }
    return $cafes;
}

// Function to get all food streets
function getFoodStreets($conn)
{
    $result = $conn->query("SELECT * FROM food_streets ORDER BY rating DESC");
    $streets = [];
    while ($row = $result->fetch_assoc()) {
        $streets[] = $row;
    }
    return $streets;
}

// Function to get recent reviews
function getRecentReviews($conn, $limit = 6)
{
    $query = "SELECT r.*, u.name as user_name,
              CASE
                WHEN r.restaurant_id IS NOT NULL THEN (SELECT name FROM restaurants WHERE id = r.restaurant_id)
                WHEN r.cafe_id IS NOT NULL THEN (SELECT name FROM cafes WHERE id = r.cafe_id)
                WHEN r.food_street_id IS NOT NULL THEN (SELECT name FROM food_streets WHERE id = r.food_street_id)
                ELSE 'Guest Review'
              END as place_name
              FROM reviews r
              LEFT JOIN users u ON r.user_id = u.id
              ORDER BY r.created_at DESC LIMIT $limit";
    $result = $conn->query($query);
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    return $reviews;
}

function initializeFavorites()
{
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }
}

function getFavoriteItems()
{
    global $conn;
    if (isLoggedIn()) {
        $stmt = $conn->prepare("SELECT item_type as type, item_id as id, name, url, subtitle FROM favorites WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $favorites = [];
        while ($row = $result->fetch_assoc()) {
            $favorites[] = $row;
        }
        $stmt->close();
        return $favorites;
    }

    initializeFavorites();
    return array_values($_SESSION['favorites']);
}

function addFavoriteItem($type, $id, $data)
{
    global $conn;
    $type = strtolower($type);
    $id = intval($id);

    if (isLoggedIn()) {
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, item_type, item_id, name, url, subtitle)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name = VALUES(name), url = VALUES(url), subtitle = VALUES(subtitle)");
        $stmt->bind_param('isisss', $_SESSION['user_id'], $type, $id, $data['name'], $data['url'], $data['subtitle']);
        $stmt->execute();
        $stmt->close();
        return;
    }

    initializeFavorites();
    $_SESSION['favorites'][$type . ':' . $id] = array_merge(['type' => $type, 'id' => $id], $data);
}

function removeFavoriteItem($type, $id)
{
    global $conn;
    $type = strtolower($type);
    $id = intval($id);

    if (isLoggedIn()) {
        $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND item_type = ? AND item_id = ?");
        $stmt->bind_param('isi', $_SESSION['user_id'], $type, $id);
        $stmt->execute();
        $stmt->close();
        return;
    }

    initializeFavorites();
    unset($_SESSION['favorites'][$type . ':' . $id]);
}

function isFavoriteItem($type, $id)
{
    global $conn;
    $type = strtolower($type);
    $id = intval($id);

    if (isLoggedIn()) {
        $stmt = $conn->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND item_type = ? AND item_id = ?");
        $stmt->bind_param('isi', $_SESSION['user_id'], $type, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    initializeFavorites();
    return isset($_SESSION['favorites'][$type . ':' . $id]);
}
