<?php
// Téléchargement PDF simulé
if (isset($_GET['action']) && $_GET['action'] === 'pdf') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="bulletin.pdf"');
    echo "Ce serait ici le contenu PDF du bulletin (généré dynamiquement plus tard).";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes & Bulletins</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar navbar-dark bg-primary px-3">
    <a class="navbar-brand" href="/universite-app/app/Views/etudiant/dashboard.php"><i class="fa fa-arrow-left me-2"></i>Retour</a>
    <a href="/universite-app/app/Views/login.php" class="btn btn-light">Déconnexion</a>
</nav>

<!-- Contenu principal -->
<div class="container mt-5">
    <h3 class="mb-4 text-primary"><i class="fa fa-clipboard-check me-2"></i>Mes notes & bulletins</h3>

    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Coefficient</th>
                        <th>Note pondérée</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mathématiques</td>
                        <td>16</td>
                        <td>4</td>
                        <td>64</td>
                    </tr>
                    <tr>
                        <td>Physique</td>
                        <td>14</td>
                        <td>3</td>
                        <td>42</td>
                    </tr>
                    <tr>
                        <td>Français</td>
                        <td>17</td>
                        <td>2</td>
                        <td>34</td>
                    </tr>
                    <tr class="table-primary">
                        <td colspan="3"><strong>Moyenne Générale</strong></td>
                        <td><strong>13.56 / 20</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <a href="notes_bulletins.php?action=pdf" class="btn btn-outline-primary">
                <i class="fa fa-file-pdf me-1"></i> Télécharger bulletin PDF
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>