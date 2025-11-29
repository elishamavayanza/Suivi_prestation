<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est CP
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Chefpromotion') {
    header("Location: ../login.php");
    exit();
}

include("../script/config.php");

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données du formulaire
        $cours = $_POST['cours'];
        $enseignant = $_POST['enseignant'];
        $section = $_POST['section'];
        $mention = $_POST['mention'];
        $promotion = $_POST['promotion'];
        $volume_horaire = !empty($_POST['volume_horaire']) ? $_POST['volume_horaire'] : null;
        $heures_reelles = !empty($_POST['heures_reelles']) ? $_POST['heures_reelles'] : null;
        $date_debut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : null;
        $date_fin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : null;
        $objectifs = !empty($_POST['objectifs']) ? $_POST['objectifs'] : null;
        $description = !empty($_POST['description']) ? $_POST['description'] : null;
        
        // Insérer la nouvelle fiche de prestation
        $sql_insert = "INSERT INTO entetefiche (code_cours, matricule_enseignant, code_section, code_mention, code_promotion, volume_horaire_prevu, heures_reelles_prestees, date_debut_prevue, date_fin_prevue, objectifs, description, datecreation) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$cours, $enseignant, $section, $mention, $promotion, $volume_horaire, $heures_reelles, $date_debut, $date_fin, $objectifs, $description]);
        
        // Récupérer l'ID de la fiche créée
        $fiche_id = $pdo->lastInsertId();
        
        // Message de succès
        $success_message = "Fiche de prestation créée avec succès avec l'ID: " . $fiche_id;
        
        // Rediriger vers la page de gestion des prestations
        header("Location: prestation.php?success=" . urlencode($success_message));
        exit();
        
    } catch (Exception $e) {
        // Message d'erreur
        $error_message = "Erreur lors de la création de la fiche de prestation: " . $e->getMessage();
        
        // Rediriger vers le formulaire avec l'erreur
        header("Location: form_prestation_cp.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers le formulaire
    header("Location: form_prestation_cp.php");
    exit();
}
?>