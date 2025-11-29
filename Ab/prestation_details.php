<?php
require_once("../script/config.php");

if (isset($_GET['id'])) {
    $ficheId = $_GET['id'];
    
    $sql = "SELECT contenufiche.datejoure as dtjr, contenufiche.contenu as cont,
            contenufiche.heureEntree as h_e, contenufiche.heureSortie as h_s, 
            contenufiche.nbreH as nbre, contenufiche.signatureCp as sigcp, 
            contenufiche.signatureEnseignant as sigens 
            FROM contenufiche 
            WHERE identetefiche = :ficheId";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ficheId', $ficheId);
    $stmt->execute();
    
    $results = array();
    while ($row = $stmt->fetch()) {
        $results[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>