<?php
session_start();
if(!isset($_SESSION['user_id'])){
    echo "Vous n'êtes pas autorisé à accéder à cette page.";
    header("Reresh: 3; url=connexion.php");
}
require 'config.php';

// Ajouter une nouvelle formation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $diplome = $_POST['diplome'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    try {
        $stmt = $bdd->prepare("INSERT INTO educations (diplome, description, date_debut, date_fin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$diplome, $description, $date_debut, $date_fin]);
        echo "Nouvelle formation ajoutée avec succès.";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Supprimer une formation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $bdd->prepare("DELETE FROM educations WHERE id = ?");
        $stmt->execute([$id]);
        echo "Formation supprimée avec succès.";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupérer les formations
try {
    $stmt = $bdd->prepare("SELECT * FROM educations");
    $stmt->execute();
    $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Récupérer la formation à modifier
$education_to_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    try {
        $stmt = $bdd->prepare("SELECT * FROM educations WHERE id = ?");
        $stmt->execute([$id]);
        $education_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Mettre à jour la formation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $diplome = $_POST['diplome'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    try {
        $stmt = $bdd->prepare("UPDATE educations SET diplome = ?, description = ?, date_debut = ?, date_fin = ? WHERE id = ?");
        $stmt->execute([$diplome, $description, $date_debut, $date_fin, $id]);
        echo "Formation mise à jour avec succès.";
        header("Location: gestion_formations.php");
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
    <title>Gestion des Formations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Formations</h1>
    </header>
    <div class="container">
        <section class="section_adminetco">
            <h2><?php echo $education_to_edit ? 'Modifier' : 'Ajouter'; ?> une formation</h2>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $education_to_edit['id'] ?? ''; ?>">
                <label for="diplome">Diplôme:</label>
                <input type="text" id="diplome" name="diplome" value="<?php echo htmlspecialchars($education_to_edit['diplome'] ?? ''); ?>" required>
                <br>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($education_to_edit['description'] ?? ''); ?></textarea>
                <br>
                <label for="date_debut">Date de début:</label>
                <input type="date" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($education_to_edit['date_debut'] ?? ''); ?>" required>
                <br>
                <label for="date_fin">Date de fin:</label>
                <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($education_to_edit['date_fin'] ?? ''); ?>" required>
                <br>
                <input type="submit" name="<?php echo $education_to_edit ? 'update' : 'add'; ?>" value="<?php echo $education_to_edit ? 'Mettre à jour' : 'Ajouter'; ?>">
            </form>
        </section>

        <section class="section_adminetco">
            <h2>Liste des formations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Diplôme</th>
                        <th>Description</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($educations as $education): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($education['diplome']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($education['description'])); ?></td>
                            <td><?php echo htmlspecialchars($education['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($education['date_fin']); ?></td>
                            <td>
                                <a href="gestion_formations.php?edit=<?php echo $education['id']; ?>">Modifier</a>
                                <a href="gestion_formations.php?delete=<?php echo $education['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">Supprimer</a>
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