<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include("../../config/connexion.php");

// Fonction pour créer une nouvelle année universitaire
function creerNouvelleAnnee($pdo) {
    // Vérifier si une année existe déjà pour l'année en cours
    $current_year = date('Y');
    $next_year = $current_year + 1;
    $annee_description = $current_year . "-" . $next_year;
    
    // Vérifier si cette année existe déjà
    $check_query = "SELECT * FROM annee WHERE description = ?";
    $stmt = $pdo->prepare($check_query);
    $stmt->execute([$annee_description]);
    
    if($stmt->rowCount() == 0) {
        // Créer la nouvelle année (du 15 septembre de l'année en cours au 15 juillet de l'année suivante)
        $date_debut = $current_year . "-09-15";
        $date_fin = $next_year . "-07-15";
        
        $insert_query = "INSERT INTO annee(dt_debut, dt_fin, description) VALUES(?, ?, ?)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->execute([$date_debut, $date_fin, $annee_description]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Créer automatiquement une nouvelle année lors de l'ajout d'un horaire
    creerNouvelleAnnee($pdo);

    // Récupérer les données du formulaire
    $idcours = !empty($_POST['idcours']) ? $_POST['idcours'] : 'A définir';
    $jourheure = !empty($_POST['jourheure']) ? $_POST['jourheure'] : 'A définir';
    $codemention = !empty($_POST['codemention']) ? $_POST['codemention'] : 'A définir';
    $codepromotion = !empty($_POST['codepromotion']) ? $_POST['codepromotion'] : 'A définir';
    $enseignant = !empty($_POST['enseignant']) ? $_POST['enseignant'] : 'A définir';
    $site = !empty($_POST['site']) ? $_POST['site'] : '';
    $periode = !empty($_POST['periode']) ? $_POST['periode'] : 'A définir';
    $observation = !empty($_POST['observation']) ? $_POST['observation'] : '';
    $datejour = date('Y-m-d H:i:s');

    // Ajout de la valeur 0 pour le champ 'id' qui ne peut pas être NULL
    $sql = "INSERT INTO horaire(idcours,id,jourheure,codemention,codepromotion,enseignant,site,periode,observation,datejour) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$idcours, 0, $jourheure, $codemention, $codepromotion, $enseignant, $site, $periode, $observation, $datejour]);

    if($result){
        header("Location: ../horaire.php?message=Ajout réussi !");
    } else {
        header("Location: ../horaire.php?error=Échec de l'ajout !");
    }
} else {
    // Valeurs par défaut pour un nouvel horaire si accès direct
    creerNouvelleAnnee($pdo);

    // Valeurs par défaut pour un nouvel horaire
    $idcours = 'A définir';
    $jourheure = 'A définir';
    $codemention = 'A définir';
    $codepromotion = 'A définir';
    $enseignant = 'A définir';
    $site = '';
    $periode = 'A définir';
    $observation = '';
    $datejour = date('Y-m-d H:i:s');

    // Ajout de la valeur 0 pour le champ 'id' qui ne peut pas être NULL
    $sql = "INSERT INTO horaire(idcours,id,jourheure,codemention,codepromotion,enseignant,site,periode,observation,datejour) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$idcours, 0, $jourheure, $codemention, $codepromotion, $enseignant, $site, $periode, $observation, $datejour]);

    if($result){
        header("Location: ../horaire.php?message=Ajout réussi !");
    } else {
        header("Location: ../horaire.php?error=Échec de l'ajout !");
    }
}
exit();
?>