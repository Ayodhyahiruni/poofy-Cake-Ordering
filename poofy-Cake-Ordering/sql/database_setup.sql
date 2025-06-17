-- Poofy Cake Business Database Schema
-- Created for MySQL

-- Create database
CREATE DATABASE IF NOT EXISTS poofy_cakes;
USE poofy_cakes;

-- Categories table for cake types
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cakes table for all cake products
CREATE TABLE cakes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    flavor VARCHAR(100),
    size_options TEXT, -- JSON format: ["6 inch", "8 inch", "10 inch"]
    price_small DECIMAL(10,2),
    price_medium DECIMAL(10,2),
    price_large DECIMAL(10,2),
    image_url VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Customer inquiries and orders
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(20),
    cake_type VARCHAR(200),
    preferred_date DATE,
    message TEXT,
    status ENUM('new', 'in_progress', 'completed', 'cancelled') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Gallery images
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    description TEXT,
    image_url VARCHAR(255) NOT NULL,
    cake_id INT NULL, -- Optional link to specific cake
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cake_id) REFERENCES cakes(id)
);

-- Testimonials
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    rating INT DEFAULT 5,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT INTO categories (name, description, icon) VALUES
('Birthday Cakes', 'Special cakes for birthday celebrations', '🎉'),
('Anniversary Cakes', 'Romantic cakes for anniversaries', '💕'),
('Chocolate Cakes', 'Rich and decadent chocolate cakes', '🍫'),
('Cupcakes', 'Individual sized treats', '🧁'),
('Mini Cakes', 'Small portion cakes perfect for intimate celebrations', '🎂');

-- Insert sample cakes
INSERT INTO cakes (category_id, name, description, flavor, size_options, price_small, price_medium, price_large, is_featured) VALUES
(1, 'Rainbow Delight', 'Colorful layered cake perfect for kids birthdays', 'Vanilla with rainbow layers', '["6 inch", "8 inch", "10 inch"]', 25.00, 35.00, 50.00, TRUE),
(1, 'Chocolate Overload', 'Moist & Rich chocolate cake with chocolate ganache', 'Double chocolate', '["6 inch", "8 inch", "10 inch"]', 30.00, 40.00, 55.00, TRUE),
(2, 'Ribbon Romance', 'Elegant cake with fondant ribbons and roses', 'Red velvet', '["8 inch", "10 inch", "12 inch"]', 40.00, 55.00, 75.00, FALSE),
(3, 'Dark Temptation', 'Rich dark chocolate with chocolate shavings', 'Dark chocolate', '["6 inch", "8 inch", "10 inch"]', 28.00, 38.00, 52.00, TRUE),
(4, 'Vanilla Dream Cupcakes', 'Set of 12 vanilla cupcakes with buttercream', 'Vanilla', '["12 pack", "24 pack", "36 pack"]', 18.00, 32.00, 45.00, FALSE),
(5, 'Mini Celebration', 'Perfect for small gatherings', 'Strawberry', '["4 inch", "5 inch"]', 15.00, 20.00, NULL, FALSE);

