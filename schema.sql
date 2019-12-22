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
    date_end DATE NOT NULL,
    name VARCHAR(128) NOT NULL,
    description VARCHAR(256) NOT NULL,
    image_url VARCHAR(128) NOT NULL,
    price_default FLOAT NOT NULL,
    price_step FLOAT NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    winner_id INT
);

CREATE TABLE bets(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_bet TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total FLOAT NOT NULL,
    author_id INT NOT NULL,
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

CREATE UNIQUE INDEX name_index ON categories(code);
CREATE UNIQUE INDEX code_index ON categories(code);
CREATE UNIQUE INDEX email_index ON users(email);
CREATE FULLTEXT INDEX lot_ft_index ON lots(name, description);
