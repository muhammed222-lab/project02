-- Create the database
CREATE DATABASE project_02;
USE project_02;
-- Table for storing user details
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('student', 'freelancer', 'instructor', 'creator'),
    department VARCHAR(100) NULL,
    matric_number VARCHAR(50) NULL,
    join_date DATE NULL,
    profile_picture VARCHAR(255) DEFAULT 'default_profile.png',
    cookies_accepted BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Table for storing projects created by creators or freelancers
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    creator_id INT,
    title VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_sold BOOLEAN DEFAULT 0,
    category VARCHAR(100),
    FOREIGN KEY (creator_id) REFERENCES users(id)
);
-- Table for managing project interests and purchases by students or freelancers
CREATE TABLE project_interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    user_id INT,
    interest_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_bought BOOLEAN DEFAULT 0,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- Table to handle payments and commissions for project purchases
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    buyer_id INT,
    creator_id INT,
    amount DECIMAL(10, 2),
    commission DECIMAL(10, 2) DEFAULT 0.20,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (creator_id) REFERENCES users(id)
);
-- Table for storing feedback or reviews on projects
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    reviewer_id INT,
    rating INT CHECK (
        rating BETWEEN 1 AND 5
    ),
    review_text TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id)
);
-- Table for managing cookie consent and storing user actions
CREATE TABLE user_cookies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    cookie_consent BOOLEAN DEFAULT 0,
    consent_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- Table for managing notifications and alerts for users
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    status ENUM('unread', 'read') DEFAULT 'unread',
    notification_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- Table for managing user sessions and storing login logs
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_token VARCHAR(255),
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    logout_time TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
ALTER TABLE projects
ADD COLUMN project_file VARCHAR(255) NOT NULL,
    ADD COLUMN writeup_file VARCHAR(255) NOT NULL;
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS `bank_details` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `account_number` VARCHAR(20) NOT NULL,
    `account_bank` VARCHAR(10) NOT NULL,
    `account_name` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
CREATE TABLE custom_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    keywords VARCHAR(255) NOT NULL,
    deadline DATE NOT NULL,
    budget DECIMAL(10, 2) NOT NULL,
    project_proposal VARCHAR(255) NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    student_name VARCHAR(255) NOT NULL,
    student_id INT NOT NULL,
    apply_count INT DEFAULT 0,
    -- Number of creators that applied
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE project_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    -- References the custom_projects table
    creator_id INT NOT NULL,
    -- Creator's ID
    creator_name VARCHAR(255) NOT NULL,
    creator_email VARCHAR(255) NOT NULL,
    why_choose_me TEXT,
    qualification VARCHAR(255),
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES custom_projects(id) ON DELETE CASCADE
);