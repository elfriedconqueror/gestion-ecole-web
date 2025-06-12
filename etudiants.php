<?php
require_once '../includes/database.php';
require_once '../models/Etudiant.php';

$etudiantModel = new Etudiant($pdo);
$etudiants = $etudiantModel->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Liste des étudiants</h2>
    <a href="ajouter_etudiant.php" class="btn btn-success mb-3">Ajouter un étudiant</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Genre</th>
                <th>Matricule</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($etudiants as $etu): ?>
            <tr>
                <td><?= htmlspecialchars($etu['id']) ?></td>
                <td><?= htmlspecialchars($etu['nom']) ?></td>
                <td><?= htmlspecialchars($etu['prenom']) ?></td>
                <td><?= htmlspecialchars($etu['genre']) ?></td>
                <td><?= htmlspecialchars($etu['matricule']) ?></td>
                <td><?= htmlspecialchars($etu['date_inscription']) ?></td>
                <td>
                    <a href="modifier_etudiant.php?id=<?= $etu['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                    <form method="post" action="../controllers/EtudiantController.php" style="display:inline;" onsubmit="return confirm('Confirmer la suppression ?');">
                        <input type="hidden" name="id" value="<?= $etu['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                    <a href="details_etudiant.php?id=<?= $etu['id'] ?>" class="btn btn-sm btn-info">Détails</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>