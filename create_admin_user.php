<?php
// Script pour créer un utilisateur administrateur
include("script/connexion.php");

// Informations de l'administrateur
$matricule = "ADM001";
$username = "Jean";
$password = "admin123";
$role = "admin";

// Vérifier si l'utilisateur existe déjà
$check_query = "SELECT id FROM utilisateur WHERE username = '$username' AND role = '$role'";
$check_result = mysqli_query($con, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Mettre à jour l'utilisateur existant
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_query = "UPDATE utilisateur SET matricule='$matricule', password='$hashed_password' WHERE username='$username' AND role='$role'";
    
    if (mysqli_query($con, $update_query)) {
        echo "Utilisateur administrateur mis à jour avec succès !\n";
        echo "Identifiants :\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
        echo "Role: $role\n";
    } else {
        echo "Erreur lors de la mise à jour de l'utilisateur : " . mysqli_error($con) . "\n";
    }
} else {
    // Hacher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insérer le nouvel utilisateur
    $insert_query = "INSERT INTO utilisateur (matricule, username, password, role) VALUES ('$matricule', '$username', '$hashed_password', '$role')";
    
    if (mysqli_query($con, $insert_query)) {
        echo "Utilisateur administrateur créé avec succès !\n";
        echo "Identifiants :\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
        echo "Role: $role\n";
    } else {
        echo "Erreur lors de la création de l'utilisateur : " . mysqli_error($con) . "\n";
    }
}
?>