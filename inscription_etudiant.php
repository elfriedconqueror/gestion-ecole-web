<?php
// Connexion à la base de données
require_once("../config/db.php");

// Récupération des classes pour la liste déroulante
$classes = [];
$result = $conn->query("SELECT id, nom FROM classe");
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Étudiant</title>
    <link rel="stylesheet" href="../gerer_enseignant/styles.css">
    <style>
        .form-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Formulaire d'Inscription Étudiant</h2>
    <div class="container">
        <form action="traitement_inscription.php" method="POST" enctype="multipart/form-data">
            <div class="form-section">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" required>
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" required>
                </div>
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" required>
                </div>
                <div class="form-group">
                    <label>Lieu de naissance</label>
                    <input type="text" name="lieu_naissance" required>
                </div>
                <div class="form-group">
                    <label>Adresse email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" required>
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" name="adresse" required>
                </div>
                <div class="form-group">
                    <label>Photo (4x4)</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label>Classe</label>
                    <select name="classe_id" required>
                        <option value="">-- Choisir une classe --</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= $classe['id'] ?>"><?= $classe['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nom du parent</label>
                    <input type="text" name="parent_nom" required>
                </div>
                <div class="form-group">
                    <label>Téléphone du parent</label>
                    <input type="text" name="parent_tel" required>
                </div>
                <div class="form-group">
                    <label>Email du parent</label>
                    <input type="email" name="parent_email" required>
                </div>

                <div class="form-group">
                    <label>Montant payé (FCFA)</label>
                    <input type="number" name="montant" required>
                </div>
                <div class="form-group">
                    <label>Mode de paiement</label>
                    <select name="mode_paiement" required>
                        <option value="">-- Choisir --</option>
                        <option>Espèces</option>
                        <option>Orange Money</option>
                        <option>Mobile Money</option>
                        <option>Chèque</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="submit-btn">Enregistrer</button>
        </form>
    </div>
</body>
</html>