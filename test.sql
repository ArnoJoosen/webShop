USE webshop;

DROP TABLE IF EXISTS ShoppingCart;
DROP TABLE IF EXISTS Order_Product;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Categorys;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Address;
DROP TABLE IF EXISTS Customer;

CREATE TABLE Customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    `date_of_birth` DATE,
    passwordhash VARCHAR(100) NOT NULL
);

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

CREATE TABLE Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    imagePath VARCHAR(100)
);

CREATE TABLE Categorys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    main_category_id INT,
    sub_category_id INT,
    FOREIGN KEY (main_category_id) REFERENCES Category(id),
    FOREIGN KEY (sub_category_id) REFERENCES Category(id)
);

CREATE TABLE Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price FLOAT,
    description TEXT,
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
    order_date DATE,
    FOREIGN KEY (customer_id) REFERENCES Customer(id)
);

CREATE TABLE Order_Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orders_id INT,
    product_id INT,
    quantity INT,
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
INSERT INTO Category (name, imagePath) VALUES ('Electronics', 'electronics.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Clothing', 'clothing.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Books', 'books.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Home & Garden', 'home.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Toys', 'toys.jpg');

/* Inserting data sub category */
INSERT INTO Category (name, imagePath) VALUES ('Laptops', 'laptops.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Laptops'));
INSERT INTO Category (name, imagePath) VALUES ('Smartphones', 'smartphones.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Smartphones'));
INSERT INTO Category (name, imagePath) VALUES ('Tablets', 'tablets.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Tablets'));
INSERT INTO Category (name, imagePath) VALUES ('Headphones', 'headphones.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Electronics'), (SELECT id FROM Category WHERE name = 'Headphones'));
INSERT INTO Category (name, imagePath) VALUES ('T-Shirts', 'tshirts.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'T-Shirts'));
INSERT INTO Category (name, imagePath) VALUES ('Jeans', 'jeans.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'Jeans'));
INSERT INTO Category (name, imagePath) VALUES ('Sweaters', 'sweaters.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'Sweaters'));
INSERT INTO Category (name, imagePath) VALUES ('Hoodies', 'hoodies.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Clothing'), (SELECT id FROM Category WHERE name = 'Hoodies'));
INSERT INTO Category (name, imagePath) VALUES ('Fantasy', 'fantasy.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Books'), (SELECT id FROM Category WHERE name = 'Fantasy'));
INSERT INTO Category (name, imagePath) VALUES ('Science Fiction', 'scifi.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Books'), (SELECT id FROM Category WHERE name = 'Science Fiction'));
INSERT INTO Category (name, imagePath) VALUES ('Romance', 'romance.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Books'), (SELECT id FROM Category WHERE name = 'Romance'));
INSERT INTO Category (name, imagePath) VALUES ('Gardening', 'gardening.jpg');
INSERT INTO Categorys (main_category_id, sub_category_id) VALUES ((SELECT id FROM Category WHERE name = 'Home & Garden'), (SELECT id FROM Category WHERE name = 'Gardening'));
