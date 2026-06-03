-- Create Database
CREATE DATABASE IF NOT EXISTS foodfinder_karachi;
USE foodfinder_karachi;

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Restaurants Table
CREATE TABLE restaurants (
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
);

-- Cafes Table
CREATE TABLE cafes (
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
);

-- Food Streets Table
CREATE TABLE food_streets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    famous_for TEXT,
    image VARCHAR(255),
    rating DECIMAL(2,1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reviews Table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    restaurant_id INT NULL,
    cafe_id INT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
);

-- Statistics Table (for dashboard)
CREATE TABLE site_stats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    total_restaurants INT DEFAULT 0,
    total_cafes INT DEFAULT 0,
    total_users INT DEFAULT 0,
    total_reviews INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========== SAMPLE DATA ==========

-- Insert Sample Users
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@foodfinder.com', '$2y$10$YourHashedPasswordHere', 'admin'),
('Ali Raza', 'ali@example.com', '$2y$10$YourHashedPasswordHere', 'user'),
('Sana Khan', 'sana@example.com', '$2y$10$YourHashedPasswordHere', 'user'),
('Ayisha Khan', 'ayisha@example.com', '$2y$10$YourHashedPasswordHere', 'user');

-- Insert Restaurants
INSERT INTO restaurants (name, description, address, location, cuisine, price_range, rating, opening_hours, phone, image, featured) VALUES
('Kolahi', 'Authentic Pakistani cuisine with stunning sea view at Do Darya. Famous for their BBQ platters and traditional Karahi.', 'Do Darya, Karachi', 'Do Darya', 'Pakistani, BBQ', '$$$', 4.7, '12:00 PM - 12:00 AM', '+92 21 111-123-456', 'kolahi.jpg', 1),
('BBQ Tonight', 'Karachi\'s most loved BBQ destination serving authentic grilled delicacies since 1992.', 'Gulshan-e-Iqbal, Karachi', 'Gulshan', 'BBQ, Pakistani', '$$', 4.6, '6:00 PM - 1:00 AM', '+92 21 111-234-567', 'bbq-tonight.jpg', 1),
('Al Bustan', 'Elegant Lebanese and Middle Eastern cuisine in the heart of Clifton.', 'Clifton, Karachi', 'Clifton', 'Lebanese, Middle Eastern', '$$$', 4.6, '12:00 PM - 11:00 PM', '+92 21 111-345-678', 'al-bustan.jpg', 1),
('Saltanat', 'Royal Mughlai cuisine experience fit for kings.', 'DHA Phase 8, Karachi', 'DHA', 'Mughlai, Pakistani', '$$$', 4.5, '1:00 PM - 12:00 AM', '+92 21 111-456-789', 'saltanat.jpg', 0),
('Ginsoy', 'Best Chinese cuisine in Karachi with authentic flavors.', 'Clifton, Karachi', 'Clifton', 'Chinese', '$$', 4.4, '12:00 PM - 11:30 PM', '+92 21 111-567-890', 'ginsoy.jpg', 0);

-- Insert Cafes
INSERT INTO cafes (name, description, address, location, coffee_types, rating, opening_hours, phone, image, featured) VALUES
('Coffee Wagon', 'Artisanal coffee and cozy atmosphere in Clifton. Perfect for work or relaxation.', 'Clifton, Karachi', 'Clifton', 'Espresso, Latte, Cappuccino, Cold Brew', 4.6, '8:00 AM - 11:00 PM', '+92 21 111-678-901', 'coffee-wagon.jpg', 1),
('Espresso', 'Premium coffee experience in Bahadurabad with imported beans.', 'Bahadurabad, Karachi', 'Bahadurabad', 'Espresso, Americano, Mocha, Caramel Latte', 4.7, '7:00 AM - 12:00 AM', '+92 21 111-789-012', 'espresso.jpg', 1),
('Cafe Aylanto', 'Elegant cafe with Continental cuisine and premium coffee.', 'Zamzama, Karachi', 'Zamzama', 'Cappuccino, Latte, Special Brews', 4.5, '9:00 AM - 11:30 PM', '+92 21 111-890-123', 'cafe-aylanto.jpg', 1),
('Ginoxy', 'Trendy cafe in Clifton with unique coffee blends.', 'Clifton, Karachi', 'Clifton', 'Frappuccino, Espresso, Turkish Coffee', 4.4, '10:00 AM - 12:00 AM', '+92 21 111-901-234', 'ginoxy-cafe.jpg', 1),
('Evergreen Cafe', 'Garden-themed cafe perfect for breakfast and brunch.', 'Clifton, Karachi', 'Clifton', 'Cold Coffee, Frappuccino, Americano', 4.4, '10:00 AM - 10:00 PM', '+92 21 111-012-345', 'evergreen.jpg', 0),
('Big Tree House Cafe', 'Unique treehouse experience in DHA.', 'DHA, Karachi', 'DHA', 'Specialty Coffee, Tea, Espresso', 4.6, '8:00 AM - 11:00 PM', '+92 21 111-123-789', 'big-tree.jpg', 0),
('Cafe Flo', 'French-inspired cafe with authentic pastries.', 'Clifton, Karachi', 'Clifton', 'French Press, Espresso, Latte', 4.5, '8:00 AM - 10:00 PM', '+92 21 111-234-890', 'cafe-flo.jpg', 0),
('Cote Rotie', 'Luxury cafe experience with premium coffee blends.', 'DHA Phase 8, Karachi', 'DHA', 'Premium Blends, Single Origin', 4.8, '7:00 AM - 1:00 AM', '+92 21 111-345-901', 'cote-rotie.jpg', 1);

-- Insert Food Streets
INSERT INTO food_streets (name, description, location, famous_for, image, rating) VALUES
('Burns Road', 'Historic food street famous for traditional Pakistani street food since 1950s. Home to legendary food spots.', 'Karachi', 'Nihari, Biryani, BBQ, Rabri, Street Food', 'burns-road.jpg', 4.6),
('Do Darya', 'Waterfront dining destination with premium restaurants offering stunning Arabian Sea views.', 'Clifton, Karachi', 'Seafood, Fine Dining, BBQ, Continental', 'do-darya.jpg', 4.5),
('Boat Basin', 'Lively food hub with diverse dining options open till late night.', 'Karachi', 'BBQ, Fast Food, Continental, Desi', 'boat-basin.jpg', 4.4),
('Hussainabad', 'Famous for late night BBQ and authentic street food experience.', 'Karachi', 'BBQ, Seekh Kabab, Malai Boti, Street Food', 'hussainabad.jpg', 4.5),
('Bahadurabad', 'Food paradise with budget-friendly options and famous sweet shops.', 'Bahadurabad, Karachi', 'Fast Food, Desi Food, Sweets, Biryani', 'bahadurabad.jpg', 4.3),
('Zamzama', 'Upscale dining destination with trendy cafes and fine dining restaurants.', 'DHA, Karachi', 'Cafes, Continental, Fine Dining, Italian', 'zamzama.jpg', 4.6);

-- Insert Reviews
INSERT INTO reviews (user_id, restaurant_id, rating, comment) VALUES
(2, 1, 5, 'Amazing food and great service! The BBQ platter is a must try.'),
(3, 2, 5, 'Best BBQ in town. Their malai boti is incredible!'),
(4, 3, 4, 'Great Lebanese food. Hummus and falafel were delicious.'),
(2, 4, 5, 'Royal experience! The mutton karahi was outstanding.'),
(3, 5, 4, 'Best Chinese food in Karachi. Highly recommend chowmein.');

INSERT INTO reviews (user_id, cafe_id, rating, comment) VALUES
(2, 1, 5, 'Best coffee in Clifton. Great ambiance for work.'),
(3, 2, 5, 'Espresso here is perfect. Barista knows his craft.'),
(4, 3, 4, 'Beautiful place, amazing coffee and pastries.'),
(2, 6, 5, 'Unique experience! Must visit for coffee lovers.'),
(3, 8, 5, 'Premium coffee at its best. Worth every penny.');

-- Update Statistics
INSERT INTO site_stats (total_restaurants, total_cafes, total_users, total_reviews) VALUES
((SELECT COUNT(*) FROM restaurants), 
 (SELECT COUNT(*) FROM cafes), 
 (SELECT COUNT(*) FROM users), 
 (SELECT COUNT(*) FROM reviews));