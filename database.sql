CREATE DATABASE IF NOT EXISTS campusloop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campusloop;

CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
    event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    venue VARCHAR(150) NOT NULL,
    capacity INT UNSIGNED NOT NULL DEFAULT 50,
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_creator FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_events_date (event_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    event_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_registrations_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_registrations_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    CONSTRAINT uq_user_event UNIQUE (user_id, event_id),
    INDEX idx_registrations_event (event_id)
) ENGINE=InnoDB;

-- After registering through the website, promote your own account to admin:
-- UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
