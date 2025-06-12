<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION["type_utilisateur"]) || $_SESSION["type_utilisateur"] != "Administrateur") {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
    $date_naissance = $_POST["date_naissance"];
    $genre = $_POST["genre"];
    $adresse = mysqli_real_escape_string($conn, $_POST["adresse"]);
    $telephone = mysqli_real_escape_string($conn, $_POST["telephone"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = sha1($_POST["password"]);
    $specialite = mysqli_real_escape_string($conn, $_POST["specialite"]);

    $sql1 = "INSERT INTO utilisateur (nom, prenom, date_naissance, genre, adresse, telephone, email, password, type_utilisateur)
             VALUES ('$nom', '$prenom', '$date_naissance', '$genre', '$adresse', '$telephone', '$email', '$password', 'Enseignant')";

    if (mysqli_query($conn, $sql1)) {
        $utilisateur_id = mysqli_insert_id($conn);

        $sql2 = "INSERT INTO enseignant (id, specialite) VALUES ($utilisateur_id, '$specialite')";

        if (mysqli_query($conn, $sql2)) {
            $message = "Enseignant ajouté avec succès.";
            header("location : index.php");
        } else {
            $message = "Erreur lors de l'ajout de la spécialité : " . mysqli_error($conn);

            mysqli_query($conn, "DELETE FROM utilisateur WHERE id = $utilisateur_id");
        }
    } else {
        $message = "Erreur lors de l'ajout de l'utilisateur : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un enseignant</title>
   <link rel="stylesheet" href="styles.css" />

</head>
<body>
    <div class="container">
        <h2>Ajouter un enseignant</h2>

        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Nom :</label><br>
            <input type="text" name="nom" required><br><br>

            <label>Prénom :</label><br>
            <input type="text" name="prenom" required><br><br>

            <label>Date de naissance :</label><br>
            <input type="date" name="date_naissance"><br><br>

            <label>Genre :</label><br>
            <select name="genre">
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select><br><br>

            <label>Adresse :</label><br>
            <textarea name="adresse"></textarea><br><br>

            <label>Téléphone :</label><br>
            <input type="text" name="telephone"><br><br>

            <label>Email :</label><br>
            <input type="email" name="email" required><br><br>

            <label>Mot de passe :</label><br>
            <input type="password" name="password" required><br><br>

            <label>Spécialité :</label><br>
            <input type="text" name="specialite"><br><br>

            <button type="submit">Ajouter</button>
        </form>
         <p><a href="index.php">Retour</a></p>
    </div>
</body>
</html>
