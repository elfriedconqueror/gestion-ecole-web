<?php
require_once '../includes/database.php';

$errors = [];
$success = false;

// Vérifie si l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalide");
}

$id = (int)$_GET['id'];

// Récupérer les données de l'étudiant
$stmt = $pdo->prepare("
    SELECT u.*, e.matricule, e.date_inscription
    FROM utilisateur u
    JOIN etudiant e ON u.id = e.id
    WHERE u.id = ?
");
$stmt->execute([$id]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    die("Étudiant non trouvé.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $matricule = trim($_POST['matricule']);
    $date_inscription = $_POST['date_inscription'];

    // Vérification si l'email a changé et s'il est déjà pris
    if ($email !== $etudiant['email']) {
        $check = $pdo->prepare("SELECT id FROM utilisateur WHERE email = ? AND id != ?");
        $check->execute([$email, $id]);
        if ($check->fetch()) {
            $errors[] = "Cet email est déjà utilisé par un autre utilisateur.";
        }
    }

    if (empty($errors)) {
        try {
            // Mise à jour dans utilisateur
            $stmt = $pdo->prepare("UPDATE utilisateur SET nom=?, prenom=?, date_naissance=?, genre=?, adresse=?, telephone=?, email=? WHERE id=?");
            $stmt->execute([$nom, $prenom, $date_naissance, $genre, $adresse, $telephone, $email, $id]);

            // Mise à jour dans etudiant
            $stmt2 = $pdo->prepare("UPDATE etudiant SET matricule=?, date_inscription=? WHERE id=?");
            $stmt2->execute([$matricule, $date_inscription, $id]);

            $success = true;
        } catch (Exception $e) {
            $errors[] = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Modifier un étudiant</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Étudiant modifié avec succès.</div>
        <a href="etudiants.php" class="btn btn-primary">Retour à la liste</a>
    <?php else: ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <form method="POST" class="row g-3 mt-3">
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="<?= $etudiant['date_naissance'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Genre</label>
                <select name="genre" class="form-select" required>
                    <option value="M" <?= $etudiant['genre'] === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= $etudiant['genre'] === 'F' ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($etudiant['telephone']) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($etudiant['adresse']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($etudiant['email']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Matricule</label>
                <input type="text" name="matricule" class="form-control" value="<?= htmlspecialchars($etudiant['matricule']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Date d'inscription</label>
                <input type="date" name="date_inscription" class="form-control" value="<?= $etudiant['date_inscription'] ?>" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Modifier</button>
                <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>