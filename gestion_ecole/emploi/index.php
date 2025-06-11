<?php
session_start();
include("../config/db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

$classes = mysqli_query($conn, "SELECT * FROM classe");

$matieres = mysqli_query($conn, "SELECT * FROM matiere");

$classe_id = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;

$emplois = [];
if ($classe_id > 0) {
    $sql = "SELECT * FROM emploi_temp WHERE id_classe = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $emplois = $result->fetch_all(MYSQLI_ASSOC);
}
if (isset($_POST['send_email']) && $classe_id > 0) {
    // 1. Récupération de l'emploi du temps
    $emploi_html = "<h3>Emploi du Temps</h3><table border='1' cellpadding='5' cellspacing='0'>
        <tr><th>Date</th><th>Heure début</th><th>Heure fin</th><th>Matière</th></tr>";

    foreach ($emplois as $emploi) {
        $matiere_id = $emploi['id_matiere'];
        $matiere_result = mysqli_query($conn, "SELECT nom FROM matiere WHERE id = $matiere_id");
        $matiere_row = mysqli_fetch_assoc($matiere_result);
        $matiere_nom = isset($matiere_row['nom']) ? $matiere_row['nom'] : 'Inconnue';

        $emploi_html .= "<tr>
            <td>{$emploi['date']}</td>
            <td>{$emploi['heure_debut']}</td>
            <td>{$emploi['heure_fin']}</td>
            <td>{$matiere_nom}</td>
        </tr>";
    }
    $emploi_html .= "</table>";

    // 2. Récupération des emails des étudiants et enseignants
    $emails = [];

    // Étudiants
    $etudiants = mysqli_query($conn, "SELECT u.email FROM etudiant e JOIN utilisateur u ON e.utilisateur_id = u.id WHERE e.classe_id = $classe_id");
    while ($e = mysqli_fetch_assoc($etudiants)) {
        if (filter_var($e['email'], FILTER_VALIDATE_EMAIL)) {
            $emails[] = $e['email'];
        }
    }

    // Enseignants
    $enseignants = mysqli_query($conn, "SELECT DISTINCT u.email
        FROM classe_matiere cm
        JOIN matiere_enseignant me ON cm.matiere_id = me.matiere_id
        JOIN enseignant ens ON me.enseignant_id = ens.id
        JOIN utilisateur u ON ens.utilisateur_id = u.id
        WHERE cm.classe_id = $classe_id");

    while ($e = mysqli_fetch_assoc($enseignants)) {
        if (filter_var($e['email'], FILTER_VALIDATE_EMAIL)) {
            $emails[] = $e['email'];
        }
    }

    // 3. Envoi avec PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // ou votre hôte SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'votre_email@gmail.com'; // ⚠️ votre email Gmail
        $mail->Password = 'votre_mot_de_passe_app'; // ⚠️ un mot de passe d'application
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('votre_email@gmail.com', 'Administration');
        foreach (array_unique($emails) as $email) {
            $mail->addAddress($email);
        }

        $mail->isHTML(true);
        $mail->Subject = 'Emploi du Temps';
        $mail->Body = $emploi_html;

        $mail->send();
        $_SESSION['message'] = "Email envoyé avec succès à tous les utilisateurs.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }

    header("Location: index.php?classe_id=" . $classe_id);
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'send_email') {
    $id_classe = (int) $_POST['id_classe'];


    $stmt = $conn->prepare("SELECT e.date, e.heure_debut, e.heure_fin, m.nom AS matiere 
                            FROM emploi_temp e 
                            JOIN matiere m ON e.id_matiere = m.id 
                            WHERE e.id_classe = ?");
    $stmt->bind_param("i", $id_classe);
    $stmt->execute();
    $result = $stmt->get_result();
    $emplois_data = $result->fetch_all(MYSQLI_ASSOC);

    if (empty($emplois_data)) {
        $_SESSION['error'] = "Aucun emploi du temps à envoyer.";
        header("Location: index.php?classe_id=" . $id_classe);
        exit();
    }

    $message = "Emploi du temps de la classe (ID $id_classe) :\n\n";
    foreach ($emplois_data as $emploi) {
        $message .= "Date : {$emploi['date']}, {$emploi['heure_debut']} - {$emploi['heure_fin']}, Matière : {$emploi['matiere']}\n";
    }

    $emails = [];

    $stmt = $conn->prepare("SELECT u.email FROM etudiant e 
                            JOIN utilisateur u ON u.id = e.utilisateur_id 
                            JOIN inscription i ON i.id_etudiant = e.id 
                            WHERE i.id_classe = ?");
    $stmt->bind_param("i", $id_classe);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $emails[] = $row['email'];
        }
    }

    $stmt = $conn->prepare("SELECT DISTINCT u.email FROM matiere_enseignant me 
                            JOIN classe_matiere cm ON cm.id_matiere = me.id_matiere 
                            JOIN enseignant ens ON ens.id = me.id_enseignant 
                            JOIN utilisateur u ON u.id = ens.utilisateur_id 
                            WHERE cm.id_classe = ?");
    $stmt->bind_param("i", $id_classe);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $emails[] = $row['email'];
        }
    }

    $subject = "Emploi du temps de la classe";
    $headers = "From: noreply@tonsite.com";

    $successCount = 0;
    foreach ($emails as $email) {
        if (mail($email, $subject, $message, $headers)) {
            $successCount++;
        }
    }

    $_SESSION['message'] = "$successCount email(s) envoyés avec succès.";
    header("Location: index.php?classe_id=" . $id_classe);
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de l'Emploi du Temps</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Gestion de l'Emploi du Temps</h2>

    <form method="get" action="">
        <label>Choisir une classe :</label>
        <select name="classe_id" onchange="this.form.submit()">
            <option value="">--Sélectionner--</option>
            <?php while ($c = mysqli_fetch_assoc($classes)) : ?>
                <option value="<?= $c['id'] ?>" <?= ($c['id'] == $classe_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if ($classe_id > 0): ?>
        <h3>Emploi du Temps pour la classe <?= htmlspecialchars($classe_id) ?></h3>

        <form action="" method="post">
            <input type="hidden" name="id_classe" value="<?= $classe_id ?>">
            <label>Date :</label>
            <input type="date" name="date" required>
            <label>Heure de début :</label>
            <input type="time" name="heure_debut" required>
            <label>Heure de fin :</label>
            <input type="time" name="heure_fin" required>
            <label>Matière :</label>
            <select name="id_matiere" required>
                <?php while ($matiere = mysqli_fetch_assoc($matieres)) : ?>
                    <option value="<?= $matiere['id'] ?>"><?= htmlspecialchars($matiere['nom']) ?></option>
                <?php endwhile; ?>
            </select>
            <input type="submit" value="Ajouter">
        </form>

        <?php if (count($emplois) > 0): ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure de début</th>
                        <th>Heure de fin</th>
                        <th>Matière</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emplois as $emploi): ?>
                        <tr>
                            <td><?= htmlspecialchars($emploi['date']) ?></td>
                            <td><?= htmlspecialchars($emploi['heure_debut']) ?></td>
                            <td><?= htmlspecialchars($emploi['heure_fin']) ?></td>
                            <td><?= htmlspecialchars($emploi['id_matiere']) ?></td>
                            <td>
                                <a href="modifier_emploi.php?id=<?= $emploi['id'] ?>">Modifier</a> |
                                <a href="supprimer_emploi.php?id=<?= $emploi['id'] ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun emploi du temps pour cette classe.</p>
        
        <?php endif; ?>
    <?php endif; ?>
   <form action="envoyer_email.php" method="get" style="margin-top: 20px;">
    <input type="hidden" name="classe_id" value="<?= $classe_id ?>">
    <button type="submit">📧 Envoyer l'emploi du temps par Email</button>
</form>


    <p><a href="../utilisateurs/dashboard.php">Retour au tableau de bord</a></p>
</body>
</html>