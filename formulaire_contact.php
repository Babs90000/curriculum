<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Formulaire de contact</h1>
    </header>
    <div class="container mobile">
        <form action="envoyer_contact.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message :</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="form-group">
            <label for="attachment">Pièce jointe :</label>
            <input type="file" class="form-control" id="attachment" name="attachment" accept="">
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        <a class="btn-retour" href="index.php">Retour à la page d'accueil</a>
    </div>
    <footer>
        <a href="formulaire_contact.php">Formulaire de contact</a><br>
        <a href="#">Mes réalisations</a>
        <p><i class="bi bi-linkedin"></i> <a href="https://www.linkedin.com/in/babou-camara-diaby/">LinkedIn</a></p>
        <p><i class="bi bi-github"></i> <a href="https://github.com/Babs90000">GitHub</a></p>
        <a href="login.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
        <p>&copy; <?php echo date("Y"); ?> Babou CAMARA-DIABY. Tous droits réservés.</p>
    </footer>
</body>
</html>