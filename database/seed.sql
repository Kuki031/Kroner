SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS web_store;

CREATE DATABASE web_store;

USE web_store;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(70) NOT NULL UNIQUE,
    password CHAR(64) NOT NULL,
    is_banned TINYINT DEFAULT 0,
    profile_picture VARCHAR(255) NULL,
    role_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity_available INT NOT NULL,
    image VARCHAR(255) NULL,
    manufacturer_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS manufacturer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    image VARCHAR(255) NULL
);

CREATE TABLE IF NOT EXISTS shopping_cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity_added INT NOT NULL DEFAULT 0
);
/*
SELECT CONSTRAINT_NAME, CONSTRAINT_TYPE
FROM information_schema.TABLE_CONSTRAINTS
WHERE TABLE_NAME = 'shopping_cart'
AND TABLE_SCHEMA = 'web_shop';
*/
ALTER TABLE users
ADD FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE products
ADD FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE shopping_cart
ADD FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE shopping_cart
ADD FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE shopping_cart
ADD CONSTRAINT s_c UNIQUE (user_id, product_id);

INSERT INTO roles (name) VALUES ('administrator'), ('guest');

INSERT INTO
    manufacturer (name)
VALUES ('BrewCo'),
    ('VinoMakers'),
    ('WhiskeyWorks'),
    ('CocktailCraft'),
    ('SpiritMakers');

INSERT INTO
    products (
        name,
        price,
        quantity_available,
        manufacturer_id
    )
VALUES ('BrewCo Lager', 5.99, 100, 1),
    (
        'BrewCo Pale Ale',
        6.49,
        80,
        1
    ),
    (
        'VinoMakers Red Wine',
        12.99,
        50,
        2
    ),
    (
        'VinoMakers White Wine',
        11.99,
        60,
        2
    ),
    (
        'WhiskeyWorks Classic',
        29.99,
        40,
        3
    ),
    (
        'WhiskeyWorks Reserve',
        45.99,
        30,
        3
    ),
    (
        'CocktailCraft Mojito Mix',
        8.99,
        90,
        4
    ),
    (
        'CocktailCraft Margarita Mix',
        9.49,
        85,
        4
    ),
    (
        'SpiritMakers Vodka',
        19.99,
        70,
        5
    ),
    (
        'SpiritMakers Gin',
        21.99,
        65,
        5
    ),
    ('BrewCo IPA', 6.99, 75, 1),
    ('BrewCo Stout', 7.49, 55, 1),
    (
        'VinoMakers Rose Wine',
        13.49,
        45,
        2
    ),
    (
        'WhiskeyWorks Bourbon',
        35.99,
        25,
        3
    ),
    (
        'CocktailCraft Pina Colada Mix',
        10.49,
        95,
        4
    ),
    (
        'SpiritMakers Tequila',
        24.99,
        60,
        5
    ),
    (
        'BrewCo Amber Ale',
        6.79,
        70,
        1
    ),
    (
        'VinoMakers Sparkling Wine',
        14.99,
        35,
        2
    ),
    (
        'WhiskeyWorks Rye Whiskey',
        32.99,
        20,
        3
    ),
    (
        'SpiritMakers Rum',
        22.49,
        50,
        5
    );

SET FOREIGN_KEY_CHECKS = 1;