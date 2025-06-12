<?php
require_once _DIR_ . '/../models/Etudiant.php';

$etudiantModel = new Etudiant($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $etudiantModel->delete($_POST['id']);
        header('Location: ../views/etudiants.php');
        exit;
    }
}
?>