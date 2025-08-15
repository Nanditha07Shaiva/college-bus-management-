-- Create database if not exists
CREATE DATABASE IF NOT EXISTS college_bus;
USE college_bus;

-- Table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','driver') NOT NULL
);

-- Table for buses
CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(100) NOT NULL,
    route VARCHAR(100) NOT NULL,
    total_seats INT NOT NULL,
    departure_time TIME NOT NULL
);

-- Table for bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    user_id INT NOT NULL,
    seat_number INT NOT NULL,
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample users (plaintext passwords for now)
INSERT INTO users (username, password, role) VALUES
('student1', 'student123', 'student'),
('driver1', 'driver123', 'driver');

-- Insert sample buses
INSERT INTO buses (bus_name, route, total_seats, departure_time) VALUES
('Bus A', 'Campus to City Center', 40, '08:00:00'),
('Bus B', 'Campus to Train Station', 35, '09:00:00'),
('Bus C', 'Campus to Airport', 30, '10:30:00');
