<?php
require("../script/config.php");
require("../fpdf/fpdf.php");

// Récupérer les paramètres
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : date('Y-m-01');
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : date('Y-m-t');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

// En-tête du rapport
$pdf->Cell(0,10,"MINISTERE D'ENSEIGNEMENT UNIVERSITAIRE ET SUPERIEUR",0,1,'C');
$pdf->Cell(0,10,"INSTITUT SUPERIEUR PEDAGOGIQUE DE MUHANGI A BUTEMBO",0,1,'C');
$pdf->Cell(0,10,"RAPPORT DES FICHES DE PRESTATION",0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Période du ".date('d/m/Y', strtotime($date_debut))." au ".date('d/m/Y', strtotime($date_fin)),0,1,'C');
$pdf->Ln(10);

try {
    // Rapport sur les fiches de prestation
    $sql_data = "SELECT ef.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom, e.prenom as enseignant_prenom, p.nomComplet as promotion_nom, m.nomComplet as mention_nom
                  FROM entetefiche ef 
                  JOIN cours c ON ef.code_cours = c.code_cours 
                  JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                  JOIN promotion p ON ef.code_promotion = p.sigle_promotion
                  JOIN mention m ON ef.code_mention = m.code_mention
                  WHERE DATE(ef.datecreation) BETWEEN ? AND ?
                  ORDER BY ef.datecreation DESC";
    $stmt_data = $pdo->prepare($sql_data);
    $stmt_data->execute([$date_debut, $date_fin]);
    $rapport_data = $stmt_data->fetchAll();
    
    // Compteur
    $pdf->Cell(0,8,"Nombre total de fiches: ".count($rapport_data),0,1);
    $pdf->Ln(5);
    
    if (count($rapport_data) > 0) {
        // Titres des colonnes
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,8,"ID",1);
        $pdf->Cell(25,8,"Date",1);
        $pdf->Cell(45,8,"Cours",1);
        $pdf->Cell(45,8,"Enseignant",1);
        $pdf->Cell(30,8,"Promotion",1);
        $pdf->Cell(25,8,"Statut",1);
        $pdf->Ln();
        
        // Données
        $pdf->SetFont('Arial','',8);
        foreach ($rapport_data as $item) {
            $pdf->Cell(10,8,$item['id'],1);
            $pdf->Cell(25,8,date('d/m/Y', strtotime($item['datecreation'])),1);
            $pdf->Cell(45,8,substr($item['cours_nom'],0,25),1);
            $pdf->Cell(45,8,substr($item['enseignant_nom'].' '.$item['enseignant_postnom'],0,25),1);
            $pdf->Cell(30,8,substr($item['promotion_nom'],0,15),1);
            $pdf->Cell(25,8,$item['statut'],1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0,8,"Aucune donnée disponible pour cette période.",0,1);
    }
} catch (Exception $e) {
    $pdf->Cell(0,10,"Erreur lors de la génération du rapport: ".$e->getMessage());
}

$pdf->Output();
?>