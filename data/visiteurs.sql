CREATE DATABASE visiteurs;
USE visiteurs;

CREATE TABLE visiteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visites INT NOT NULL
);

INSERT INTO visiteurs (visites) VALUES (0);
