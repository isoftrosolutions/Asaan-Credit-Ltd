-- InvestMatch Nepal - Database Schema
-- Run this file to initialize the database

CREATE DATABASE IF NOT EXISTS invest_match CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE invest_match;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'entrepreneur',
    account_type VARCHAR(50) NULL,
    phone VARCHAR(50) NULL,
    province VARCHAR(100) NULL,
    district VARCHAR(100) NULL,
    profile_photo VARCHAR(255) NULL,
    company_name VARCHAR(255) NULL,
    bio TEXT NULL,
    linkedin_url VARCHAR(255) NULL,
    website_url VARCHAR(255) NULL,
    verification_status VARCHAR(50) NOT NULL DEFAULT 'unverified',
    verified_at TIMESTAMP NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    is_suspended TINYINT(1) NOT NULL DEFAULT 0,
    daily_request_count INT NOT NULL DEFAULT 0,
    daily_request_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) ENGINE=InnoDB;

-- Password reset tokens
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB;

-- Sessions
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB;

-- Sectors
CREATE TABLE IF NOT EXISTS sectors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Investor profiles
CREATE TABLE IF NOT EXISTS investor_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    past_investments INT NOT NULL DEFAULT 0,
    portfolio_companies TEXT NULL,
    total_capital_deployed DECIMAL(15,2) NULL,
    preferred_sectors JSON NULL,
    preferred_stages JSON NULL,
    ticket_min DECIMAL(15,2) NULL,
    ticket_max DECIMAL(15,2) NULL,
    preferred_geography JSON NULL,
    references_text TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Pitches
CREATE TABLE IF NOT EXISTS pitches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    tagline VARCHAR(140) NULL,
    problem_statement TEXT NULL,
    solution TEXT NULL,
    market_size TEXT NULL,
    business_model TEXT NULL,
    traction TEXT NULL,
    funding_amount DECIMAL(15,2) NULL,
    equity_offered DECIMAL(5,2) NULL,
    fund_usage TEXT NULL,
    valuation DECIMAL(15,2) NULL,
    pitch_deck VARCHAR(255) NULL,
    pitch_video_url VARCHAR(255) NULL,
    stage VARCHAR(100) NULL,
    sector_id BIGINT UNSIGNED NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_hidden TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Pitch media
CREATE TABLE IF NOT EXISTS pitch_media (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pitch_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pitch_id) REFERENCES pitches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Pitch team members
CREATE TABLE IF NOT EXISTS pitch_team_members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pitch_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) NULL,
    linkedin_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pitch_id) REFERENCES pitches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Verification documents
CREATE TABLE IF NOT EXISTS verification_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    rejection_reason TEXT NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Interest requests
CREATE TABLE IF NOT EXISTS interest_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    pitch_id BIGINT UNSIGNED NULL,
    message TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    responded_at TIMESTAMP NULL,
    rejected_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pitch_id) REFERENCES pitches(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(100) NOT NULL,
    title TEXT NOT NULL,
    body TEXT NULL,
    action_url VARCHAR(255) NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- FAQs
CREATE TABLE IF NOT EXISTS faqs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Homepage contents
CREATE TABLE IF NOT EXISTS homepage_contents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(255) NOT NULL UNIQUE,
    content_value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
