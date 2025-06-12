<?php
session_start();
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_etudiant = $_POST['id_etudiant'];
    $montant = $_POST['montant'];
    $classe_id = $_POST['classe_id']; 

    if (is_numeric($montant) && $montant > 0) {
        $sql = "SELECT montantfinal FROM config_paiement ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $config = mysqli_fetch_assoc($result);
        $montantfinal = $config['montantfinal'];

        $stmt = $conn->prepare("INSERT INTO paiement (id_etudiant, montant, date_paiement, type_paiement, montantfinal) VALUES (?, ?, NOW(), 'Mensualité', ?)");
        $stmt->bind_param("ddi", $id_etudiant, $montant, $montantfinal);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Paiement ajouté avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du paiement.";
        }
    } else {
        $_SESSION['error'] = "Montant invalide.";
    }

    header("Location: index.php?classe_id=" . $classe_id);
    exit();
}

$classes = mysqli_query($conn, "SELECT * FROM classe");

$classe_id = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;

$etudiants = [];
if ($classe_id > 0) {
    $sql = "
        SELECT u.id, u.nom, u.prenom, e.matricule, u.email, u.telephone, u.genre, u.date_naissance,
               (SELECT COALESCE(SUM(p.montant), 0) FROM paiement p WHERE p.id_etudiant = e.id) AS total_paye,
               (SELECT montantfinal FROM config_paiement ORDER BY id DESC LIMIT 1) AS montantfinal
        FROM etudiant e
        JOIN utilisateur u ON e.id = u.id
        JOIN inscription i ON i.id_etudiant = e.id
        WHERE i.id_classe = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $etudiants = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function imprimerPage() {
            window.print();
        }
    </script>
</head>
<body>
    <h2>Gestion des Étudiants</h2>

    <button onclick="imprimerPage()">Imprimer</button>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

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

    <?php if ($classe_id > 0): ?>
        <h3>Étudiants dans la classe <?= htmlspecialchars($classe_id) ?></h3>

        <?php if (count($etudiants) > 0): ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date de naissance</th>
                        <th>Genre</th>
                        <th>Total payé</th>
                        <th>Reste à payer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
                            <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                            <td><?= htmlspecialchars($etudiant['email']) ?></td>
                            <td><?= htmlspecialchars($etudiant['telephone']) ?></td>
                            <td><?= htmlspecialchars($etudiant['date_naissance']) ?></td>
                            <td><?= htmlspecialchars($etudiant['genre']) ?></td>
                            <td><?= number_format($etudiant['total_paye'], 0, ',', ' ') ?> F</td>
                            <td><?= number_format($etudiant['montantfinal'] - $etudiant['total_paye'], 0, ',', ' ') ?> F</td>
                            <td>
                                <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="id_etudiant" value="<?= $etudiant['id'] ?>">
                                    <input type="hidden" name="classe_id" value="<?= $classe_id ?>">
                                    <input type="number" name="montant" placeholder="Montant" min="0" required>
                                    <input type="submit" value="Payer">
                                </form>
                                <br>
                                <a href="modifier_etudiant.php?id=<?= $etudiant['id'] ?>">Modifier</a> |
                                <a href="supprimer_etudiant.php?id=<?= $etudiant['id'] ?>" 
                                   onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun étudiant dans cette classe.</p>
        <?php endif; ?>

    <?php endif; ?>
  <p><a href="../utilisateurs/dashboard.php">Retour au tableau de bord</a></p>
</body>
</html>