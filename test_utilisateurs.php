<?php
// Test de la page de gestion des utilisateurs
include("script/connexion.php");

// Compter le nombre d'utilisateurs
$query = "SELECT COUNT(*) as total FROM utilisateur";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

echo "Nombre total d'utilisateurs dans la base de données : " . $row['total'] . "\n";

// Afficher quelques utilisateurs
$query = "SELECT * FROM utilisateur LIMIT 5";
$result = mysqli_query($con, $query);

echo "\nListe des utilisateurs :\n";
while ($user = mysqli_fetch_assoc($result)) {
    echo "- ID: " . $user['id'] . ", Matricule: " . $user['matricule'] . ", Nom d'utilisateur: " . $user['username'] . ", Rôle: " . $user['role'] . "\n";
}
?>