<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Étudiant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: 600;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">🎓 Espace Étudiant</a>
        <a href="/universite-app/app/Views/login.php" class="btn btn-light">Déconnexion</a>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-5">
    <h3 class="mb-4 text-primary">👋 Bienvenue sur votre tableau de bord</h3>
    <div class="row g-4">

        <!-- Exemple de carte fonctionnelle -->
        <div class="col-md-6 col-lg-4">
            <a href="infos_perso.php" class="text-dark">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-user text-primary me-2"></i> Informations personnelles</h5>
                        <p class="card-text">Voir et modifier vos infos personnelles.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="paiements.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fa fa-money-bill-wave text-success me-2"></i> Paiements & soldes</h5>
                        <p class="card-text">Historique de paiements et montants restants.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
    <a href="emploi_du_temps.php">
        <div class="card p-3 shadow-sm bg-white">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fa fa-calendar-alt text-warning me-2"></i> Emploi du temps
                </h5>
                <p class="card-text">Consultez vos horaires de cours.</p>
            </div>
        </div>
    </a>
</div>

<div class="col-md-6 col-lg-4">
    <a href="bulletins.php">
        <div class="card p-3 shadow-sm bg-white">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fa fa-clipboard-check text-info me-2"></i> Notes & Bulletins
                </h5>
                <p class="card-text">Accès à vos résultats scolaires.</p>
            </div>
        </div>
    </a>
</div>

        <div class="col-md-6 col-lg-4">
            <a href="suivi_presence.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-user-clock text-danger me-2"></i> Suivi de présence</h5>
                        <p class="card-text">Absences et retards enregistrés.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="cours_inscrits.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-book-open text-secondary me-2"></i> Cours inscrits</h5>
                        <p class="card-text">Liste des unités d’enseignement.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="notifications.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-bell text-primary me-2"></i> Notifications</h5>
                        <p class="card-text">Examens, rappels, annonces officielles.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="documents_officiels.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-download text-dark me-2"></i> Documents officiels</h5>
                        <p class="card-text">Télécharger vos documents administratifs.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="changer_mot_de_passe.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-key text-warning me-2"></i> Changer mot de passe</h5>
                        <p class="card-text">Mettre à jour vos identifiants d’accès.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="contacter_administration.php">
                <div class="card p-3 shadow-sm bg-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-envelope text-info me-2"></i> Contacter l’administration</h5>
                        <p class="card-text">Soumettre une question ou un souci.</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>