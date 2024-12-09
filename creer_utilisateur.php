<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $bdd->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        echo "Nouvel utilisateur créé avec succès.";
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
    <title>Créer un utilisateur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Créer un utilisateur</h1>
    </header>
    <div class="container">
        <section>
            <h2>Ajouter un nouvel utilisateur</h2>
            <form method="post" action="">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <input type="submit" value="Créer">
            </form>
        </section>
    </div>
  
</body>
</html>