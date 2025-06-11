<?php
// Exemple de données fictives pour l’étudiant
$etudiant = [
    'nom' => 'Mouafo',
    'prenom' => 'Paul',
    'email' => 'paul.mouafo@example.com',
    'telephone' => '670123456',
    'adresse' => 'Douala, Cameroun',
    'filiere' => 'Informatique',
    'niveau' => 'Licence 3',
    'photo' => 'uploads/photos/etudiant1.jpg' // Remplacer par la vraie image ou par défaut
];

// Image par défaut
if (!file_exists($etudiant['photo'])) {
    $etudiant['photo'] = 'uploads/photos/default.png';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Informations personnelles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 12px;
        }
        .photo-profil {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="fa fa-arrow-left me-2"></i>Retour</a>
        <a href="/universite-app/app/Views/login.php" class="btn btn-outline-light">Déconnexion</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="text-primary mb-4"><i class="fa fa-user me-2"></i>Mes informations personnelles</h3>

        <!-- PHOTO -->
        <div class="text-center mb-4">
            <img src="<?= $etudiant['photo'] ?>" alt="Photo de profil" class="photo-profil mb-3">
            <form action="upload_photo.php" method="post" enctype="multipart/form-data">
                <input type="file" name="photo" accept="image/*" class="form-control mb-2" required>
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-upload me-1"></i>Changer la photo</button>
            </form>
        </div>

        <!-- INFOS -->
        <form method="post" action="sauvegarder_infos.php">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" value="<?= $etudiant['nom'] ?>" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="<?= $etudiant['prenom'] ?>" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" name="email" id="email" value="<?= $etudiant['email'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="<?= $etudiant['telephone'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" name="adresse" id="adresse" value="<?= $etudiant['adresse'] ?>" class="form-control">
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="filiere" class="form-label">Filière</label>
                    <input type="text" name="filiere" id="filiere" value="<?= $etudiant['filiere'] ?>" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="niveau" class="form-label">Niveau</label>
                    <input type="text" name="niveau" id="niveau" value="<?= $etudiant['niveau'] ?>" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-secondary"><i class="fa fa-arrow-left me-1"></i>Retour</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>