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
    
    // Sauvegarder les honoraires dans la base de données
    try {
        // Récupérer les informations de l'entête de fiche
        $enteteSql = "SELECT * FROM entetefiche WHERE id = :ficheId";
        $enteteStmt = $pdo->prepare($enteteSql);
        $enteteStmt->bindParam(':ficheId', $ficheId);
        $enteteStmt->execute();
        $enteteFiche = $enteteStmt->fetch();
        
        if ($enteteFiche) {
            // Calculer le montant total
            $totalHeures = array_sum(array_column($results, 'nbre'));
            $tauxHoraire = 50;
            $montantTotal = $totalHeures * $tauxHoraire;
            
            // Vérifier si l'honoraire existe déjà
            $checkSql = "SELECT id FROM honoraire WHERE code_cours = :code_cours AND matricule_enseignant = :matricule_enseignant";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->bindParam(':code_cours', $enteteFiche['code_cours']);
            $checkStmt->bindParam(':matricule_enseignant', $enteteFiche['matricule_enseignant']);
            $checkStmt->execute();
            
            if (!$checkStmt->fetch()) {
                // Insérer une nouvelle fiche d'honoraire
                $insertSql = "INSERT INTO honoraire (code_section, code_mention, code_promotion, code_cours, matricule_enseignant, montant, devise, description) 
                              VALUES (:code_section, :code_mention, :code_promotion, :code_cours, :matricule_enseignant, :montant, 'USD', :description)";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->bindParam(':code_section', $enteteFiche['code_section']);
                $insertStmt->bindParam(':code_mention', $enteteFiche['code_mention']);
                $insertStmt->bindParam(':code_promotion', $enteteFiche['code_promotion']);
                $insertStmt->bindParam(':code_cours', $enteteFiche['code_cours']);
                $insertStmt->bindParam(':matricule_enseignant', $enteteFiche['matricule_enseignant']);
                $insertStmt->bindParam(':montant', $montantTotal);
                $description = "Honoraires générés à partir de la fiche de prestation #" . $ficheId . " - " . date('Y-m-d H:i:s');
                $insertStmt->bindParam(':description', $description);
                $insertStmt->execute();
            }
        }
    } catch (Exception $e) {
        // Ne pas interrompre le processus en cas d'erreur de sauvegarde
        error_log("Erreur lors de la sauvegarde des honoraires: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>