-- Create database

CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;


-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    user_id INT NOT NULL,
    image_url VARCHAR(255),
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Skills table
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    proficiency ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'beginner',
    user_id INT NOT NULL,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Experiences table
CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    description TEXT,
    current_job BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Contacts table
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO users (email, password, role) VALUES 
('admin@portfolio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('user@portfolio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO projects (title, description, user_id, project_url, github_url) VALUES 
('E-commerce Website', 'Full-stack e-commerce platform with React and Node.js', 1, 'https://example.com', 'https://github.com/example'),
('Portfolio Management System', 'Web application for managing portfolio projects', 1, 'https://portfolio.com', 'https://github.com/portfolio'),
('Task Management App', 'Mobile task management application with React Native', 1, 'https://taskapp.com', 'https://github.com/taskapp');

INSERT INTO skills (name, proficiency, user_id, category) VALUES 
('PHP', 'advanced', 1, 'Backend'),
('JavaScript', 'expert', 1, 'Frontend'),
('MySQL', 'advanced', 1, 'Database'),
('React', 'intermediate', 1, 'Frontend'),
('Node.js', 'intermediate', 1, 'Backend');

INSERT INTO experiences (company, position, user_id, start_date, end_date, description, current_job) VALUES 
('Tech Company A', 'Full Stack Developer', 1, '2022-01-01', '2023-01-01', 'Developed web applications using modern technologies', FALSE),
('Tech Company B', 'Senior Developer', 1, '2023-02-01', NULL, 'Leading development team and architecting solutions', TRUE);

INSERT INTO contacts (name, email, message, status) VALUES 
('John Doe', 'john@example.com', 'I would like to discuss a project opportunity', 'unread'),
('Jane Smith', 'jane@example.com', 'Great portfolio! Are you available for freelance work?', 'read');