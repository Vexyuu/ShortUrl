<?php
    // Chargement des fichiers externes
    require_once "./database.php";
    require_once "./compteur.php";
    require_once "./utils/shorturl.php";

    $shortUrlDisplay = null;
    $errorMessage = null;

    if (isset($_GET['success']) && !empty($_GET['success'])) {
        $shortUrlDisplay = $_GET['success'];
    }

    if (isset($_GET['code']) && !empty($_GET['code'])) {
        redirectToUrl($_GET['code'], $dbb);
        $errorMessage = "URL raccourcie non trouvée.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
        $response = createShortUrl(trim($_POST['url']), $dbb);
        
        if (isset($response['error'])) {
            $errorMessage = $response['error'];
        } else {
            header("Location: index.php?success=" . urlencode($response['success']));
            exit();
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
                <div class="result-box error">
                    <p><strong>Erreur :</strong> <?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($shortUrlDisplay): ?>
                <div class="result-box success">
                    <p><strong>Succès ! Votre lien est prêt :</strong></p>
                    <a href="<?php echo buildShortUrl($shortUrlDisplay); ?>" target="_blank"><?php echo buildShortUrl($shortUrlDisplay); ?></a>
                    <p style="font-size: 0.9em; margin-top: 10px;">Vous pouvez partager ce lien.</p>
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