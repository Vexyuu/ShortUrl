<?php
    require_once "./database.php";
    require_once "./compteur.php";

    $shortUrlDisplay = $_SESSION['success'] ?? null;
    $errorMessage = $_SESSION['error'] ?? null;

    unset($_SESSION['success'], $_SESSION['error']);

    // --- REDIRECTION ---
    if (!empty($_GET['code'])) {
        $stmt = $dbb->prepare("SELECT long_url FROM url WHERE court_url = ?");
        $stmt->execute([$_GET['code']]);
        if ($longUrl = $stmt->fetchColumn()) {
            header("Location: $longUrl");
            exit;
        }
        $errorMessage = "URL raccourcie non trouvée.";
    }

    // --- CRÉATION (POST) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
        $url = $_POST['url'];
        $hash = crypt($url, rand());
        $newUrlCode = substr(preg_replace('/[^a-zA-Z0-9]/', '', $hash), 0, 8);

        try {
            $stmt = $dbb->prepare("INSERT INTO url (long_url, court_url) VALUES (?, ?)");
            $stmt->execute([$url, $newUrlCode]);
            $_SESSION['success'] = $newUrlCode;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la création du lien.";
        }
        
        header("Location: index.php");
        exit;
    }

    $baseUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/";
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