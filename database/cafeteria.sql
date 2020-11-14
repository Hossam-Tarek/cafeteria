CREATE DATABASE cafeteria;

USE cafeteria;

CREATE TABLE Category (
    category_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(64) NOT NULL UNIQUE ,
    PRIMARY KEY (category_id)
);

CREATE TABLE Room (
    room_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(64) NOT NULL UNIQUE ,
    PRIMARY KEY (room_id)
);

CREATE TABLE Product (
    product_id INT NOT NULL AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(64) NOT NULL UNIQUE,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(256),
    available TINYINT DEFAULT 1,
    PRIMARY KEY (product_id),
    INDEX (name),

    CONSTRAINT product_category_fk FOREIGN KEY (category_id) REFERENCES Category(category_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE User (
    user_id INT NOT NULL AUTO_INCREMENT,
    room_id INT NOT NULL,
    name VARCHAR(64) NOT NULL,
    email VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(128) NOT NULL,
    extra_info VARCHAR(256),
    avatar VARCHAR(256),
    PRIMARY KEY (user_id),
    INDEX (email),

    CONSTRAINT user_room_fk FOREIGN KEY (room_id) REFERENCES Room (room_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `Order` (
    order_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    date DATETIME DEFAULT NOW(),
    status TINYINT DEFAULT 1,
    comment VARCHAR(256),
    PRIMARY KEY (order_id),

    CONSTRAINT order_user_fk FOREIGN KEY (user_id) REFERENCES User (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT order_room_fk FOREIGN KEY (room_id) REFERENCES Room (room_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Order_Product (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    PRIMARY KEY (order_id, product_id),

    CONSTRAINT order_product_order_fk FOREIGN KEY (order_id) REFERENCES `Order` (order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT order_product_product_fk FOREIGN KEY (product_id) REFERENCES Product (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
