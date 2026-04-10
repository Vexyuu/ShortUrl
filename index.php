<?php
    session_start();
    include "./Header.php";
    include "./database.php";

    //permet de changer un truc long en un truc court : crypt($url, rand());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $url = $_POST['url'];

        // Insérer URL dans la bdd
    };

    // function longToCrypt($url, rand()) {

    // };
?>

<html>
<body>
    <head> 
        <link rel =  "stylesheet" href = "index.css">
    </head> 

<h1> Raccourcisseur de lien </h1>

    <form action="index.php" method="POST">
        <label> Entrez votre lien : </label>
        <input type = "text" name = "url" placeholder = "www.exemple.com" required>
        
        <button type="submit">Envoyer</button>
    </form>

    <footer>
    <p> Ce site est utilisé par : </p>
    <img src = "https://www.limonadeandco.fr/wp-content/uploads/2019/09/Logo-Universite-Sorbonne.png" alt = "logo Paris1">
    <img src = "Images/Logo-Universite-Sorbonne.jpeg" alt = "logo Paris1">
    <img src = "https://ecran-total.fr/wp-content/uploads/2024/10/Banijay-France-logo-768x768.webp" alt = "logo Banijay">
    <img src = "https://www.pagesjaunes.fr/media/agc/83/0f/30/00/00/27/2d/3f/b5/9a/6977830f300000272d3fb59a/6977830f300000272d3fb59b.jpg" alt = "logo BMW">
    <img src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQf_xXwSqBudM6GfgctdkZPLOpUTw2VJAplDQ&s" alt = "logo Audiens">
</footer>
</body>
</html>