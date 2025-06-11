<?php
session_start();
include("../config/db.php");

$res = mysqli_query($conn, "SELECT montantfinal FROM config_paiement ORDER BY id DESC LIMIT 1");
$config = mysqli_fetch_assoc($res);
$montantfinal = isset($config['montantfinal']) ? $config['montantfinal'] : 0;
$classes = mysqli_query($conn, "SELECT * FROM classe");

function generateMatricule($conn) {
    $prefix = "ET";
    $num_length = 7;

    $res = mysqli_query($conn, "SELECT matricule FROM etudiant ORDER BY id DESC LIMIT 1");
    $lastMatricule = $res ? mysqli_fetch_assoc($res)['matricule'] : null;

    if ($lastMatricule && preg_match('/ET(\d{7})/', $lastMatricule, $matches)) {
        $lastNum = (int)$matches[1];
        $newNum = $lastNum + 1;
    } else {
        $newNum = 1;
    }

    return $prefix . str_pad($newNum, $num_length, '0', STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $password = sha1($_POST['password']);
    $type_utilisateur = 'Etudiant';
    $date_inscription = date('Y-m-d');

    $classe_id = (int)$_POST['classe_id'];
    $annee_scolaire = trim($_POST['annee_scolaire']);
    $montant_paye = floatval($_POST['montant_paye']);
   $type_paiement = isset($_POST['type_paiement']) ? $_POST['type_paiement'] : 'Inscription';

    if (!$nom || !$prenom || !$email || !$password || !$classe_id || !$annee_scolaire || !$type_paiement) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        $checkEmail = mysqli_query($conn, "SELECT id FROM utilisateur WHERE email = '".mysqli_real_escape_string($conn, $email)."'");
        if (mysqli_num_rows($checkEmail) > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $sqlUser = "INSERT INTO utilisateur (nom, prenom, date_naissance, genre, adresse, telephone, email, password, type_utilisateur)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->bind_param("sssssssss", $nom, $prenom, $date_naissance, $genre, $adresse, $telephone, $email, $password, $type_utilisateur);
            $stmtUser->execute();
            $id_utilisateur = $stmtUser->insert_id;

            $matricule = generateMatricule($conn);

            $sqlEtudiant = "INSERT INTO etudiant (id, matricule, date_inscription) VALUES (?, ?, ?)";
            $stmtEtudiant = $conn->prepare($sqlEtudiant);
            $stmtEtudiant->bind_param("iss", $id_utilisateur, $matricule, $date_inscription);
            $stmtEtudiant->execute();

            $sqlInscription = "INSERT INTO inscription (id_etudiant, id_classe, annee_scolaire) VALUES (?, ?, ?)";
            $stmtInscription = $conn->prepare($sqlInscription);
            $stmtInscription->bind_param("iis", $id_utilisateur, $classe_id, $annee_scolaire);
            $stmtInscription->execute();

            $sqlPaiement = "INSERT INTO paiement (id_etudiant, montantfinal, montant, date_paiement, type_paiement) VALUES (?, ?, ?, NOW(), ?)";
            $stmtPaiement = $conn->prepare($sqlPaiement);
            $stmtPaiement->bind_param("idds", $id_utilisateur, $montantfinal, $montant_paye, $type_paiement);
            $stmtPaiement->execute();

            $success = "Étudiant inscrit avec succès. Matricule : $matricule";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inscription Étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Inscription Étudiant</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif (!empty($success)): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Nom:</label>
        <input type="text" name="nom" required value="<?= isset($nom) ? htmlspecialchars($nom) : '' ?>">

        <label>Prénom:</label>
        <input type="text" name="prenom" required value="<?= isset($prenom) ? htmlspecialchars($prenom) : '' ?>">

        <label>Date de naissance:</label>
        <input type="date" name="date_naissance" required value="<?= isset($date_naissance) ? htmlspecialchars($date_naissance) : '' ?>">

        <label>Genre:</label>
        <select name="genre" required>
            <option value="">--Choisir--</option>
            <option value="M" <?= (isset($genre) && $genre === 'M') ? 'selected' : '' ?>>Masculin</option>
            <option value="F" <?= (isset($genre) && $genre === 'F') ? 'selected' : '' ?>>Féminin</option>
        </select>

        <label>Adresse:</label>
        <textarea name="adresse"><?= isset($adresse) ? htmlspecialchars($adresse) : '' ?></textarea>

        <label>Téléphone:</label>
        <input type="text" name="telephone" value="<?= isset($telephone) ? htmlspecialchars($telephone) : '' ?>">

        <label>Email:</label>
        <input type="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

        <label>Mot de passe:</label>
        <input type="password" name="password" required>

        <label>Classe:</label>
        <select name="classe_id" required>
            <option value="">--Choisir--</option>
            <?php while ($c = mysqli_fetch_assoc($classes)): ?>
                <option value="<?= $c['id'] ?>" <?= (isset($classe_id) && $classe_id == $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Année scolaire (ex: 2025-2026):</label>
        <input type="text" name="annee_scolaire" placeholder="2025-2026" required value="<?= isset($annee_scolaire) ? htmlspecialchars($annee_scolaire) : '' ?>">

        <label>Montant payé (en FCFA):</label>
        <input type="number" step="0.01" name="montant_paye" min="0" required value="<?= isset($montant_paye) ? htmlspecialchars($montant_paye) : '' ?>">

        <label>Type de paiement :</label>
        <select name="type_paiement" required>
            <option value="Inscription" <?= (isset($type_paiement) && $type_paiement === 'Inscription') ? 'selected' : '' ?>>Inscription</option>
            <option value="Mensualité" <?= (isset($type_paiement) && $type_paiement === 'Mensualité') ? 'selected' : '' ?>>Mensualité</option>
            <option value="Autre" <?= (isset($type_paiement) && $type_paiement === 'Autre') ? 'selected' : '' ?>>Autre</option>
        </select>

        <p>Montant total demandé par l'école: <strong><?= number_format($montantfinal, 2, ',', ' ') ?> FCFA</strong></p>

        <button type="submit">Inscrire</button>
    </form>

    <p><a href="montantfinal.php">Gérer le montant total</a></p>
    <p><a href="../utilisateurs/dashboard.php">Retour</a></p>
</body>
</html>
