<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV de Babou CAMARA-DIABY</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>CV de Babou CAMARA-DIABY</h1>
        <p><i class="bi bi-geo-fill"></i> 8 cité verte 94370 Sucy-en-Brie</p>
        <a href="mailto:b.camara.diaby@outlook.com"><i class="bi bi-envelope-at-fill"></i> b.camara.diaby@outlook.com</a><br>
        <a href="tel:+33745081138"><i class="bi bi-telephone-fill"></i> 07 45 08 11 38</a>
    </header>
    <div class="container_accueil">
        <section class="profile">
            <h2>Profil</h2>
            <p>Passionné par le développement web j’ai quittémon ancien poste et le domaine de la financeafin d’entamer une reconversion professionnellecomme développeur web full stack.Je suis actuellement à la recherche d’un stageafin de gagner en expérience et apprendred’avantage dans le domaine de laprogrammation</p>
        </section>

        <?php
        // Inclure le fichier de configuration pour la connexion à la base de données
        require 'config.php';

        // Récupérer les expériences
        try {
            $stmt = $bdd->prepare("SELECT Entreprise, poste, description, date_debut, date_fin FROM experiences");
            $stmt->execute();
            $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        ?>

        <section class="experience">
            <h2>Expériences</h2><br>
            <?php
            if (count($experiences) > 0) {
                foreach ($experiences as $row) {
                    $date_debut = new DateTime($row["date_debut"]);
                    $date_fin = new DateTime($row["date_fin"]);
                    echo "<h3>" . htmlspecialchars($row["Entreprise"]) . " - " . htmlspecialchars($row["poste"]) . " (" . $date_debut->format('d/m/Y') . " - " . $date_fin->format('d/m/Y') . ")</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($row["description"])) . "</p></br>";
                }
            } else {
                echo "<p>Aucune expérience trouvée.</p></br>";
            }
            ?>
        </section>

        <?php
        // Récupérer les éducations
        try {
            $stmt = $bdd->prepare("SELECT diplome, description, date_debut, date_fin FROM educations");
            $stmt->execute();
            $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        ?>

        <section class="education">
            <h2>Formations</h2><br>
            <?php
            if (count($educations) > 0) {
                foreach ($educations as $row) {
                    $date_debut = new DateTime($row["date_debut"]);
                    $date_fin = new DateTime($row["date_fin"]);
                    echo "<h3>" . htmlspecialchars($row["diplome"]) . " (" . $date_debut->format('d/m/Y') . " - " . $date_fin->format('d/m/Y') . ")</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($row["description"])) . "</p></br>";
                }
            } else {
                echo "<p>Aucune éducation trouvée.</p></br>";
            }
            ?>
        </section>

        <?php
        // Récupérer les compétences
        try {
            $stmt = $bdd->prepare("SELECT skill, level FROM skills");
            $stmt->execute();
            $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        ?>

        <section class="skills">
            <h2>Compétences</h2><br>
            <ul>
                <?php
                if (count($skills) > 0) {
                    foreach ($skills as $row) {
                        echo "<li>" . htmlspecialchars($row["skill"]) . " (Niveau: " . htmlspecialchars($row["level"]) . ")</li>";
                    }
                } else {
                    echo "<li>Aucune compétence trouvée.</li></br>";
                }
                ?>
            </ul>
        </section>
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