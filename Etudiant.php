<?php
require_once _DIR_ . '/../includes/database.php';

class Etudiant {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $sql = "SELECT e.id, u.nom, u.prenom, u.genre, e.matricule, e.date_inscription
                FROM etudiant e
                JOIN utilisateur u ON e.id = u.id";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM etudiant e JOIN utilisateur u ON e.id = u.id WHERE e.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Ajouter et modifier seront ajoutés plus tard
}
?>