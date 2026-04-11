<?php
    require_once "./database.php";
    require_once "./compteur.php";

    $shortUrlDisplay = $_SESSION['success'] ?? null;
    $errorMessage = $_SESSION['error'] ?? null;

    // Suppression des messages de la session pour qu'ils ne s'affichent qu'une fois
    unset($_SESSION['success'], $_SESSION['error']);

    // --- LOGIQUE DE REDIRECTION ---
    // Si un code est fourni en paramètre GET, on redirige immédiatement
    if (isset($_GET['code']) && !empty($_GET['code'])) {
        $code = $_GET['code'];
        $stmt = $dbb->prepare("SELECT long_url FROM url WHERE court_url = :code");
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            header("Location: " . $result['long_url']);
            exit();
        } else {
            $errorMessage = "URL raccourcie non trouvée.";
        }
    }

    // --- LOGIQUE DE CRÉATION (POST) ---
    if (isset($_POST['url']) && !empty($_POST['url'])) {
        $url = $_POST['url'];
        
        // Génération simplifiée avec crypt() et nettoyage (que de l'alphanumérique)
        $hash = crypt($url, rand());
        $newUrlCode = substr(preg_replace('/[^a-zA-Z0-9]/', '', $hash), 0, 8);

        try {
            $stmt = $dbb->prepare("INSERT INTO url (long_url, court_url) VALUES (:url, :newUrl)");
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            $stmt->bindParam(':newUrl', $newUrlCode, PDO::PARAM_STR);
            $stmt->execute();
            
            $_SESSION['success'] = $newUrlCode;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la création du lien.";
        }
        
        // Redirection systématique (Pattern PRG)
        header("Location: index.php");
        exit();
    }

    // Déterminer l'URL de base pour l'affichage
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    $baseUrl = $protocol . "://" . $host . rtrim($path, '/\\') . "/";

    include "./header.php";
?>

<body>
    <header class="navbar">
        <nav role="navigation">
            <a href="#">Accueil</a>
            <a href="#">Nos actus</a>
            <a href="#">Nous contacter</a>
        </nav>
    </header>

    <main>
        <h1>Shortify Your Links</h1>

        <div class="first">
            <form action="index.php" method="POST">
                <label for="url-input">Entrez votre lien :</label>
                <input type="text" id="url-input" name="url" placeholder="https://www.exemple.com" required>
                
                <button type="submit">Générer le lien court</button>
            </form>

            <?php if ($errorMessage): ?>
                <div class="result-box" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3);">
                    <p style="color: #ef4444;"><strong>Erreur :</strong> <?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($shortUrlDisplay): ?>
                <div class="result-box">
                    <p><strong>Succès ! Votre lien est prêt :</strong></p>
                    <code class="short-url" id="shortened-link"><?php echo $baseUrl . "?code=" . htmlspecialchars($shortUrlDisplay); ?></code>
                    <p style="font-size: 0.9em; color: var(--text-muted);">Copiez ce lien et collez-le dans votre navigateur.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>Ce service a été utilisé <span id="visites"><?php echo $visites; ?></span> fois.</p>
        <div class="logos">
            <img src="./images/universite-paris-1-pantheon-sorbonne.webp" alt="logo Paris1">
            <img src="./images/Banijay-France.webp" alt="logo Banijay">
            <img src="./images/BMW_indigo.jpg" alt="logo BMW">
            <img src="./images/Audiens.png" alt="logo Audiens">
        </div>
    </footer>
</body>
</html>