-- Auth
DROP TABLE IF EXISTS players;
CREATE TABLE players (
    player_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    money BIGINT UNSIGNED NOT NULL,
    income INT UNSIGNED NOT NULL,
    attack INT UNSIGNED NOT NULL,
    defence INT UNSIGNED NOT NULL,
    paid_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL
);

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
    session_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session VARCHAR(255) UNIQUE NOT NULL,
    player_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL,
    expires_at DATETIME NOT NULL
);

-- Units
DROP TABLE IF EXISTS units;
CREATE TABLE units (
    unit_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price BIGINT UNSIGNED NOT NULL,
    attack INT UNSIGNED NOT NULL,
    defence INT UNSIGNED NOT NULL
);
INSERT INTO units (name, price, attack, defence) VALUES
    ('Minigunner', 500, 3, 3),
    ('Military Car', 2500, 6, 6),
    ('Humvee', 4500, 9, 6);

DROP TABLE IF EXISTS player_unit;
CREATE TABLE player_unit (
    player_id INT UNSIGNED NOT NULL,
    unit_id INT UNSIGNED NOT NULL,
    amount INT NOT NULL
);

-- Buildings
DROP TABLE IF EXISTS buildings;
CREATE TABLE buildings (
    building_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price BIGINT UNSIGNED NOT NULL,
    income BIGINT UNSIGNED NOT NULL,
    defence INT UNSIGNED NOT NULL
);
INSERT INTO buildings (name, price, income, defence) VALUES
    ('Supply Depot', 5000, 5, 0),
    ('Refinery', 15000, 25, 0),
    ('Bunker', 5000, 0, 25);

DROP TABLE IF EXISTS player_building;
CREATE TABLE player_building (
    player_id INT UNSIGNED NOT NULL,
    building_id INT UNSIGNED NOT NULL,
    amount INT NOT NULL
);