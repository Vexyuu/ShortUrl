<?php
require_once 'database.php';

$queryUpdate = $dbb->prepare("UPDATE visiteurs SET visites = visites + 1");
$queryUpdate->execute();

$querySelect = $dbb->prepare("SELECT visites FROM visiteurs");
$querySelect->execute();
$resultat = $querySelect->fetch();

$nb_visites = $resultat['visites'];
