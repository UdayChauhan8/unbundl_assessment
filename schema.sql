CREATE DATABASE IF NOT EXISTS unbundl_db;
USE unbundl_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    -- Phone is validated/collected as exactly 10 digits in the PHP + HTML.
    phone VARCHAR(10) NOT NULL,
    address TEXT NOT NULL
);
