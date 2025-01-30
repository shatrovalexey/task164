CREATE TABLE `task163`.`record` (
  `name` VARCHAR(60) NOT NULL,
  `phone` CHAR(11) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`phone`),
  UNIQUE INDEX `email` (`email` ASC) INVISIBLE);
