CREATE DATABASE IF NOT EXISTS `eduhacks`;

USE `eduhacks`;
CREATE TABLE IF NOT EXISTS users (
    iduser int NOT NULL AUTO_INCREMENT,
    mail varchar(40) NOT NULL UNIQUE,
    username varchar(16) NOT NULL UNIQUE,
    passHash varchar(60) NOT NULL,
    userFirstName varchar(60),
    userLastName varchar(120),
    creationDate datetime,
    removeDate datetime,
    lastSignIn datetime,
    active tinyint(1) DEFAULT 0,
    `activationDate` DATETIME, 
	`activationCode` CHAR(64),
	`resetPassExpiry` DATETIME, 
	`resetPassCode` CHAR(64),
    PRIMARY KEY (iduser)
);

CREATE TABLE IF NOT EXISTS `reptes` ( 
`idrepte` int NOT NULL AUTO_INCREMENT, 
`titol` varchar(50) UNIQUE NOT NULL, 
`descripcio` varchar(255) NOT NULL, 
`puntuacio` INT NOT NULL, 
`flag` varchar(50) NOT NULL, 
`dataPublicacio` datetime NOT NULL, 
`iduser` INT NOT NULL,
`archivo` varchar(50) NOT NULL, 
`ruta` varchar(50) NULL, 
PRIMARY KEY(idrepte));

CREATE TABLE IF NOT EXISTS categories (
    `idcategoria` int NOT NULL AUTO_INCREMENT,
    `hashtag` varchar(40) NOT NULL UNIQUE,
    primary key (idcategoria)
);

CREATE TABLE IF NOT EXISTS valida (
    `iduser` int NOT NULL,
    `idrepte` int NOT NULL,
    primary key (iduser,idrepte),
    FOREIGN KEY (iduser) REFERENCES users(iduser),
    FOREIGN KEY (idrepte) REFERENCES reptes(idrepte)

);

CREATE TABLE IF NOT EXISTS pertany (
    `idcategoria` int NOT NULL,
    `idrepte` int NOT NULL,
    primary key (idcategoria,idrepte),
    FOREIGN KEY (idcategoria) REFERENCES categories(idcategoria),
    FOREIGN KEY (idrepte) REFERENCES reptes(idrepte)
);

CREATE TABLE IF NOT EXISTS `ranking` (
  `user` varchar(50) NOT NULL,
  `puntuacio` int NOT NULL,
   PRIMARY KEY (user)
);