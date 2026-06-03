<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodfinder_karachi');

// Create connection function
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL (update this to your local path)
define('BASE_URL', 'http://localhost/foodfinder/');

// Function to get dynamic stats
function getSiteStats($conn) {
    $result = $conn->query("SELECT total_restaurants, total_cafes, total_users, total_reviews FROM site_stats LIMIT 1");
    if($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return ['total_restaurants' => 0, 'total_cafes' => 0, 'total_users' => 0, 'total_reviews' => 0];
}

// Function to get featured restaurants
function getFeaturedRestaurants($conn, $limit = 4) {
    $result = $conn->query("SELECT * FROM restaurants WHERE featured = 1 ORDER BY rating DESC LIMIT $limit");
    $restaurants = [];
    while($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
    return $restaurants;
}

// Function to get featured cafes
function getFeaturedCafes($conn, $limit = 4) {
    $result = $conn->query("SELECT * FROM cafes WHERE featured = 1 ORDER BY rating DESC LIMIT $limit");
    $cafes = [];
    while($row = $result->fetch_assoc()) {
        $cafes[] = $row;
    }
    return $cafes;
}

// Function to get all food streets
function getFoodStreets($conn) {
    $result = $conn->query("SELECT * FROM food_streets ORDER BY rating DESC");
    $streets = [];
    while($row = $result->fetch_assoc()) {
        $streets[] = $row;
    }
    return $streets;
}

// Function to get recent reviews
function getRecentReviews($conn, $limit = 6) {
    $query = "SELECT r.*, u.name as user_name, 
              CASE 
                WHEN r.restaurant_id IS NOT NULL THEN (SELECT name FROM restaurants WHERE id = r.restaurant_id)
                ELSE (SELECT name FROM cafes WHERE id = r.cafe_id)
              END as place_name
              FROM reviews r 
              JOIN users u ON r.user_id = u.id 
              ORDER BY r.created_at DESC LIMIT $limit";
    $result = $conn->query($query);
    $reviews = [];
    while($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    return $reviews;
}

function initializeFavorites() {
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }
}

function getFavoriteItems() {
    initializeFavorites();
    return $_SESSION['favorites'];
}

function addFavoriteItem($type, $id, $data) {
    initializeFavorites();
    $_SESSION['favorites'][$type . ':' . $id] = array_merge(['type' => $type, 'id' => $id], $data);
}

function removeFavoriteItem($type, $id) {
    initializeFavorites();
    unset($_SESSION['favorites'][$type . ':' . $id]);
}

function isFavoriteItem($type, $id) {
    initializeFavorites();
    return isset($_SESSION['favorites'][$type . ':' . $id]);
}
?>