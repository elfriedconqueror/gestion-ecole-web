<?php
session_start();
require '../config/db.php';

$enseignant_id = isset($_SESSION['enseignant_id']) ? $_SESSION['enseignant_id'] : 0;
if ($enseignant_id == 0) {
    die("Non autorisé.");
}

$sql_classes = "SELECT DISTINCT cm.id_classe, c.nom_classe
                FROM classe_matiere cm
                JOIN matiere_enseignant me ON me.matiere_id = cm.id_matiere
                JOIN classe c ON c.id = cm.id_classe
                WHERE me.enseignant_id = ?
                ORDER BY c.nom_classe";

$stmt = $conn->prepare($sql_classes);
$stmt->bind_param("i", $enseignant_id);
$stmt->execute();
$result_classes = $stmt->get_result();

$classes = $result_classes->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['classe_id'])) {
    $classe_id = (int)$_POST['classe_id'];

    $notes = isset($_POST['notes']) ? $_POST['notes'] : [];

    foreach ($notes as $etudiant_id => $matieres_notes) {
        foreach ($matieres_notes as $matiere_id => $note_valeur) {
            $note_valeur = floatval($note_valeur);

            $sequence = 1;

            $sql_check = "SELECT id FROM note WHERE id_etudiant=? AND id_matiere=? AND id_classe=? AND sequence=?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("iiii", $etudiant_id, $matiere_id, $classe_id, $sequence);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $sql_update = "UPDATE note SET note=? WHERE id_etudiant=? AND id_matiere=? AND id_classe=? AND sequence=?";
                $stmt_upd = $conn->prepare($sql_update);
                $stmt_upd->bind_param("diiii", $note_valeur, $etudiant_id, $matiere_id, $classe_id, $sequence);
                $stmt_upd->execute();
                $stmt_upd->close();
            } else {
                $sql_insert = "INSERT INTO note (id_etudiant, id_matiere, id_classe, sequence, note) VALUES (?, ?, ?, ?, ?)";
                $stmt_ins = $conn->prepare($sql_insert);
                $stmt_ins->bind_param("iiiid", $etudiant_id, $matiere_id, $classe_id, $sequence, $note_valeur);
                $stmt_ins->execute();
                $stmt_ins->close();
            }
            $stmt_check->close();
        }
    }

    echo "<p>Notes enregistrées avec succès.</p>";
}

$classe_selectionnee = isset($classes[0]['id_classe']) ? $classes[0]['id_classe'] : 0;
?>

<h2>Gestion des notes - Enseignant</h2>

<form method="post" action="">
    <label for="classe_id">Choisir la classe :</label>
    <select name="classe_id" id="classe_id" onchange="this.form.submit()">
        <?php foreach ($classes as $classe) { ?>
            <option value="<?php echo $classe['id_classe']; ?>" <?php echo ($classe['id_classe'] == $classe_selectionnee) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($classe['nom_classe']); ?>
            </option>
        <?php } ?>
    </select>
</form>

<?php
if ($classe_selectionnee) {

    $sql_etudiants = "SELECT e.id AS etudiant_id, u.nom, u.prenom FROM inscription i
                      JOIN etudiant e ON i.id_etudiant = e.id
                      JOIN utilisateur u ON e.utilisateur_id = u.id
                      WHERE i.id_classe = ? ORDER BY u.nom, u.prenom";
    $stmt = $conn->prepare($sql_etudiants);
    $stmt->bind_param("i", $classe_selectionnee);
    $stmt->execute();
    $result_etudiants = $stmt->get_result();
    $etudiants = $result_etudiants->fetch_all(MYSQLI_ASSOC);

    $sql_matieres = "SELECT m.id AS matiere_id, m.nom AS matiere_nom
                     FROM matiere m
                     JOIN classe_matiere cm ON cm.id_matiere = m.id
                     JOIN matiere_enseignant me ON me.matiere_id = m.id
                     WHERE cm.id_classe = ? AND me.enseignant_id = ?
                     ORDER BY m.nom";
    $stmt = $conn->prepare($sql_matieres);
    $stmt->bind_param("ii", $classe_selectionnee, $enseignant_id);
    $stmt->execute();
    $result_matieres = $stmt->get_result();
    $matieres = $result_matieres->fetch_all(MYSQLI_ASSOC);

    $notes_existantes = array();
    $sequence = 1;
    $sql_notes = "SELECT id_etudiant, id_matiere, note FROM note WHERE id_classe = ? AND sequence = ?";
    $stmt = $conn->prepare($sql_notes);
    $stmt->bind_param("ii", $classe_selectionnee, $sequence);
    $stmt->execute();
    $result_notes = $stmt->get_result();
    while ($row = $result_notes->fetch_assoc()) {
        $notes_existantes[$row['id_etudiant']][$row['id_matiere']] = $row['note'];
    }
    ?>

    <form method="post" action="">
        <input type="hidden" name="classe_id" value="<?php echo $classe_selectionnee; ?>">

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Élève</th>
                    <?php foreach ($matieres as $matiere) { ?>
                        <th><?php echo htmlspecialchars($matiere['matiere_nom']); ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></td>
                        <?php foreach ($matieres as $matiere) { 
                            $val_note = isset($notes_existantes[$etudiant['etudiant_id']][$matiere['matiere_id']]) ? $notes_existantes[$etudiant['etudiant_id']][$matiere['matiere_id']] : '';
                        ?>
                            <td>
                                <input type="number" step="0.01" min="0" max="20"
                                       name="notes[<?php echo $etudiant['etudiant_id']; ?>][<?php echo $matiere['matiere_id']; ?>]"
                                       value="<?php echo htmlspecialchars($val_note); ?>">
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Enregistrer les notes</button>
    </form>

<?php
}
?>
