CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL UNIQUE,
    code VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE lots(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_end DATETIME NOT NULL,
    name VARCHAR(128) NOT NULL,
    description VARCHAR(256),
    image_url VARCHAR(128),
    price_default FLOAT NOT NULL,
    price_step FLOAT NOT NULL
);

CREATE TABLE lot_author(
    lot_id INT NOT NULL,
    user_id INT NOT NULL
);

CREATE TABLE lot_winner(
    lot_id INT NOT NULL,
    user_id INT NOT NULL
);

CREATE TABLE lot_category(
    lot_id INT NOT NULL,
    category_id INT NOT NULL
);

CREATE TABLE bets(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_bet TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total FLOAT NOT NULL
);

CREATE TABLE bet_user(
    bet_id INT NOT NULL,
    user_id INT NOT NULL
);

CREATE TABLE bet_lot(
    bet_id INT NOT NULL,
    lot_id INT NOT NULL
);

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) NOT NULL UNIQUE,
    name VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL,
    contacts VARCHAR(128) NOT NULL
);

CREATE UNIQUE INDEX namex ON categories(name);
CREATE UNIQUE INDEX codex ON categories(code);
CREATE UNIQUE INDEX emailx ON users(email);

ALTER TABLE users ADD INDEX id(id);
ALTER TABLE bets ADD INDEX id(id);
ALTER TABLE lots ADD INDEX id(id);
ALTER TABLE categories ADD INDEX id(id);
