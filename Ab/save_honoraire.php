<?php
session_start();
require_once("../script/config.php");

// Vérifier si l'utilisateur est connecté et a le rôle AB
if (!isset($_SESSION['role']) || $_SESSION['role'] != "AB") {
    http_response_code(403);
    echo json_encode(array('status' => 'error', 'message' => 'Accès refusé'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fiche_id'])) {
    try {
        $ficheId = $_POST['fiche_id'];
        
        // Récupérer les informations de l'entête de fiche
        $sql = "SELECT * FROM entetefiche WHERE id = :ficheId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ficheId', $ficheId);
        $stmt->execute();
        $enteteFiche = $stmt->fetch();
        
        if (!$enteteFiche) {
            http_response_code(404);
            echo json_encode(array('status' => 'error', 'message' => 'Fiche non trouvée'));
            exit();
        }
        
        // Récupérer les détails de la fiche de prestation
        $sql = "SELECT SUM(nbreH) as total_heures FROM contenufiche WHERE identetefiche = :ficheId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ficheId', $ficheId);
        $stmt->execute();
        $totalHeures = $stmt->fetch()['total_heures'] ?? 0;
        
        // Calculer le montant total (exemple avec un taux horaire fixe de 50 USD)
        $tauxHoraire = 50;
        $montantTotal = $totalHeures * $tauxHoraire;
        
        // Vérifier si l'honoraire existe déjà
        $checkSql = "SELECT id FROM honoraire WHERE code_cours = :code_cours AND matricule_enseignant = :matricule_enseignant";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':code_cours', $enteteFiche['code_cours']);
        $checkStmt->bindParam(':matricule_enseignant', $enteteFiche['matricule_enseignant']);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            // Mettre à jour l'honoraire existant
            $updateSql = "UPDATE honoraire SET montant = :montant, devise = 'USD', description = CONCAT(description, ' - Mis à jour le ', NOW()) 
                          WHERE code_cours = :code_cours AND matricule_enseignant = :matricule_enseignant";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':montant', $montantTotal);
            $updateStmt->bindParam(':code_cours', $enteteFiche['code_cours']);
            $updateStmt->bindParam(':matricule_enseignant', $enteteFiche['matricule_enseignant']);
            $updateStmt->execute();
            
            echo json_encode(array(
                'status' => 'success', 
                'message' => 'Fiche d\'honoraires mise à jour avec succès',
                'montant' => $montantTotal,
                'heures' => $totalHeures
            ));
        } else {
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
            
            echo json_encode(array(
                'status' => 'success', 
                'message' => 'Fiche d\'honoraires générée avec succès',
                'montant' => $montantTotal,
                'heures' => $totalHeures,
                'honoraire_id' => $pdo->lastInsertId()
            ));
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('status' => 'error', 'message' => 'Erreur lors de la génération de la fiche d\'honoraires: ' . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'Requête invalide'));
}
?>