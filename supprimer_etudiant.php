<?php
require_once '../includes/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalide");
}

$id = (int)$_GET['id'];

// Récupérer les données de l'étudiant
$stmt = $pdo->prepare("SELECT u.nom, u.prenom, e.matricule FROM utilisateur u JOIN etudiant e ON u.id = e.id WHERE u.id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    die("Étudiant non trouvé.");
}

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
    try {
        // Suppression dans utilisateur (le reste sera supprimé en cascade)
        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        $success = true;
    } catch (Exception $e) {
        $errors[] = "Erreur lors de la suppression : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Suppression d’un étudiant</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">L’étudiant a été supprimé avec succès.</div>
        <a href="etudiants.php" class="btn btn-primary">Retour à la liste</a>
    <?php else: ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <div class="alert alert-warning">
            <p>⚠ Êtes-vous sûr de vouloir supprimer l’étudiant <strong><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></strong> (Matricule : <?= htmlspecialchars($etudiant['matricule']) ?>) ?</p>
            <p><strong>Toutes ses données associées seront supprimées définitivement.</strong></p>
        </div>

        <form method="POST">
            <input type="hidden" name="confirm" value="oui">
            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
            <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>