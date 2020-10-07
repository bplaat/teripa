CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT,

    `username` VARCHAR(32) NOT NULL,
    `email` VARCHAR(191) NULL,
    `password` VARCHAR(191) NULL,
    `old_password` CHAR(32) NULL,
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
    `ip_city` VARCHAR(191) NOT NULL,
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

CREATE TABLE `legacy_players` (
    `id` INT UNSIGNED AUTO_INCREMENT,

    `user_id` INT UNSIGNED NOT NULL,
    `food` BIGINT NOT NULL,
    `wood` BIGINT NOT NULL,
    `gold` BIGINT NOT NULL,
    `won` BIGINT NOT NULL DEFAULT 0,
    `lost` BIGINT NOT NULL DEFAULT 0,
    `payed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `map` TEXT NOT NULL,
    `units` TEXT NOT NULL,

    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);
