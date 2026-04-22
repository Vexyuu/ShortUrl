<?php
$host = "localhost";
$username = "root";
// En production, le mdp doit être secret et stocké dans une variable d'environnement ou un fichier .env non indexé pour éviter le "hardcoding"
$password = "Projets-TD-INF7-Securise!"; // Vide si pas de mdp | SonarQube oblige un mdp fort pour la qualimétrie du code A
$database = "link";

try {
    $dbb = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br>";
    die();
}
