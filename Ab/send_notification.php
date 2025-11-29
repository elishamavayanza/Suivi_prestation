<?php
require_once("../script/config.php");

if (isset($_POST['honoraire_id'])) {
    $honoraireId = $_POST['honoraire_id'];
    
    try {
        // 获取酬金信息
        $sql = "SELECT h.*, e.nom, e.postnom, e.prenom, e.adresseMail, e.telephone, c.nomComplet as cours
                FROM honoraire h
                JOIN enseignant e ON h.matricule_enseignant = e.matriculeEnseignant
                JOIN cours c ON h.code_cours = c.code_cours
                WHERE h.id = :honoraireId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':honoraireId', $honoraireId);
        $stmt->execute();
        $honoraire = $stmt->fetch();
        
        if ($honoraire) {
            // 准备通知内容（实际项目中可以发送电子邮件或短信）
            $notification = array(
                'status' => 'success',
                'message' => 'Notification envoyée avec succès à ' . $honoraire['nom'] . ' ' . $honoraire['postnom'] . ' ' . $honoraire['prenom'],
                'email' => $honoraire['adresseMail'],
                'telephone' => $honoraire['telephone'],
                'details' => 'Bonjour ' . $honoraire['prenom'] . ', vous pouvez passer au bureau pour le retrait de votre salaire d\'un montant de ' . $honoraire['montant'] . ' ' . $honoraire['devise'] . ' pour le cours ' . $honoraire['cours']
            );
            
            // 在实际应用中，这里会发送电子邮件
            // mail($honoraire['adresseMail'], 'Paiement des honoraires', $notification['details']);
            
            // 更新通知状态
            $updateSql = "UPDATE honoraire SET description = CONCAT(description, ' - Notification envoyée le ', NOW()) WHERE id = :honoraireId";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':honoraireId', $honoraireId);
            $updateStmt->execute();
            
            echo json_encode($notification);
        } else {
            http_response_code(404);
            echo json_encode(array('status' => 'error', 'message' => 'Honoraires non trouvés'));
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('status' => 'error', 'message' => 'Erreur lors de l\'envoi de la notification: ' . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'ID d\'honoraires requis'));
}
?>