CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT,

    `username` VARCHAR(32) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NULL,
    `admin` BOOLEAN NOT NULL DEFAULT FALSE,

    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE (`username`),
    UNIQUE (`email`)
);

CREATE TABLE `sessions` (
    `id` INT UNSIGNED AUTO_INCREMENT,

    `user_id` INT UNSIGNED NOT NULL,
    `session` CHAR(32) NOT NULL,
    `ip` VARCHAR(32) NOT NULL,
    `ip_country` CHAR(2) NOT NULL,
    `ip_city` VARCHAR(255) NOT NULL,
    `ip_lat` DECIMAL(10, 8) NOT NULL,
    `ip_lng` DECIMAL(11, 8) NOT NULL,
    `browser` VARCHAR(32) NOT NULL,
    `browser_version` VARCHAR(16) NOT NULL,
    `platform` VARCHAR(32) NOT NULL,
    `platform_version` VARCHAR(16) NOT NULL,
    `expires_at` DATETIME NOT NULL,

    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE (`session`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);
