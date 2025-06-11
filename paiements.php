<?php
// Paiements fictifs simulés
$paiements = [
    [
        'id' => 1,
        'type_paiement' => 'Inscription',
        'montant' => 150000,
        'date_paiement' => '2024-10-05'
    ],
    [
        'id' => 2,
        'type_paiement' => 'Scolarité - Trimestre 1',
        'montant' => 100000,
        'date_paiement' => '2024-11-10'
    ],
    [
        'id' => 3,
        'type_paiement' => 'Scolarité - Trimestre 2',
        'montant' => 120000,
        'date_paiement' => '2025-01-15'
    ]
];

$total_paye = 0;
foreach ($paiements as $paiement) {
    $total_paye += $paiement['montant'];
}

$frais_annuel = 500000; // fixe pour la démo
$solde = $frais_annuel - $total_paye;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Paiements</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .card-payment {
            border: none;
            border-left: 5px solid #0d6efd;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .card-payment:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .summary-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="fa fa-arrow-left me-2"></i>Retour</a>
        <a href="/universite-app/app/Views/login.php" class="btn btn-light">Déconnexion</a>
    </div>
</nav>

<!-- CONTENU -->
<div class="container mt-5">
    <h3 class="mb-4 text-primary"><i class="fas fa-money-check-alt me-2"></i>Mes Paiements & Solde</h3>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="summary-box border-start border-4 border-success">
                <h5 class="text-success"><i class="fas fa-check-circle me-2"></i>Total Payé</h5>
                <p class="fs-5 fw-bold"><?= number_format($total_paye, 0, ',', ' ') ?> FCFA</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="summary-box border-start border-4 border-danger">
                <h5 class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Solde Restant</h5>
                <p class="fs-5 fw-bold"><?= number_format($solde, 0, ',', ' ') ?> FCFA</p>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (count($paiements) === 0): ?>
            <div class="alert alert-warning text-center">Aucun paiement enregistré pour l’instant.</div>
        <?php else: ?>
            <?php foreach ($paiements as $paiement): ?>
                <div class="col-md-6 mb-4">
                    <div class="card card-payment shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1"><?= htmlspecialchars($paiement['type_paiement']) ?></h6>
                                <h5 class="fw-bold"><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</h5>
                                <small class="text-secondary"><?= date("d/m/Y", strtotime($paiement['date_paiement'])) ?></small>
                            </div>
                            <a href="generer_recu.php?id=<?= $paiement['id'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i> Reçu
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left me-1"></i> Retour</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>