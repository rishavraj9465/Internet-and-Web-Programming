-- ============================================================
-- Assignment 3: AJAX-Based Product Search System
-- Author  : RISHAV RAJ  |  23MEI10002
-- File    : assignment3_schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS product_db;
USE product_db;

DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(150) NOT NULL,
    category VARCHAR(80)  NOT NULL,
    price    DECIMAL(10,2) NOT NULL
);

INSERT INTO products (name, category, price) VALUES
('Samsung Galaxy S24',      'Electronics',  72999.00),
('Apple iPhone 15',         'Electronics',  79999.00),
('Sony WH-1000XM5 Headset', 'Electronics',  29999.00),
('Levi''s 511 Jeans',       'Clothing',      3499.00),
('Nike Air Max Sneakers',   'Clothing',      8999.00),
('Formal Cotton Shirt',     'Clothing',      1299.00),
('Clean Code (Book)',       'Books',          699.00),
('Let Us C (Book)',         'Books',          399.00),
('PHP & MySQL Novice',      'Books',          549.00),
('Wooden Study Table',      'Furniture',    12500.00),
('Ergonomic Office Chair',  'Furniture',    18900.00),
('LED Desk Lamp',           'Electronics',   1999.00);

-- Test search query (simulates AJAX search)
SELECT id, name, category, price
  FROM products
 WHERE name LIKE '%phone%' OR category LIKE '%phone%'
 ORDER BY name ASC;
