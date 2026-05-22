-- Full database setup for BeautiEase
-- Run this in phpMyAdmin or MySQL to create all tables

CREATE DATABASE IF NOT EXISTS beautiease;
USE beautiease;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    role ENUM('User', 'Admin') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    service_type VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    address_type VARCHAR(255) NOT NULL,
    address TEXT,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    status ENUM('Pending', 'Approved', 'Completed') DEFAULT 'Pending',
    payment_status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Payments table (if used)
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_status VARCHAR(50) DEFAULT 'Unpaid',
    amount DECIMAL(10,2),
    transaction_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Insert sample admin user (password: admin123, hashed)
INSERT INTO users (fullname, email, password, phone, role) VALUES 
('Admin User', 'admin@beautyease.com', '0192023a7bbd73250516f069df18b500', '1234567890', 'Admin')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample services
INSERT INTO services (service_name, price, description, service_type) VALUES 
('Hair Cut', 500.00, 'Professional hair cutting service', 'Hair'),
('Facial', 800.00, 'Relaxing facial treatment', 'Skin')
ON DUPLICATE KEY UPDATE id=id;