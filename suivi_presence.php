<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi de présence</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .status-icon.present { color: green; }
        .status-icon.absent { color: red; }
        .status-icon.retard { color: orange; }
    </style>
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="fa fa-arrow-left me-2"></i>Retour</a>
        <a href="/universite-app/app/Views/login.php" class="btn btn-outline-light">Déconnexion</a>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-5">
    <h3 class="mb-4 text-primary">📅 Suivi de présence</h3>

    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>Date</th>
                <th>Cours</th>
                <th>Heure</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <!-- Exemple de données fictives -->
            <tr>
                <td>2025-06-05</td>
                <td>Mathématiques</td>
                <td>08:00 - 10:00</td>
                <td><i class="fa fa-check-circle status-icon present"></i> Présent</td>
            </tr>
            <tr>
                <td>2025-06-06</td>
                <td>Physique</td>
                <td>10:00 - 12:00</td>
                <td><i class="fa fa-times-circle status-icon absent"></i> Absent</td>
            </tr>
            <tr>
                <td>2025-06-07</td>
                <td>Chimie</td>
                <td>14:00 - 16:00</td>
                <td><i class="fa fa-clock status-icon retard"></i> En retard</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>