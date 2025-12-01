<?php
// Connexion à la base de données pour l'espace étudiant
$host = 'localhost';
$db = 'suivi_prestation';
$user = 'admin';
$pass = 'admin';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}
?>