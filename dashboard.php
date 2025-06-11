<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION["type_utilisateur"])) {
    header("Location: login.php");
    exit;
}

$nom = isset($_SESSION["nom"]) ? $_SESSION["nom"] : '';
$type = $_SESSION["type_utilisateur"];

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Tableau de bord</title>
    <style>

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        color: #fff;
        overflow-x: hidden;
    }


    .blur-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 15px;
        padding: 30px;
        margin: 40px 20px;
        max-width: 900px;
        width: 100%;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 700;
        text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
    }

    nav {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 20px;
    }

    nav a {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        padding: 15px 20px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 12px;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: background 0.3s ease, transform 0.2s ease;
        user-select: none;
    }

    nav a:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.05);
        text-decoration: none;
        color: #000;
        font-weight: 700;
        box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    }

    nav a.logout {
        justify-self: end;
        background: #e74c3c;
        color: white !important;
        font-weight: 700;
        transition: background 0.3s ease;
    }

    nav a.logout:hover {
        background: #c0392b;
        color: #fff !important;
        transform: none;
        box-shadow: 0 6px 20px rgba(192, 57, 43, 0.7);
    }

    @media (max-width: 480px) {
        nav {
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .blur-container {
            padding: 20px;
            margin: 20px 10px;
        }
    }
</style>

</head>
<body>
    <div class="blur-container">
        <h1>Bienvenue <?= htmlspecialchars($nom) ?> !</h1>
        <p style="text-align:center; margin-bottom: 40px;">
            Vous êtes connecté en tant que <strong><?= htmlspecialchars($type) ?></strong>.
        </p>

        <nav>
            <?php if ($type === "Administrateur"): ?>
                <a href="../gerer_etudiant/index.php">Gérer les étudiants</a>
                <a href="../gerer_enseignant/index.php">Gérer les enseignants</a>
                <a href="../gerer_salle_classe/index.php">Gérer les salles de classe</a>
                <a href="../gerer_matiere/liste_matieres.php">Gérer les matières</a>
                <a href="../gerer_presences/index.php">Gérer les présences</a>
                <a href="../gerer_inscriptions/index.php">Gérer les inscriptions</a>
            <?php elseif ($type === "Enseignant"): ?>
                <a href="../gerer_notes.php">Gérer les notes</a>
                <a href="../liste_etudiants.php">Liste des étudiants</a>
            <?php elseif ($type === "Etudiant"): ?>
                <a href="../mes_notes.php">Mes notes</a>
                <a href="../mon_bulletin.php">Mon bulletin</a>
            <?php endif; ?>
            <a href="logout.php" class="logout">Déconnexion</a>
        </nav>
    </div>
</body>

</html>
