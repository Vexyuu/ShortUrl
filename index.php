<?php
    // Chargement des fichiers externes pour la base de données et les statistiques.
    require_once "./database.php";
    require_once "./compteur.php";

    $baseUrl = "http://localhost/ShortUrl/index.php"; // Assurez-vous que cette URL correspond à l'emplacement de votre index.php

    // Initialisation à vide des variables qui serviront pour l'affichage plus bas.
    $shortUrlDisplay = null;
    $errorMessage = null;

    // Phase 1 : Vérification si l'utilisateur arrive via un lien raccourci pour le rediriger.
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

    // Phase 2 : Traitement des données quand l'utilisateur soumet le formulaire pour créer un lien.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
        $url = $_POST['url'];
        $hash = crypt($url, rand());
        $newUrlCode = substr($hash,0, 8);

        try {
            $stmt = $dbb->prepare("INSERT INTO url (long_url, court_url) VALUES (:url, :newUrl)");
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            $stmt->bindParam(':newUrl', $newUrlCode, PDO::PARAM_STR);
            $stmt->execute();
            
            $shortUrlDisplay = $newUrlCode;
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de la création du lien.";
        }
    }

    // Chargement de la balise <head> et du début de la structure HTML.
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
        <h3> Entrez ci-dessous un lien que vous trouvez trop long afin que notre raccourcisseur vous aide ! </h3>

            
            <form action="index.php" method="POST">
                <!--<label for="url-input">Entrez votre lien :</label>-->
                <input type="text" id="url-input" name="url" placeholder="https://www.exemple.com" required>
                <br/>
                <button type="submit">Générer le lien court</button>
            </form>

            <?php if ($errorMessage): ?>
                <div class="result-box" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3);">
                    <p style="color: #ef4444;"><strong>Erreur :</strong> <?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($shortUrlDisplay): ?>
                <div class="result-box">
                    <p>
                    <strong>Succès ! Votre lien est prêt : <br>
                    <a href="<?php echo $baseUrl . "?code=" . htmlspecialchars($shortUrlDisplay); ?>" target="_blank" class="short-url" id="shortened-link"><?php echo $baseUrl . "?code=" . htmlspecialchars($shortUrlDisplay); ?></a>
                    </strong>
                    </p>
                    <p style="font-size: 0.9em; color: var(--text-muted);">Vous pouvez maintenant partager ce lien.</p>
                </div>
            <?php endif; ?>

            <hr>

        <div class="logos">
            <p>Ce service a été utilisé <span id="visites"><?php echo $visites; ?></span> fois par les employés des entreprises suivantes : </p>
            <img src="./images/universite-paris-1-pantheon-sorbonne.webp" alt="logo Paris1">
            <img src="./images/Banijay-France.webp" alt="logo Banijay">
            <img src="./images/BMW_indigo.jpg" alt="logo BMW">
            <img src="./images/Audiens.png" alt="logo Audiens">
        </div>
    </main>

    <footer>
        
    </footer>
    
</body>
</html>