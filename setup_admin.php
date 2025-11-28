<?php
// Script pour configurer l'utilisateur administrateur
$host = "localhost";
$user = "admin";
$password = "admin";
$db = "suivi_prestation";
$port = 3306;

$con = mysqli_connect($host, $user, $password, $db, $port);
if (mysqli_connect_error()) {
    die("Probleme de connexion: " . mysqli_connect_error());
}

// Informations de l'administrateur
$matricule = "ADM001";
$username = "Jean";
$password = "admin123";
$role = "admin";

// Hacher le mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Supprimer l'utilisateur admin existant s'il y en a un
$delete_query = "DELETE FROM utilisateur WHERE username = '$username' AND role = '$role'";
mysqli_query($con, $delete_query);

// Insérer le nouvel utilisateur admin
$insert_query = "INSERT INTO utilisateur (matricule, username, password, role) VALUES ('$matricule', '$username', '$hashed_password', '$role')";

if (mysqli_query($con, $insert_query)) {
    echo "Utilisateur administrateur configuré avec succès !\n";
    echo "Vous pouvez maintenant vous connecter avec :\n";
    echo "Nom d'utilisateur: Jean\n";
    echo "Mot de passe: admin123\n";
    echo "Rôle: admin\n";
} else {
    echo "Erreur lors de la configuration de l'utilisateur : " . mysqli_error($con) . "\n";
}

mysqli_close($con);
?>