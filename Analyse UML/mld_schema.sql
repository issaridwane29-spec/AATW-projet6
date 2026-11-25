-- MLD / schéma physique (MySQL) déduit du MCD
CREATE DATABASE IF NOT EXISTS qr_platform CHARACTER SET utf8mb4;
USE qr_platform;

CREATE TABLE utilisateur (
    id_user          INT AUTO_INCREMENT PRIMARY KEY,
    nom              VARCHAR(100) NOT NULL,
    email            VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe     VARCHAR(255) NOT NULL,
    role             ENUM('UTILISATEUR','ADMIN') NOT NULL DEFAULT 'UTILISATEUR',
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE personnalisation (
    id_perso       INT AUTO_INCREMENT PRIMARY KEY,
    forme          VARCHAR(50)  DEFAULT 'carre',
    couleur_fond   VARCHAR(7)   DEFAULT '#FFFFFF',
    couleur_points VARCHAR(7)   DEFAULT '#000000',
    logo           VARCHAR(255),
    cadre          VARCHAR(50)
);

CREATE TABLE qr_codes (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    contenu          TEXT NOT NULL,
    type             ENUM('URL','TEXTE','VCARD','WIFI','EMAIL') NOT NULL DEFAULT 'URL',
    code_court       VARCHAR(20) NOT NULL UNIQUE,
    nb_scans_max     INT NOT NULL DEFAULT 0,          -- 0 = illimité
    nb_scans_actuel  INT NOT NULL DEFAULT 0,
    statut           ENUM('ACTIF','INACTIF','EXPIRE') NOT NULL DEFAULT 'ACTIF',
    date_creation    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_expiration  DATETIME NULL,
    id_user          INT NOT NULL,
    id_perso         INT NOT NULL UNIQUE,
    FOREIGN KEY (id_user)  REFERENCES utilisateur(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_perso) REFERENCES personnalisation(id_perso)
);

CREATE TABLE scans (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    qr_id       INT NOT NULL,
    scanned_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_address  VARCHAR(45) NOT NULL,
    country     VARCHAR(80),
    city        VARCHAR(80),
    FOREIGN KEY (qr_id) REFERENCES qr_codes(id) ON DELETE CASCADE
);

CREATE INDEX idx_scans_scanned_at ON scans(scanned_at);
CREATE INDEX idx_scans_zone       ON scans(country, city);
CREATE INDEX idx_scans_ip         ON scans(ip_address);
