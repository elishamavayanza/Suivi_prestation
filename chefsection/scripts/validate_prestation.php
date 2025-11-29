<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ficheId'])) {
    $ficheId = $_POST['ficheId'];
    
    try {
        // Mettre à jour la fiche de prestation comme validée
        $sql = "UPDATE contenufiche SET signatureCP = 'OK' WHERE identetefiche = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ficheId]);
        
        $_SESSION['success_message'] = "Fiche de prestation validée avec succès.";
        header("Location: ../prestation.php?success=1");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur lors de la validation : " . $e->getMessage();
        header("Location: ../prestation.php?error=1");
        exit();
    }
} else {
    header("Location: ../prestation.php");
    exit();
}
?>