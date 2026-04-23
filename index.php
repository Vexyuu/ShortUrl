<?php
    // Chargement des fichiers externes
    require_once "./includes/database.php";
    require_once "./includes/compteur.php";
    require_once "./utils/shorturl.php";

    $shortUrlDisplay = null;
    $errorMessage = null;

    if (isset($_GET['success']) && !empty($_GET['success'])) {
        $shortUrlDisplay = $_GET['success'];
    }

    if (isset($_GET['q']) && !empty($_GET['q'])) {
        redirectToUrl($_GET['q'], $dbb);
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

    $history = getHistory($dbb, 5);

    include_once "./includes/header.php";
?>

<body>
    <header class="navbar">
        <nav role="navigation" class="container">
            <a href="#">Accueil</a>
            <a href="#">Nos actus</a>
            <a href="#">Nous contacter</a>
        </nav>
    </header>

    <main class="container">
        <h1>Raccourcissez vos liens</h1>
        <h3>Entrez ci-dessous un lien que vous trouvez trop long afin que notre raccourcisseur vous aide !</h3>

        <div class="card">
            <form action="index.php" method="POST">
                <input type="text" id="url-input" name="url" placeholder="https://www.exemple.com" required>
                <button type="submit">Générer le lien court</button>
            </form>

            <?php if ($errorMessage): ?>
                <div class="result-box error">
                    <p><strong>Erreur :</strong> <?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($shortUrlDisplay): ?>
                <div class="result-box success">
                    <p><strong>Succès ! Voici le lien court :</strong></p>
                    <?php $urlComplete = buildShortUrl($shortUrlDisplay); ?>
                    <a href="<?= htmlspecialchars($urlComplete) ?>" target="_blank"><?= htmlspecialchars($urlComplete) ?></a>
                    <p style="font-size: 0.8rem; margin-top: 15px; opacity: 0.7;">Vous pouvez partager ce lien n'importe où.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($history): ?>
            <div class="card history">
                <h2>Derniers liens créés</h2>
                    <?php foreach ($history as $row): ?>
                        <div class="history-item">
                            <?php echo htmlspecialchars($row['long_url']); ?>
                            <span class="arrow">:</span>
                            <?php $urlComplete = buildShortUrl($row['court_url']); ?>
                            <a href="<?= htmlspecialchars($urlComplete) ?>" target="_blank"><?= htmlspecialchars($urlComplete) ?></a>
                        </div>
                    <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="logos">
            <p>Ce service a été utilisé <span id="visites"><?php echo $nb_visites; ?></span> fois par les employés des entreprises suivantes : </p>
            <div class="logo-grid">
                <img src="./images/universite-paris-1-pantheon-sorbonne.webp" alt="logo Paris1">
                <img src="./images/Banijay-France.webp" alt="logo Banijay">
                <img src="./images/BMW_indigo.jpg" alt="logo BMW">
                <img src="./images/Audiens.png" alt="logo Audiens">
            </div>
        </div>
    </main>

    <footer>
        
    </footer>
    
</body>
</html>
