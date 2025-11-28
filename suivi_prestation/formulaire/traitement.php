<?php
$host = "localhost";
$dbname = "db_evaluation";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Connexion échouée : " . $conn->connect_error); }

$nom_etudiant = $_POST['nom_etudiant'];
$enseignant = $_POST['enseignant'];
$cours =$_POST['cours'];
$commentaire = $_POST['commentaire'];

// Récupération dynamique des 20 notes
$notes = [];
for ($i=1; $i<=20; $i++) {
    $notes[$i] = $_POST["r$i"];
}

$sql = "INSERT INTO evaluations (nom_etudiant, enseignant,cours, r1,r2,r3,r4,r5,r6,r7,r8,r9,r10,r11,r12,r13,r14,r15,r16,r17,r18,r19,r20, commentaire)
        VALUES (?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssiiiiiiiiiiiiiiiiiiii",
    $nom_etudiant, $enseignant,$cours
    $notes[1], $notes[2], $notes[3], $notes[4], $notes[5],
    $notes[6], $notes[7], $notes[8], $notes[9], $notes[10],
    $notes[11], $notes[12], $notes[13], $notes[14], $notes[15],
    $notes[16], $notes[17], $notes[18], $notes[19], $notes[20],
    $commentaire
);

if ($stmt->execute()) {
    echo "<h3>✅ Évaluation enregistrée</h3>";
} else {
    echo "❌ Erreur : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
