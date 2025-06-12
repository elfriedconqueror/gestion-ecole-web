<?php
session_start();
require_once '../config/db.php';

// Vérification de la session
if (!isset($_SESSION["utilisateur_id"]) || $_SESSION["type_utilisateur"] !== "Enseignant") {
    header("Location: ../login.php");
    exit;
}

$enseignant_id = $_SESSION["utilisateur_id"];

// Récupération des matières enseignées
$sql = "SELECT m.id, m.nom 
        FROM matiere m
        INNER JOIN matiere_enseignant me ON me.matiere_id = m.id
        WHERE me.enseignant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $enseignant_id);
$stmt->execute();
$result_matieres = $stmt->get_result();
$matieres = $result_matieres ? $result_matieres->fetch_all(MYSQLI_ASSOC) : [];

$statistiques = [];
$matiere_nom = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matiere_id'], $_POST['sequence'])) {
    $matiere_id = (int)$_POST['matiere_id'];
    $sequence = $_POST['sequence'];

    // Récupération du nom de la matière
    foreach ($matieres as $m) {
        if ($m['id'] == $matiere_id) {
            $matiere_nom = $m['nom'];
            break;
        }
    }

    $sql = "SELECT AVG(note) AS moyenne, MIN(note) AS min_note, MAX(note) AS max_note, COUNT(*) AS total
            FROM note
            WHERE id_matiere = ? AND sequence = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $matiere_id, $sequence);
    $stmt->execute();
    $result = $stmt->get_result();
    $statistiques = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>📊 Statistiques des notes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
        }
        h1 {
            color: #444;
        }
        form, .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: inline-block;
        }
        select, button {
            padding: 10px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            border-collapse: collapse;
            width: 60%;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        .print-btn {
            margin-top: 20px;
            background-color: #2196F3;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2196F3;
        }
    </style>
</head>
<body>
    <h1>📊 Statistiques de notes</h1>

    <form method="post">
        <label>Matière :</label>
        <select name="matiere_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($matieres as $matiere): ?>
                <option value="<?= $matiere['id'] ?>" <?= (isset($_POST['matiere_id']) && $_POST['matiere_id'] == $matiere['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($matiere['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Séquence :</label>
        <select name="sequence" required>
            <option value="">-- Choisir --</option>
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <option value="Séquence <?= $i ?>" <?= (isset($_POST['sequence']) && $_POST['sequence'] == "Séquence $i") ? 'selected' : '' ?>>
                    Séquence <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">Afficher</button>
    </form>

    <?php if (!empty($statistiques) && $statistiques['total'] > 0): ?>
    <div class="content" id="print-section">
        <h3><?= htmlspecialchars($matiere_nom) ?> - <?= htmlspecialchars($_POST['sequence']) ?></h3>

        <table>
            <thead>
                <tr>
                    <th>Moyenne</th>
                    <th>Note Min</th>
                    <th>Note Max</th>
                    <th>Nombre de notes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= number_format($statistiques['moyenne'], 2) ?></td>
                    <td><?= number_format($statistiques['min_note'], 2) ?></td>
                    <td><?= number_format($statistiques['max_note'], 2) ?></td>
                    <td><?= $statistiques['total'] ?></td>
                </tr>
            </tbody>
        </table>

        <canvas id="statistiquesChart" width="500" height="300"></canvas>
    </div>

    <button class="print-btn" onclick="printStats()">🖨 Imprimer</button>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>Aucune note trouvée pour cette matière et cette séquence.</p>
    <?php endif; ?>

    <a href="../utilisateurs/dashboard.php">⬅ Retour</a>

    <script>
        <?php if (!empty($statistiques) && $statistiques['total'] > 0): ?>
        const ctx = document.getElementById('statistiquesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Moyenne', 'Note Min', 'Note Max'],
                datasets: [{
                    label: 'Valeurs',
                    data: [
                        <?= number_format($statistiques['moyenne'], 2) ?>,
                        <?= number_format($statistiques['min_note'], 2) ?>,
                        <?= number_format($statistiques['max_note'], 2) ?>
                    ],
                    backgroundColor: ['#4CAF50', '#f44336', '#2196F3']
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        <?php endif; ?>

        function printStats() {
            const canvas = document.getElementById("statistiquesChart");
            const imgData = canvas.toDataURL("image/png");

            const section = document.getElementById("print-section").cloneNode(true);
            const img = document.createElement("img");
            img.src = imgData;
            img.style.maxWidth = "100%";
            section.querySelector("canvas").replaceWith(img);

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head><title>Impression Statistiques</title></head>
                <body>${section.innerHTML}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>
</body>
</html>