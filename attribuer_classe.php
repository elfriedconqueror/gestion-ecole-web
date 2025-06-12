<?php
require_once '../includes/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalide");
}

$id_etudiant = (int)$_GET['id'];
$errors = [];
$success = false;

// Vérifier si l’étudiant existe
$stmt = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ? AND type_utilisateur = 'Etudiant'");
$stmt->execute([$id_etudiant]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    die("Étudiant non trouvé.");
}

// Récupérer les classes
$classes = $pdo->query("SELECT id, nom, niveau FROM classe ORDER BY niveau, nom")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_classe = (int)$_POST['id_classe'];
    $annee_scolaire = trim($_POST['annee_scolaire']);

    if (!$annee_scolaire) {
        $errors[] = "L'année scolaire est requise.";
    }

    if (empty($errors)) {
        try {
            // Vérifier si une inscription existe déjà
            $check = $pdo->prepare("SELECT * FROM inscription WHERE id_etudiant = ? AND annee_scolaire = ?");
            $check->execute([$id_etudiant, $annee_scolaire]);

            if ($check->fetch()) {
                // Mise à jour
                $update = $pdo->prepare("UPDATE inscription SET id_classe = ? WHERE id_etudiant = ? AND annee_scolaire = ?");
                $update->execute([$id_classe, $id_etudiant, $annee_scolaire]);
            } else {
                // Insertion
                $insert = $pdo->prepare("INSERT INTO inscription (id_etudiant, id_classe, annee_scolaire) VALUES (?, ?, ?)");
                $insert->execute([$id_etudiant, $id_classe, $annee_scolaire]);
            }

            $success = true;
        } catch (Exception $e) {
            $errors[] = "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attribuer une classe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Attribuer une classe à : <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Classe attribuée avec succès.</div>
        <a href="etudiants.php" class="btn btn-primary">Retour à la liste</a>
    <?php else: ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <form method="POST" class="mt-4 row g-3">
            <div class="col-md-6">
                <label for="id_classe" class="form-label">Classe</label>
                <select name="id_classe" id="id_classe" class="form-select" required>
                    <option value="">-- Sélectionner une classe --</option>
                    <?php foreach ($classes as $classe): ?>
                        <option value="<?= $classe['id'] ?>">
                            <?= htmlspecialchars($classe['niveau'] . ' - ' . $classe['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="annee_scolaire" class="form-label">Année scolaire</label>
                <input type="text" name="annee_scolaire" id="annee_scolaire" class="form-control" placeholder="2024-2025" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Attribuer</button>
                <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>