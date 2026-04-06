-- ============================================================
-- Assignment 1: User Registration & Login System
-- Author  : RISHAV RAJ  |  23MEI10002
-- File    : assignment1_schema.sql
-- ============================================================

-- Create and select database
CREATE DATABASE IF NOT EXISTS user_auth_db;
USE user_auth_db;

-- Drop table if exists (for fresh setup)
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample users (passwords are SHA-256 hashed)
INSERT INTO users (name, email, password) VALUES
('Aarav Sharma',  'aarav@example.com',  SHA2('pass1234', 256)),
('Priya Mehta',   'priya@example.com',  SHA2('priya@123', 256)),
('Rohit Kumar',   'rohit@example.com',  SHA2('rohit#456', 256));

-- Verify inserted data
SELECT id, name, email, created_at FROM users;
