<?php
session_start();
if(!isset($_SESSION['user_id'])){
    echo "Vous n'êtes pas autorisé à accéder à cette page.";
    header("Reresh: 3; url=connexion.php");
}
require 'config.php';

// Ajouter une nouvelle compétence
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $skill = $_POST['skill'];
    $level = $_POST['level'];

    try {
        $stmt = $bdd->prepare("INSERT INTO skills (skill, level) VALUES (:skill, :level)");
        $stmt->bindValue(':skill', $skill);
        $stmt->bindValue(':level', $level);
        $stmt->execute();
        echo "Nouvelle compétence ajoutée avec succès.";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Supprimer une compétence
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $bdd->prepare("DELETE FROM skills WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        echo "Compétence supprimée avec succès.";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupérer les compétences
try {
    $stmt = $bdd->prepare("SELECT * FROM skills");
    $stmt->execute();
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Récupérer la compétence à modifier
$skill_to_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    try {
        $stmt = $bdd->prepare("SELECT * FROM skills WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $skill_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Mettre à jour la compétence
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $skill = $_POST['skill'];
    $level = $_POST['level'];

    try {
        $stmt = $bdd->prepare("UPDATE skills SET skill = :skill, level = :level WHERE id = :id");
        $stmt->bindValue(':skill', $skill);
        $stmt->bindValue(':level', $level);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        echo "Compétence mise à jour avec succès.";
        header("Location: gestion_competences.php");
        exit();
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
    <title>Gestion des Compétences</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Compétences</h1>
    </header>
    <div class="container">
        <section class="section_adminetco">
            <h2><?php echo $skill_to_edit ? 'Modifier' : 'Ajouter'; ?> une compétence</h2>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $skill_to_edit['id'] ?? ''; ?>">
                <label for="skill">Compétence:</label>
                <input type="text" id="skill" name="skill" value="<?php echo htmlspecialchars($skill_to_edit['skill'] ?? ''); ?>" required>
                <br>
                <label for="level">Niveau:</label>
                <input type="number" id="level" name="level" value="<?php echo htmlspecialchars($skill_to_edit['level'] ?? ''); ?>" required>
                <br>
                <input type="submit" name="<?php echo $skill_to_edit ? 'update' : 'add'; ?>" value="<?php echo $skill_to_edit ? 'Mettre à jour' : 'Ajouter'; ?>">
            </form>
            <br>
        </section>

        <section class="section_adminetco">
            <h2>Liste des compétences</h2>
            <table>
                <thead>
                    <tr>
                        <th>Compétence</th>
                        <th>Niveau</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skills as $skill): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($skill['skill']); ?></td>
                            <td><?php echo htmlspecialchars($skill['level']); ?></td>
                            <td>
                                <a href="gestion_competences.php?edit=<?php echo $skill['id']; ?>">Modifier</a>
                                <a href="gestion_competences.php?delete=<?php echo $skill['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
            <a class="btn-retour" href="espace_admin.php">Retour vers l'espace admin</a>
    </div>
</body>
</html>