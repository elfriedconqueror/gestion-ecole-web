<?php
$host = 'localhost';
$db   = 'gestion_ecole';
$user = 'root';
$pass = ''; // à adapter selon ta config
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}
?>