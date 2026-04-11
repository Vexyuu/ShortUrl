<?php
$host = "localhost";
$username = "root";
$password = ""; // Laisser vide si pas de mot de passe
$database = "link";

try {
    $dbb = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br>";
    die();
}
