CREATE DATABASE IF NOT EXISTS webshop;
CREATE USER 'webuser'@'%' IDENTIFIED BY 'webpassword';
GRANT SELECT, INSERT, UPDATE, DELETE ON webshop.* TO 'webuser'@'%';
FLUSH PRIVILEGES;

USE webshop;

/* Drop all tables in webshop databace */
DROP TABLE IF EXISTS ShoppingCart;
DROP TABLE IF EXISTS Order_Product;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Categorys;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Address;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Admins;

/* Create tables */

CREATE TABLE Admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role ENUM('superAdmin', 'admin') NOT NULL,
    passwordhash VARCHAR(100) NOT NULL
);

/* inserting super admin*/
INSERT INTO Admins (username, first_name, last_name, role, passwordhash)
        VALUES ('SAdmin', '', '', 'superAdmin', "$2y$10$F5/0OrChMOsrK22InclUkeEG598PK8ex5s11GIne1yuvo8ThrxqEy");

CREATE TABLE Customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    passwordhash VARCHAR(100) NOT NULL,
    deleted BOOLEAN DEFAULT 0
);

/* inserting test customer white password 'password' */
INSERT INTO Customer (first_name, last_name, email, date_of_birth, passwordhash)
        VALUES ('Test', 'Customer', 'test@example.com', '1990-01-01', '$2y$10$Mrov9SOPMYt9hREjnwkHau/tgTJ.EaOMcm6tWW9ZvIA00QJetMXAS');

CREATE TABLE Address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    street VARCHAR(100),
    street_number INT,
    city VARCHAR(100),
    postal_code VARCHAR(10),
    country VARCHAR(100),
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES Customer(id)
);

INSERT INTO Address (street, street_number, city, postal_code, country, customer_id)
        VALUES ('test', 123, 'test', '10001', 'ts', (SELECT id FROM Customer WHERE email = 'test@example.com'));

CREATE TABLE Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    imagePath VARCHAR(100)
);

CREATE TABLE Categorys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    main_category_id INT,
    sub_category_id INT,
    level TINYINT DEFAULT 1,
    FOREIGN KEY (main_category_id) REFERENCES Category(id),
    FOREIGN KEY (sub_category_id) REFERENCES Category(id)
);

CREATE TABLE Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price FLOAT,
    description TEXT,
    manufacturer VARCHAR(100),
    stock INT,
    imagePath VARCHAR(100),
    available BOOLEAN,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES Category(id)
);

CREATE TABLE Review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_id INT,
    rating INT,
    comment TEXT,
    FOREIGN KEY (product_id) REFERENCES Product(id),
    FOREIGN KEY (customer_id) REFERENCES Customer(id)
);

CREATE TABLE Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    address_id INT,
    order_date DATE,
    total_price FLOAT,
    status ENUM('pending', 'shipped', 'delivered', 'cancelled'),
    FOREIGN KEY (customer_id) REFERENCES Customer(id),
    FOREIGN KEY (address_id) REFERENCES Address(id)
);

CREATE TABLE Order_Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orders_id INT,
    product_id INT,
    quantity INT,
    price FLOAT,
    FOREIGN KEY (orders_id) REFERENCES Orders(id),
    FOREIGN KEY (product_id) REFERENCES Product(id)
);

CREATE TABLE ShoppingCart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (customer_id) REFERENCES Customer(id),
    FOREIGN KEY (product_id) REFERENCES Product(id)
);

/* Inserting data root category */
INSERT INTO Category (name, imagePath) VALUES ('Electronics', '/resources/example_categories/Electronics.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Clothing', '/resources/example_categories/Clothing.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Books', '/resources/example_categories/Books.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Home & Garden', '/resources/example_categories/Home_&_Garden.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Toys', '/resources/example_categories/Toys.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Sports & Outdoors', '/resources/example_categories/Sports_&_Outdoors.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Health & Beauty', '/resources/example_categories/Health_&_Beauty.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Automotive', '/resources/example_categories/Automotive.jpg');

/* Inserting data sub category */
INSERT INTO Category (name, imagePath) VALUES ('Laptops', '/resources/example_categories/Laptops.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Laptops'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Smartphones', '/resources/example_categories/Smartphones.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Smartphones'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Tablets', '/resources/example_categories/Tablets.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Tablets'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Headphones', '/resources/example_categories/Headphones.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Headphones'), 1);
INSERT INTO Category (name, imagePath) VALUES ('T-Shirts', '/resources/example_categories/T-Shirts.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'T-Shirts'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Jeans', '/resources/example_categories/Jeans.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'Jeans'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Sweaters', '/resources/example_categories/Sweaters.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'Sweaters'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Hoodies', '/resources/example_categories/Hoodies.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'Hoodies'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Fantasy', '/resources/example_categories/Fantasy.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Books'), (SELECT id FROM Category WHERE name = 'Fantasy'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Science Fiction', '/resources/example_categories/Science_Fiction.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Books'), (SELECT id FROM Category WHERE name = 'Science Fiction'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Romance', '/resources/example_categories/Romance.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Books'), (SELECT id FROM Category WHERE name = 'Romance'), 1);
INSERT INTO Category (name, imagePath) VALUES ('Gardening', '/resources/example_categories/Gardening.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Home & Garden'), (SELECT id FROM Category WHERE name = 'Gardening'), 1);

/* creating sub categories of sub categories */
INSERT INTO Category (name, imagePath) VALUES ('Gaming Laptops', '/resources/example_categories/Gaming_Laptops.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Laptops'), (SELECT id FROM Category WHERE name = 'Gaming Laptops'), 2);
INSERT INTO Category (name, imagePath) VALUES ('Business Laptops', '/resources/example_categories/Business_Laptops.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id, level) VALUES ((SELECT id FROM Category WHERE name = 'Laptops'), (SELECT id FROM Category WHERE name = 'Business Laptops'), 2);

/* Inserting data in sub categories */
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Alienware M15', 1499.99, 'Alienware M15 gaming laptop with Intel Core i7, 16GB RAM and 512GB SSD.', 'Dell', 5, '/resources/example_products/Alienware-M15.jpg', 1, (SELECT id FROM Category WHERE name = 'Gaming Laptops'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Dell XPS 13', 1199.99, 'Dell XPS 13 business laptop with Intel Core i5, 8GB RAM and 256GB SSD.', 'Dell', 10, '/resources/example_products/Dell_XPS_13.jpeg', 1, (SELECT id FROM Category WHERE name = 'Business Laptops'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('iPhone 12 Pro', 999.99, 'The new iPhone 12 Pro with 256GB storage and LiDAR scanner.', 'Apple', 15, '/resources/example_products/iPhone_12_Pro.jpg', 1, (SELECT id FROM Category WHERE name = 'Smartphones'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Samsung Galaxy S21', 899.99, 'The new Samsung Galaxy S21 with 128GB storage and 5G support.', 'Samsung', 20, '/resources/example_products/Samsung_Galaxy_S21.jpg', 1, (SELECT id FROM Category WHERE name = 'Smartphones'));

/* Inserting data products */
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Macbook Pro', 1299.99, 'The new Macbook Pro with M1 chip, 8GB RAM and 256GB SSD.', 'Apple', 10, '/resources/example_products/Macbook_Pro.jpg', 1, (SELECT id FROM Category WHERE name = 'Laptops'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('iPhone 12', 799.99, 'The new iPhone 12 with 128GB storage and 5G support.', 'Apple', 20, '/resources/example_products/iPhone_12.jpg', 1, (SELECT id FROM Category WHERE name = 'Smartphones'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('iPad Pro', 999.99, 'The new iPad Pro with 11-inch display, 128GB storage and Apple Pencil support.', 'Apple', 15, '/resources/example_products/iPad_Pro.jpeg', 1, (SELECT id FROM Category WHERE name = 'Tablets'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('AirPods Pro', 249.99, 'The new AirPods Pro with active noise cancellation and transparency mode.', 'Apple', 30, '/resources/example_products/AirPods_Pro.jpg', 1, (SELECT id FROM Category WHERE name = 'Headphones'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('White T-Shirt', 19.99, 'A white T-Shirt made of 100% cotton.', 'Generic', 50, '/resources/example_products/White_T-Shirt.jpg', 1, (SELECT id FROM Category WHERE name = 'T-Shirts'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Blue Jeans', 39.99, 'Blue jeans made of 98% cotton and 2% elastane.', 'Generic', 40, '/resources/example_products/Blue_Jeans.jpg', 1, (SELECT id FROM Category WHERE name = 'Jeans'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Grey Sweater', 29.99, 'A grey sweater made of 100% wool.', 'Generic', 35, '/resources/example_products/Grey_Sweater.jpg', 1, (SELECT id FROM Category WHERE name = 'Sweaters'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Black Hoodie', 49.99, 'A black hoodie made of 80% cotton and 20% polyester.', 'Generic', 45, '/resources/example_products/Black_Hoodie.jpg', 1, (SELECT id FROM Category WHERE name = 'Hoodies'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('The Hobbit', 9.99, 'The Hobbit by J.R.R. Tolkien.', 'HarperCollins', 60, '/resources/example_products/The_Hobbit.jpg', 1, (SELECT id FROM Category WHERE name = 'Fantasy'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Dune', 14.99, 'Dune by Frank Herbert.', 'Chilton Books', 55, '/resources/example_products/Dune.jpg', 1, (SELECT id FROM Category WHERE name = 'Science Fiction'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Pride and Prejudice', 7.99, 'Pride and Prejudice by Jane Austen.', 'T. Egerton', 65, '/resources/example_products/Pride_and_Prejudice.jpg', 1, (SELECT id FROM Category WHERE name = 'Romance'));
INSERT INTO Product (name, price, description, manufacturer, stock, imagePath, available, category_id) VALUES ('Gardening for Dummies', 19.99, 'Gardening for Dummies by Sue Fisher.', 'For Dummies', 70, '/resources/example_products/Gardening_for_Dummies.jpg', 1, (SELECT id FROM Category WHERE name = 'Gardening'));

/* Inserting reviews */
INSERT INTO Review (rating, comment, product_id, customer_id) VALUES (5, 'Great laptop, very fast and reliable.', (SELECT id FROM Product WHERE name = 'Macbook Pro'), (SELECT id FROM Customer WHERE email = 'test@example.com'));
INSERT INTO Review (rating, comment, product_id, customer_id) VALUES (4, 'Good phone, but battery life could be better.', (SELECT id FROM Product WHERE name = 'iPhone 12'), (SELECT id FROM Customer WHERE email = 'test@example.com'));
INSERT INTO Review (rating, comment, product_id, customer_id) VALUES (5, 'Excellent tablet, very fast and great display.', (SELECT id FROM Product WHERE name = 'iPad Pro'), (SELECT id FROM Customer WHERE email = 'test@example.com'));
INSERT INTO Review (rating, comment, product_id, customer_id) VALUES (5, 'Best headphones I ever had, sound quality is amazing.', (SELECT id FROM Product WHERE name = 'AirPods Pro'), (SELECT id FROM Customer WHERE email = 'test@example.com'));
INSERT INTO Review (rating, comment, product_id, customer_id) VALUES (4, 'Nice T-Shirt, but a bit expensive.', (SELECT id FROM Product WHERE name = 'White T-Shirt'), (SELECT id FROM Customer WHERE email = 'test@example.com'));
