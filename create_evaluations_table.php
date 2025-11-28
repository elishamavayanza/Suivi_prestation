<?php
// Script pour créer la table evaluations manquante

include("script/connexion.php");

$sql = "CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matriculeEtudiant VARCHAR(50),
    enseignant VARCHAR(255),
    cours VARCHAR(255),
    r1 INT,
    r2 INT,
    r3 INT,
    r4 INT,
    r5 INT,
    r6 INT,
    r7 INT,
    r8 INT,
    r9 INT,
    r10 INT,
    r11 INT,
    r12 INT,
    r13 INT,
    r14 INT,
    r15 INT,
    r16 INT,
    r17 INT,
    r18 INT,
    r19 INT,
    r20 INT,
    commentaire TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($con, $sql)) {
    echo "Table 'evaluations' créée avec succès";
} else {
    echo "Erreur lors de la création de la table: " . mysqli_error($con);
}

mysqli_close($con);
?>