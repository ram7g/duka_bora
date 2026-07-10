
-- Online Market Inventory System - "Duka Bora"
-- Database name: duka_bora_db

-- Create the database
CREATE DATABASE IF NOT EXISTS duka_bora;
USE duka_bora;

-- 1. CATEGORIES TABLE

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);


-- 2. SUPPLIERS TABLE
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    location VARCHAR(150)
);

-- 3. PRODUCTS TABLE
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price > 0),
    stock_qty INT NOT NULL DEFAULT 0 CHECK (stock_qty >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

-- 4. SALES TABLE
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    qty_sold INT NOT NULL CHECK (qty_sold > 0),
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

-- SEED DATA: CATEGORIES (3 categories)
INSERT INTO categories (category_name) VALUES
('Electronics'),
('Clothing'),
('Food');

-- SEED DATA: SUPPLIERS (3 suppliers)
INSERT INTO suppliers (supplier_name, phone, location) 
VALUES('MaTechHub Distributors', '+255-712-345-678', 'Dar es Salaam'),
('Fashion Line Ltd', '+255-713-456-789', 'Arusha'),
('Fresh Foods Co.', '+255-714-567-890', 'Mwanza');

-- SEED DATA: PRODUCTS (10 products across 3 categories/suppliers)
INSERT INTO products (name, category_id, supplier_id, price, stock_qty)
VALUES('Wireless Mouse', 1, 1, 15000.00, 25),
('Bluetooth Headphones', 1, 1, 45000.00, 12),
('USB-C Charger', 1, 1, 10000.00, 3),
('LED Desk Lamp', 1, 1, 20000.00, 8),
('Men\'s T-Shirt', 2, 2, 12000.00, 30),
('Women\'s Dress', 2, 2, 3000.00, 15),
('Super Jeans', 2, 2, 28000.00, 0),
('Rice (5kg Bag)', 3, 3, 18000.00, 40),
('Cooking Oil (2L)', 3, 3, 9500.00, 4),
('Sugar (2kg Bag)', 3, 3, 6000.00, 20);