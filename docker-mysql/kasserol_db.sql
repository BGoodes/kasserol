CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(45) NOT NULL,
  lastName VARCHAR(45) NOT NULL,
  hash VARCHAR(255) NOT NULL,
  email VARCHAR(45) NOT NULL,
  phone VARCHAR(16),
  PRIMARY KEY (id)
);

CREATE TABLE associations (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE materials (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  description VARCHAR(255),
  barcode VARCHAR(45),
  number INT NOT NULL,
  associationId INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (associationId) REFERENCES associations(id)
);

CREATE TABLE transactions (
  materialId INT NOT NULL,
  userId INT NOT NULL,
  number INT NOT NULL,
  date DATETIME NOT NULL,
  PRIMARY KEY (materialId, userId),
  FOREIGN KEY (materialId) REFERENCES materials(id),
  FOREIGN KEY (userId) REFERENCES users(id)
);

INSERT INTO users (firstName, lastName, hash, email, phone) VALUES
('John', 'Doe', 'hash1', 'johndoe@example.com', '1234567890'),
('Jane', 'Smith', 'hash2', 'janesmith@example.com', '9876543210'),
('Michael', 'Johnson', 'hash3', 'michaeljohnson@example.com', '5555555555');

INSERT INTO associations (name, email, pwd) VALUES
('Cooking Club', 'cookingclub@example.com', 'password123'),
('Afterwork Group', 'afterworkgroup@example.com', 'password456'),
('Sports Team', 'sportsteam@example.com', 'password789');

INSERT INTO materials (name, description, barcode, number, associationId) VALUES
('Cooking Utensils', 'Various utensils for cooking', '321', 10, 1),
('Kasserol', 'Used to bonk people in some games', '456321987', 500, 1),
('Projector', 'High-quality projector for presentations', '123', 1, 2),
('Soccer Balls', 'Balls for playing soccer', '45678', 5, 3);

INSERT INTO transactions (materialId, userId, number, date) VALUES
(1, 1, 2, NOW()),
(2, 2, 1, NOW()),
(1, 2, 5, NOW()),
(3, 3, 3, NOW());
