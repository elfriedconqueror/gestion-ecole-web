<?php
require_once("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Récupérer les données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $date_naissance = $_POST["date_naissance"];
    $lieu_naissance = $_POST["lieu_naissance"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $adresse = $_POST["adresse"];
    $classe_id = $_POST["classe_id"];
    $parent_nom = $_POST["parent_nom"];
    $parent_tel = $_POST["parent_tel"];
    $parent_email = $_POST["parent_email"];
    $montant = $_POST["montant"];
    $mode_paiement = $_POST["mode_paiement"];
    $date_paiement = date("Y-m-d");
    $annee_scolaire = date("Y") . "-" . (date("Y") + 1);

    // 2. Gérer l'upload de la photo
    $photo_name = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_name = uniqid() . "_" . basename($_FILES['photo']['name']);
        $upload_path = "../uploads/photos/";
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        move_uploaded_file($photo_tmp, $upload_path . $photo_name);
    }

    // 3. Créer un mot de passe aléatoire pour l'étudiant
    $password = password_hash("etudiant123", PASSWORD_DEFAULT);

    // 4. Insérer dans la table utilisateur
    $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, date_naissance, genre, adresse, telephone, email, password, type_utilisateur, lieu_naissance, photo) VALUES (?, ?, ?, NULL, ?, ?, ?, ?, 'Etudiant', ?, ?)");
    $stmt->bind_param("sssssssss", $nom, $prenom, $date_naissance, $adresse, $telephone, $email, $password, $lieu_naissance, $photo_name);
    if ($stmt->execute()) {
        $utilisateur_id = $stmt->insert_id;

        // 5. Insérer dans la table etudiant
        $matricule = "ETD" . str_pad($utilisateur_id, 5, "0", STR_PAD_LEFT);
        $stmt2 = $conn->prepare("INSERT INTO etudiant (id, matricule, date_inscription) VALUES (?, ?, NOW())");
        $stmt2->bind_param("is", $utilisateur_id, $matricule);
        $stmt2->execute();

        // 6. Inscrire dans la classe
        $stmt3 = $conn->prepare("INSERT INTO inscription (id_etudiant, id_classe, annee_scolaire) VALUES (?, ?, ?)");
        $stmt3->bind_param("iis", $utilisateur_id, $classe_id, $annee_scolaire);
        $stmt3->execute();

        // 7. Enregistrer le parent
        $stmt4 = $conn->prepare("INSERT INTO parents (id_etudiant, nom, telephone, email, lien_parente) VALUES (?, ?, ?, ?, 'Parent')");
        $stmt4->bind_param("isss", $utilisateur_id, $parent_nom, $parent_tel, $parent_email);
        $stmt4->execute();

        // 8. Enregistrer le paiement
        $stmt5 = $conn->prepare("INSERT INTO paiement (id_etudiant, montant, date_paiement, mode_paiement, reference_paiement) VALUES (?, ?, ?, ?, NULL)");
        $stmt5->bind_param("idss", $utilisateur_id, $montant, $date_paiement, $mode_paiement);
        $stmt5->execute();

        echo "<script>alert('Inscription réussie !'); window.location.href = 'inscription_etudiant.php';</script>";
    } else {
        echo "Erreur lors de l'enregistrement.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>