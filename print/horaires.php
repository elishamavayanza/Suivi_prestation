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
$pdf->Cell(0,10,"RAPPORT DES HORAIRES",0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Période du ".date('d/m/Y', strtotime($date_debut))." au ".date('d/m/Y', strtotime($date_fin)),0,1,'C');
$pdf->Ln(10);

try {
    // Rapport sur les horaires
    $sql_data = "SELECT h.*, c.nomComplet as cours_nom, COALESCE(e.nom, h.enseignant) as enseignant_nom, COALESCE(CONCAT(e.postnom, ' ', e.prenom), '') as enseignant_autres, p.nomComplet as promotion_nom, m.nomComplet as mention_nom
                  FROM horaire h
                  JOIN cours c ON h.idcours = c.code_cours
                  LEFT JOIN enseignant e ON h.enseignant = e.matriculeEnseignant
                  JOIN promotion p ON h.codepromotion = p.sigle_promotion
                  JOIN mention m ON h.codemention = m.code_mention
                  WHERE DATE(h.datejour) BETWEEN ? AND ?
                  ORDER BY h.datejour DESC";
    $stmt_data = $pdo->prepare($sql_data);
    $stmt_data->execute([$date_debut, $date_fin]);
    $rapport_data = $stmt_data->fetchAll();
    
    // Compteur
    $pdf->Cell(0,8,"Nombre total d'horaires: ".count($rapport_data),0,1);
    $pdf->Ln(5);
    
    if (count($rapport_data) > 0) {
        // Titres des colonnes
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(25,8,"Date",1);
        $pdf->Cell(35,8,"Heure",1);
        $pdf->Cell(45,8,"Cours",1);
        $pdf->Cell(40,8,"Enseignant",1);
        $pdf->Cell(25,8,"Promotion",1);
        $pdf->Cell(20,8,"Site",1);
        $pdf->Ln();
        
        // Données
        $pdf->SetFont('Arial','',8);
        foreach ($rapport_data as $item) {
            $pdf->Cell(25,8,date('d/m/Y', strtotime($item['datejour'])),1);
            $pdf->Cell(35,8,substr($item['jourheure'],0,20),1);
            $pdf->Cell(45,8,substr($item['cours_nom'],0,25),1);
            $pdf->Cell(40,8,substr(($item['enseignant_nom'] ?? $item['enseignant']).' '.($item['enseignant_autres'] ?? ''),0,20),1);
            $pdf->Cell(25,8,substr($item['promotion_nom'],0,15),1);
            $pdf->Cell(20,8,substr($item['site'],0,10),1);
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