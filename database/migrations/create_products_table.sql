
START TRANSACTION;

CREATE TABLE products (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) UNSIGNED NOT NULL,
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    rating DECIMAL(3,2) DEFAULT 0.00,
    stock INT DEFAULT 0,
    brand VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    thumbnail VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY idx_product_id (product_id),
    KEY idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
