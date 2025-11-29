<?php
require_once("../script/config.php");

if (isset($_POST['fiche_id'])) {
    $ficheId = $_POST['fiche_id'];
    
    // Récupérer les détails de la fiche de prestation
    $sql = "SELECT contenufiche.datejoure as dtjr, contenufiche.contenu as cont,
            contenufiche.nbreH as nbre
            FROM contenufiche 
            WHERE identetefiche = :ficheId";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ficheId', $ficheId);
    $stmt->execute();
    
    $results = array();
    while ($row = $stmt->fetch()) {
        // Ajouter le taux horaire fixe (à adapter selon vos besoins)
        $row['taux_horaire'] = 50; // Exemple de taux horaire en dollars
        $row['total'] = $row['nbre'] * $row['taux_horaire'];
        $results[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>