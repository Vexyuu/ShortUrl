<?php
//Mettre un compteur qui compte le nombre d’utilisateur de la page depuis le dernier lancement serveur.
session_start();

if (!isset($_SESSION['visites'])) {
    $_SESSION['visites'] = 1;
} else {
    $_SESSION['visites']++;
}
$visites = $_SESSION['visites'];
?>