CREATE DATABASE task_shelder
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE task_shelder;
CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(32) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE projects(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(32) NOT NULL,
    user_id int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);
CREATE TABLE tasks(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    status BOOLEAN DEFAULT(0) NOT NULL,
    date DATE,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    file_path TEXT,
    user_id int NOT NULL,
    project_id int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (project_id) REFERENCES projects (id)
);
CREATE FULLTEXT INDEX task_search ON tasks(title);