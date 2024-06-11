CREATE Database IF NOT EXISTS  gares;
USE gares;

CREATE TABLE IF NOT EXISTS  gare_arrivee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS  users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_as ENUM('admin','guest') DEFAULT 'guest',
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    pswd VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS  paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(255) NOT NULL,
    trajet_id INT NOT NULL,
    STATUS VARCHAR(50) NOT NULL,
    payment_date TIMESTAMP,
    date_de_depart TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Correction pour le suivi des mises à jour
);

CREATE TABLE IF NOT EXISTS  contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subjet VARCHAR(225) NOT NULL,
    fullname VARCHAR(225) NOT NULL,
    email VARCHAR(225) NOT NULL,
    message LONGTEXT NOT NULL,
	status BOOL DEFAULT FALSE,
	date_envoi TIMESTAMP
);


CREATE TABLE IF NOT EXISTS  gare_depart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

INSERT INTO gare_depart (nom, ville) VALUES
('Gare de Lyon', 'Paris'),
('Gare du Nord', 'Paris'),
('Gare Saint-Lazare', 'Paris'),
('Gare de Montparnasse', 'Paris'),
("Gare de l'Est", 'Paris'),
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
("Gare de l'Est", 'Paris'),
('Gare de Marseille-Saint-Charles', 'Marseille'),
('Gare de Lyon Part-Dieu', 'Lyon'),
('Gare de Bordeaux-Saint-Jean', 'Bordeaux'),
('Gare de Lille-Flandres', 'Lille'),
('Gare de Nantes', 'Nantes');

-- REVOIR CECI -----------------

CREATE TABLE IF NOT EXISTS  tarifs(
    id INT AUTO_INCREMENT PRIMARY KEY,
    gares_depart VARCHAR(255) NOT NULL,
    gares_arrivee VARCHAR(255) NOT NULL,
    prix INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Correction pour le suivi des mises à jour
);

INSERT INTO tarifs (gares_depart, gares_arrivee, prix)
SELECT gd.nom AS gare_depart, ga.nom AS gare_arrivee, ROUND(RAND() * 100, 2) AS prix
FROM gare_depart gd CROSS JOIN gare_arrivee ga
LEFT JOIN tarifs t ON gd.nom = t.gares_depart AND ga.nom = t.gares_arrivee
WHERE ga.nom != gd.nom
ORDER BY gd.nom, ga.nom;



-- Ajout
CREATE TABLE IF NOT EXISTS  orders(
    id INT AUTO_INCREMENT PRIMARY KEY,
    passenger longText NOT NULL,
    paiement_id TinyInt NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Correction pour le suivi des mises à jour
);
