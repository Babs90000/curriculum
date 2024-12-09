<?php
session_start();
if(!isset($_SESSION['user_id'])){
    echo "Vous n'êtes pas autorisé à accéder à cette page.";
    header("Reresh: 3; url=connexion.php");
}
require 'config.php';

// Ajouter une nouvelle expérience
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $entreprise = $_POST['entreprise'];
    $poste = $_POST['poste'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    try {
        $stmt = $bdd->prepare("INSERT INTO experiences (Entreprise, poste, description, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$entreprise, $poste, $description, $date_debut, $date_fin]);
        echo "Nouvelle expérience ajoutée avec succès.";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Supprimer une expérience
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $bdd->prepare("DELETE FROM experiences WHERE id = ?");
        $stmt->execute([$id]);
        echo "Expérience supprimée avec succès.";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupérer les expériences
try {
    $stmt = $bdd->prepare("SELECT * FROM experiences");
    $stmt->execute();
    $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Récupérer l'expérience à modifier
$experience_to_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    try {
        $stmt = $bdd->prepare("SELECT * FROM experiences WHERE id = ?");
        $stmt->execute([$id]);
        $experience_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Mettre à jour l'expérience
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $entreprise = $_POST['entreprise'];
    $poste = $_POST['poste'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    try {
        $stmt = $bdd->prepare("UPDATE experiences SET Entreprise = ?, poste = ?, description = ?, date_debut = ?, date_fin = ? WHERE id = ?");
        $stmt->execute([$entreprise, $poste, $description, $date_debut, $date_fin, $id]);
        echo "Expérience mise à jour avec succès.";
        header("Location: gestion_experiences.php");
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
    <title>Gestion des Expériences</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Expériences</h1>
    </header>
    <div class="container">
        <section class="section_adminetco">
            <h2><?php echo $experience_to_edit ? 'Modifier' : 'Ajouter'; ?> une expérience</h2>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $experience_to_edit['id'] ?? ''; ?>">
                <label for="entreprise">Entreprise:</label>
                <input type="text" id="entreprise" name="entreprise" value="<?php echo htmlspecialchars($experience_to_edit['Entreprise'] ?? ''); ?>" required>
                <br>
                <label for="poste">Poste:</label>
                <input type="text" id="poste" name="poste" value="<?php echo htmlspecialchars($experience_to_edit['poste'] ?? ''); ?>" required>
                <br>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($experience_to_edit['description'] ?? ''); ?></textarea>
                <br>
                <label for="date_debut">Date de début:</label>
                <input type="date" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($experience_to_edit['date_debut'] ?? ''); ?>" required>
                <br>
                <label for="date_fin">Date de fin:</label>
                <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($experience_to_edit['date_fin'] ?? ''); ?>" required>
                <br>
                <input type="submit" name="<?php echo $experience_to_edit ? 'update' : 'add'; ?>" value="<?php echo $experience_to_edit ? 'Mettre à jour' : 'Ajouter'; ?>">
            </form>
        </section>

        <section class="section_adminetco">
            <h2>Liste des expériences</h2>
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Poste</th>
                        <th>Description</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($experiences as $experience): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($experience['Entreprise']); ?></td>
                            <td><?php echo htmlspecialchars($experience['poste']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($experience['description'])); ?></td>
                            <td><?php echo htmlspecialchars($experience['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($experience['date_fin']); ?></td>
                            <td>
                                <a href="gestion_experiences.php?edit=<?php echo $experience['id']; ?>">Modifier</a>
                                <a href="gestion_experiences.php?delete=<?php echo $experience['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette expérience ?');">Supprimer</a>
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