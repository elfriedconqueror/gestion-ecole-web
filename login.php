<?php
session_start();
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = sha1($_POST["password"]); 

    $query = "SELECT * FROM utilisateur WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result) == 1) {
    $utilisateur = mysqli_fetch_assoc($result);
    $_SESSION["utilisateur_id"] = $utilisateur["id"];
    $_SESSION["nom"] = $utilisateur["nom"];
    $_SESSION["type_utilisateur"] = $utilisateur["type_utilisateur"];

    header("Location: dashboard.php");
    exit;
}
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
