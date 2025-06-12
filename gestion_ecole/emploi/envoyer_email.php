<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../config/db.php'; // Connexion DB

// Récupérer l'ID de la classe et valider
$classe_id = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;
if ($classe_id <= 0) {
    exit('ID de classe invalide.');
}

$emails = [];

// 1. Récupérer les emails des étudiants inscrits dans la classe
$sql_etudiants = "
    SELECT u.email
    FROM inscription i
    JOIN etudiant e ON e.id = i.id_etudiant
    JOIN utilisateur u ON u.id = e.id
    WHERE i.id_classe = ?
";
$stmt = $conn->prepare($sql_etudiants);
if (!$stmt) {
    exit("Erreur préparation requête étudiants : " . $conn->error);
}
$stmt->bind_param("i", $classe_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    if (!empty($row['email'])) {
        $emails[] = $row['email'];
    }
}
$stmt->close();

if (empty($emails)) {
    exit("Aucun email d'étudiant trouvé pour cette classe.");
}

// 2. Récupérer les emails des enseignants liés aux matières de la classe
$sql_enseignants = "
    SELECT DISTINCT u.email 
    FROM utilisateur u
    JOIN enseignant ens ON u.id = ens.id
    JOIN matiere_enseignant me ON me.enseignant_id = ens.id
    JOIN classe_matiere cm ON cm.id_matiere = me.matiere_id
    WHERE cm.id_classe = ?
";
$stmt = $conn->prepare($sql_enseignants);
if (!$stmt) {
    exit("Erreur préparation requête enseignants : " . $conn->error);
}
$stmt->bind_param("i", $classe_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    if (!empty($row['email']) && !in_array($row['email'], $emails)) {
        $emails[] = $row['email'];
    }
}
$stmt->close();

if (empty($emails)) {
    exit("Aucun email d'enseignant trouvé pour cette classe.");
}

// 3. Générer le contenu PDF de l’emploi du temps
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Administration Scolaire');
$pdf->SetTitle('Emploi du temps de la semaine');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

$html = "<h2>Emploi du temps de la semaine</h2>";

$monday = strtotime('monday this week');
$sunday = strtotime('sunday this week');

// Requête emploi du temps
$sql_emploi = "
    SELECT e.date, e.heure_debut, e.heure_fin, m.nom as matiere
    FROM emploi_temp e
    JOIN matiere m ON e.id_matiere = m.id
    WHERE e.id_classe = ? AND e.date BETWEEN ? AND ?
    ORDER BY e.date, e.heure_debut
";
$stmt = $conn->prepare($sql_emploi);
if (!$stmt) {
    exit("Erreur préparation requête emploi : " . $conn->error);
}

$date_debut = date('Y-m-d', $monday);
$date_fin = date('Y-m-d', $sunday);

$stmt->bind_param("iss", $classe_id, $date_debut, $date_fin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $html .= "<p>Aucun emploi du temps disponible pour cette semaine.</p>";
} else {
    $html .= "<table border=\"1\" cellpadding=\"5\">";
    $html .= "<tr><th>Date</th><th>Heure début</th><th>Heure fin</th><th>Matière</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
            <td>" . htmlspecialchars($row['date']) . "</td>
            <td>" . htmlspecialchars($row['heure_debut']) . "</td>
            <td>" . htmlspecialchars($row['heure_fin']) . "</td>
            <td>" . htmlspecialchars($row['matiere']) . "</td>
        </tr>";
    }
    $html .= "</table>";
}
$stmt->close();

$pdf->writeHTML($html, true, false, true, false, '');

$pdf_path = sys_get_temp_dir() . "/emploi_temps_classe_{$classe_id}.pdf";
$pdf->Output($pdf_path, 'F');

// 4. Envoi de l’email avec Brevo SMTP
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.brevo.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'dannryan9@gmail.com';       // Ton adresse email Brevo
    $mail->Password = 'EbOyWYXsVpMz5D6K';          // Clé SMTP Brevo
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('dannryan9@gmail.com', 'Administration Scolaire');

    foreach ($emails as $email) {
        $mail->addAddress($email);
    }

    $mail->isHTML(true);
    $mail->Subject = "Emploi du temps de la semaine - Classe #$classe_id";
    $mail->Body = "Veuillez trouver ci-joint l'emploi du temps de la semaine pour votre classe.";

    $mail->addAttachment($pdf_path);

    $mail->send();
    unlink($pdf_path);

    echo "Email envoyé avec succès via Brevo !";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    if (file_exists($pdf_path)) unlink($pdf_path);
}
