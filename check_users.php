<?php
include("script/connexion.php");

$query = "SELECT * FROM utilisateur";
$result = mysqli_query($con, $query);

echo "Liste des utilisateurs :\n";
echo "=====================\n";

while ($user = mysqli_fetch_assoc($result)) {
    echo "ID: " . $user['id'] . "\n";
    echo "Matricule: " . $user['matricule'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "Role: " . $user['role'] . "\n";
    echo "Password (hash): " . $user['password'] . "\n";
    echo "------------------------\n";
}
?>