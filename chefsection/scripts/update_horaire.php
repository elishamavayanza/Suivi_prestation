<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include("../../config/connexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id']) && !empty($_POST['idcours']) && !empty($_POST['jourheure']) && !empty($_POST['codemention']) && !empty($_POST['codepromotion']) && !empty($_POST['enseignant']) && isset($_POST['site']) && !empty($_POST['periode'])) {
        $id = $_POST['id'];
        $idcours = $_POST['idcours'];
        $jourheure = $_POST['jourheure'];
        $codemention = $_POST['codemention'];
        $codepromotion = $_POST['codepromotion'];
        $enseignant = $_POST['enseignant'];
        $site = $_POST['site'];
        $periode = $_POST['periode'];
        $observation = !empty($_POST['observation']) ? $_POST['observation'] : '';
        $datejour = !empty($_POST['datejour']) ? $_POST['datejour'] : date('Y-m-d');
        
        // Mettre à jour l'horaire
        $sql = "UPDATE horaire SET idcours=?, id=0, jourheure=?, codemention=?, codepromotion=?, enseignant=?, site=?, periode=?, observation=?, datejour=? WHERE idhoraire=?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$idcours, $jourheure, $codemention, $codepromotion, $enseignant, $site, $periode, $observation, $datejour, $id]);

        if($result){
            header("Location: ../horaire.php?message=Modification réussie !");
        } else {
            header("Location: ../horaire.php?error=Échec de modification !");
        }
    } else {
        header("Location: ../horaire.php?error=Veuillez remplir tous les champs requis !");
    }
} else {
    header("Location: ../horaire.php");
}
exit();
?>