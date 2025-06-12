<?php
require_once '../includes/database.php';

$classes = $pdo->query("SELECT id, nom, niveau FROM classe ORDER BY niveau, nom")->fetchAll(PDO::FETCH_ASSOC);

$id_classe = $_GET['id_classe'] ?? null;
$annee_scolaire = $_GET['annee_scolaire'] ?? date('Y') . '-' . (date('Y') + 1);

$etudiants = [];

if ($id_classe && $annee_scolaire) {
    $stmt = $pdo->prepare("
        SELECT u.id, u.nom, u.prenom, e.matricule
        FROM inscription i
        JOIN etudiant e ON i.id_etudiant = e.id
        JOIN utilisateur u ON e.id = u.id
        WHERE i.id_classe = ? AND i.annee_scolaire = ?
        ORDER BY u.nom, u.prenom
    ");
    $stmt->execute([$id_classe, $annee_scolaire]);
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étudiants par classe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Liste des étudiants par classe</h2>

    <form method="GET" class="row g-3 mt-3 mb-4">
        <div class="col-md-6">
            <label for="id_classe" class="form-label">Classe</label>
            <select name="id_classe" id="id_classe" class="form-select" required>
                <option value="">-- Sélectionner une classe --</option>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= $classe['id'] ?>" <?= ($classe['id'] == $id_classe) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($classe['niveau'] . ' - ' . $classe['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="annee_scolaire" class="form-label">Année scolaire</label>
            <input type="text" name="annee_scolaire" id="annee_scolaire" class="form-control" placeholder="2024-2025" value="<?= htmlspecialchars($annee_scolaire) ?>" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Afficher</button>
        </div>
    </form>

    <?php if ($id_classe && $etudiants): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $index => $etudiant): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
                            <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                            <td>
                                <a href="modifier_etudiant.php?id=<?= $etudiant['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="supprimer_etudiant.php?id=<?= $etudiant['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($id_classe): ?>
        <div class="alert alert-info">Aucun étudiant trouvé pour cette classe en <?= htmlspecialchars($annee_scolaire) ?>.</div>
    <?php endif; ?>
</div>
</body>
</html>