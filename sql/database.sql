CREATE DATABASE gares;
USE gares;

CREATE TABLE gare_arrivee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_as ENUM('admin','guest') DEFAULT 'guest',
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    pswd VARCHAR(255) NOT NULL
);

CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(255) NOT NULL,
    trajet_id INT NOT NULL,
    STATUS VARCHAR(50) NOT NULL,
    payment_date TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Correction pour le suivi des mises à jour
);

CREATE TABLE gare_depart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

INSERT INTO gare_depart (nom, ville) VALUES
('Gare de Lyon', 'Paris'),
('Gare du Nord', 'Paris'),
('Gare Saint-Lazare', 'Paris'),
('Gare de Montparnasse', 'Paris'),
('Gare de l\'Est', 'Paris'),
('Gare de Marseille-Saint-Charles', 'Marseille'),
('Gare de Lyon Part-Dieu', 'Lyon'),
('Gare de Bordeaux-Saint-Jean', 'Bordeaux'),
('Gare de Lille-Flandres', 'Lille'),
('Gare de Nantes', 'Nantes');

INSERT INTO gare_arrivee (nom, ville) VALUES
('Gare de Lyon', 'Paris'),
('Gare du Nord', 'Paris'),
('Gare Saint-Lazare', 'Paris'),
('Gare de Montparnasse', 'Paris'),
('Gare de l\'Est', 'Paris'),
('Gare de Marseille-Saint-Charles', 'Marseille'),
('Gare de Lyon Part-Dieu', 'Lyon'),
('Gare de Bordeaux-Saint-Jean', 'Bordeaux'),
('Gare de Lille-Flandres', 'Lille'),
('Gare de Nantes', 'Nantes');

-------------- REVOIR CECI -----------------

CREATE TABLE tarifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gares_depart VARCHAR(255) NOT NULL,
    gares_arrivee VARCHAR(255) NOT NULL,
    prix INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Correction pour le suivi des mises à jour
);

INSERT INTO tarifs (gares_depart, gares_arrivee,prix) VALUES
('Gare de Lyon', 'Gare du Nord',30),
('Gare du Nord', 'Gare de Lyon',40),
('Gare Saint-Lazare', 'Paris',30),
('Gare de Montparnasse', 'Paris',500),
('Gare de l\'Est', 'Paris',100),
('Gare de Marseille-Saint-Charles', 'Marseille',30),
('Gare de Lyon Part-Dieu', 'Lyon',204),
('Gare de Bordeaux-Saint-Jean', 'Bordeaux',47),
('Gare de Lille-Flandres', 'Lille',90),
('Gare de Nantes', 'Gare de l\'Est',399);
