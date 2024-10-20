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

/* Create tables */
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
INSERT INTO Category (name, imagePath) VALUES ('Sports & Outdoors', 'sports.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Health & Beauty', 'health.jpg');
INSERT INTO Category (name, imagePath) VALUES ('Automotive', 'automotive.jpg');


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

/* Inserting data products */
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Macbook Pro', 1299.99, 'The new Macbook Pro with M1 chip, 8GB RAM and 256GB SSD.', 10, 'macbookpro.jpg', 1, (SELECT id FROM Category WHERE name = 'Laptops'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('iPhone 12', 799.99, 'The new iPhone 12 with 128GB storage and 5G support.', 20, 'iphone12.jpg', 1, (SELECT id FROM Category WHERE name = 'Smartphones'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('iPad Pro', 999.99, 'The new iPad Pro with 11-inch display, 128GB storage and Apple Pencil support.', 15, 'ipadpro.jpg', 1, (SELECT id FROM Category WHERE name = 'Tablets'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('AirPods Pro', 249.99, 'The new AirPods Pro with active noise cancellation and transparency mode.', 30, 'airpodspro.jpg', 1, (SELECT id FROM Category WHERE name = 'Headphones'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('White T-Shirt', 19.99, 'A white T-Shirt made of 100% cotton.', 50, 'whitetshirt.jpg', 1, (SELECT id FROM Category WHERE name = 'T-Shirts'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Blue Jeans', 39.99, 'Blue jeans made of 98% cotton and 2% elastane.', 40, 'bluejeans.jpg', 1, (SELECT id FROM Category WHERE name = 'Jeans'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Grey Sweater', 29.99, 'A grey sweater made of 100% wool.', 35, 'greysweater.jpg', 1, (SELECT id FROM Category WHERE name = 'Sweaters'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Black Hoodie', 49.99, 'A black hoodie made of 80% cotton and 20% polyester.', 45, 'blackhoodie.jpg', 1, (SELECT id FROM Category WHERE name = 'Hoodies'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('The Hobbit', 9.99, 'The Hobbit by J.R.R. Tolkien.', 60, 'thehobbit.jpg', 1, (SELECT id FROM Category WHERE name = 'Fantasy'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Dune', 14.99, 'Dune by Frank Herbert.', 55, 'dune.jpg', 1, (SELECT id FROM Category WHERE name = 'Science Fiction'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Pride and Prejudice', 7.99, 'Pride and Prejudice by Jane Austen.', 65, 'prideandprejudice.jpg', 1, (SELECT id FROM Category WHERE name = 'Romance'));
INSERT INTO Product (name, price, description, stock, imagePath, available, category_id) VALUES ('Gardening for Dummies', 19.99, 'Gardening for Dummies by Sue Fisher.', 70, 'gardeningfordummies.jpg', 1, (SELECT id FROM Category WHERE name = 'Gardening'));

/* Inserting reviews */
INSERT INTO Review (rating, comment, product_id) VALUES (5, 'Great laptop, very fast and reliable.', (SELECT id FROM Product WHERE name = 'Macbook Pro'));
INSERT INTO Review (rating, comment, product_id) VALUES (4, 'Good phone, but battery life could be better.', (SELECT id FROM Product WHERE name = 'iPhone 12'));
INSERT INTO Review (rating, comment, product_id) VALUES (5, 'Excellent tablet, very fast and great display.', (SELECT id FROM Product WHERE name = 'iPad Pro'));
INSERT INTO Review (rating, comment, product_id) VALUES (5, 'Best headphones I ever had, sound quality is amazing.', (SELECT id FROM Product WHERE name = 'AirPods Pro'));
INSERT INTO Review (rating, comment, product_id) VALUES (4, 'Nice T-Shirt, but a bit expensive.', (SELECT id FROM Product WHERE name = 'White T-Shirt'));
