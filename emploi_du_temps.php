<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps - Étudiant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
        .table thead th {
            background-color: #0d6efd;
            color: white;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .btn-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/universite-app/dashboard-etudiant.php">🎓 Emploi du temps</a>
        <a href="/universite-app/app/Views/login.php" class="btn btn-light">Déconnexion</a>
    </div>
</nav>

<div class="container mt-4">
    <a href="/universite-app/app/Views/etudiant/dashboard.php" class="btn btn-outline-primary btn-back">
        <i class="fa fa-arrow-left me-1"></i> Retour au tableau de bord
    </a>

    <h4 class="mb-4 text-primary"><i class="fa fa-calendar-alt me-2"></i> Emploi du temps hebdomadaire</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm bg-white">
            <thead>
                <tr>
                    <th>Heure</th>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>08h - 10h</td>
                    <td>Mathématiques</td>
                    <td>Informatique</td>
                    <td>Libre</td>
                    <td>Physique</td>
                    <td>Anglais</td>
                    <td>Libre</td>
                </tr>
                <tr>
                    <td>10h - 12h</td>
                    <td>Chimie</td>
                    <td>Libre</td>
                    <td>Anglais</td>
                    <td>Programmation</td>
                    <td>Libre</td>
                    <td>Mathématiques</td>
                </tr>
                <tr>
                    <td>14h - 16h</td>
                    <td>Libre</td>
                    <td>Projet web</td>
                    <td>Libre</td>
                    <td>Chimie</td>
                    <td>Physique</td>
                    <td>Libre</td>
                </tr>
                <tr>
                    <td>16h - 18h</td>
                    <td>Libre</td>
                    <td>Libre</td>
                    <td>Sport</td>
                    <td>Libre</td>
                    <td>Libre</td>
                    <td>Projet web</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>