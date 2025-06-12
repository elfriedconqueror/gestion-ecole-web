<?php
require_once '../includes/database.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $matricule = trim($_POST['matricule']);
    $date_inscription = $_POST['date_inscription'];

    // Vérification email déjà utilisé
    $check = $pdo->prepare("SELECT id FROM utilisateur WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        $errors[] = "Cet email est déjà utilisé.";
    }

    if (empty($errors)) {
        try {
            // Démarrer une transaction
            $pdo->beginTransaction();

            // Insertion dans la table utilisateur
            $stmt = $pdo->prepare("INSERT INTO utilisateur 
                (nom, prenom, date_naissance, genre, adresse, telephone, email, password, type_utilisateur) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Etudiant')");
            $stmt->execute([$nom, $prenom, $date_naissance, $genre, $adresse, $telephone, $email, $password]);
            $utilisateur_id = $pdo->lastInsertId();

            // Insertion dans la table etudiant
            $stmt2 = $pdo->prepare("INSERT INTO etudiant (id, matricule, date_inscription) VALUES (?, ?, ?)");
            $stmt2->execute([$utilisateur_id, $matricule, $date_inscription]);

            $pdo->commit();
            $success = true;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Ajouter un étudiant</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Étudiant ajouté avec succès.</div>
        <a href="/gestion_ecole/gerer_etudiant/views/ajouter_etudiants.php" class="btn btn-primary">Retour à la liste</a>
    <?php else: ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <form method="POST" class="row g-3 mt-3">
            <div class="col-md-6">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" required>
            </div>
            <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" required>
            </div>
            <div class="col-md-4">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" name="date_naissance" required>
            </div>
            <div class="col-md-4">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-select" name="genre" required>
                    <option value="">-- Choisir --</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone">
            </div>
            <div class="col-12">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" name="adresse">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="col-md-6">
                <label for="matricule" class="form-label">Matricule</label>
                <input type="text" class="form-control" name="matricule" required>
            </div>
            <div class="col-md-6">
                <label for="date_inscription" class="form-label">Date d'inscription</label>
                <input type="date" class="form-control" name="date_inscription" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Ajouter</button>
                <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>