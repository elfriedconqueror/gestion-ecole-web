<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notifications Étudiant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .notification-card {
            border-radius: 12px;
            transition: all 0.2s ease-in-out;
        }
        .notification-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .icon {
            font-size: 1.2rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="fa fa-arrow-left me-2"></i>Retour</a>
        <a href="/universite-app/app/views/login.php" class="btn btn-outline-light">Déconnexion</a>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-5">
    <h3 class="mb-4 text-primary">📩 Mes notifications</h3>

    <div class="row g-3">
        <!-- Notification - Examen -->
        <div class="col-md-6">
            <div class="card notification-card border-start border-4 border-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-danger">
                        <i class="fa fa-pen icon"></i> Examen final prévu
                    </h5>
                    <p class="card-text">Votre examen de Mathématiques est prévu le <strong>15 juin 2025</strong> à 08h00.</p>
                    <small class="text-muted">Reçu le 05 juin 2025</small>
                </div>
            </div>
        </div>

        <!-- Notification - Paiement -->
        <div class="col-md-6">
            <div class="card notification-card border-start border-4 border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fa fa-money-bill-wave icon"></i> Paiement reçu
                    </h5>
                    <p class="card-text">Nous avons reçu votre paiement de 50 000 FCFA le <strong>04 juin 2025</strong>.</p>
                    <small class="text-muted">Reçu le 04 juin 2025</small>
                </div>
            </div>
        </div>

        <!-- Notification - Absence -->
        <div class="col-md-6">
            <div class="card notification-card border-start border-4 border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fa fa-exclamation-triangle icon"></i> Absence enregistrée
                    </h5>
                    <p class="card-text">Vous avez été marqué absent au cours de Physique du 03 juin 2025.</p>
                    <small class="text-muted">Reçu le 03 juin 2025</small>
                </div>
            </div>
        </div>

        <!-- Notification - Nouveau document -->
        <div class="col-md-6">
            <div class="card notification-card border-start border-4 border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fa fa-file-alt icon"></i> Nouveau document disponible
                    </h5>
                    <p class="card-text">Votre attestation de présence est désormais disponible au téléchargement.</p>
                    <small class="text-muted">Reçu le 02 juin 2025</small>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>