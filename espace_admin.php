<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        echo "Vous n'êtes pas autorisé à accéder à cette page.";
        header("Reresh: 3; url=connexion.php");
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Espace Admin</h1>
        <a class="btn-deconnexion" href="deconnexion.php"><i class="bi bi-box-arrow-in-left"></i> Déconnexion</a>
    </header>
    <div class="container">
        <section class="section_adminetco">
            <h2>Gestion des Contenus</h2>
            <ul>
                <li><a href="gestion_experiences.php">Gestion des Expériences</a></li>
                <li><a href="gestion_formations.php">Gestion des Formations</a></li>
                <li><a href="gestion_competences.php">Gestion des Compétences</a></li>
            </ul>
        </section>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Babou CAMARA-DIABY. Tous droits réservés.</p>
    </footer>
</body>
</html>