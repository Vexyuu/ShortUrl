<?php

function generateShortCode($url) {
    $hash = crypt($url, rand());
    return substr($hash, 0, 8);
}

function buildShortUrl($code) {
    $baseUrl = "http://localhost/ShortUrl/index.php";
    return $baseUrl . "?code=" . htmlspecialchars($code);
}

function redirectToUrl($code, $dbb) {
    $code = trim($code);
    $stmt = $dbb->prepare("SELECT long_url FROM url WHERE court_url = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        header("Location: " . $result['long_url']);
        exit();
    }
}

function createShortUrl($url, $dbb) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ['error' => "Veuillez entrer une URL valide."];
    }
    
    $newUrlCode = generateShortCode($url);
    
    try {
        $stmt = $dbb->prepare("INSERT INTO url (long_url, court_url) VALUES (:url, :newUrl)");
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':newUrl', $newUrlCode, PDO::PARAM_STR);
        $stmt->execute();
        return ['success' => $newUrlCode];
    } catch (PDOException $e) {
        return ['error' => "Erreur lors de la création du lien."];
    }
}
