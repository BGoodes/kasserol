CREATE TABLE `kasserol_db`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

INSERT INTO users (username, password, email) VALUES ('admin', 'admin', 'admin');
