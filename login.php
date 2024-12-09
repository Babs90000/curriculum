<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $bdd->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: espace_admin.php");
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <div class="container_login">
        <section>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <input type="submit" value="Se connecter">
            </form>
            <a class="btn-retour" href="index.php">Retour à la page d'accueil</a>
        </section>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Babou CAMARA-DIABY. Tous droits réservés.</p>
    </footer>
</body>
</html>