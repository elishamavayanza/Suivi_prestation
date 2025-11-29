<?php
require("../script/config.php");
require("../fpdf/fpdf.php");

// Récupérer les paramètres
$type_rapport = isset($_GET['type_rapport']) ? $_GET['type_rapport'] : 'prestations';
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : date('Y-m-01');
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : date('Y-m-t');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

// En-tête du rapport
$pdf->Cell(0,10,"MINISTERE D'ENSEIGNEMENT UNIVERSITAIRE ET SUPERIEUR",0,1,'C');
$pdf->Cell(0,10,"INSTITUT SUPERIEUR PEDAGOGIQUE DE MUHANGI A BUTEMBO",0,1,'C');
$pdf->Cell(0,10,"RAPPORT DE PRESTATIONS",0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Période du ".date('d/m/Y', strtotime($date_debut))." au ".date('d/m/Y', strtotime($date_fin)),0,1,'C');
$pdf->Ln(10);

try {
    if ($type_rapport == 'prestations') {
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
        
        // Titres des colonnes
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,8,"ID",1);
        $pdf->Cell(25,8,"Date",1);
        $pdf->Cell(40,8,"Cours",1);
        $pdf->Cell(40,8,"Enseignant",1);
        $pdf->Cell(30,8,"Promotion",1);
        $pdf->Cell(25,8,"Statut",1);
        $pdf->Ln();
        
        // Données
        $pdf->SetFont('Arial','',8);
        foreach ($rapport_data as $item) {
            $pdf->Cell(10,8,$item['id'],1);
            $pdf->Cell(25,8,date('d/m/Y', strtotime($item['datecreation'])),1);
            $pdf->Cell(40,8,substr($item['cours_nom'],0,20),1);
            $pdf->Cell(40,8,substr($item['enseignant_nom'].' '.$item['enseignant_postnom'],0,20),1);
            $pdf->Cell(30,8,substr($item['promotion_nom'],0,15),1);
            $pdf->Cell(25,8,$item['statut'],1);
            $pdf->Ln();
        }
    } elseif ($type_rapport == 'horaires') {
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
        
        // Titres des colonnes
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20,8,"Date",1);
        $pdf->Cell(30,8,"Heure",1);
        $pdf->Cell(40,8,"Cours",1);
        $pdf->Cell(40,8,"Enseignant",1);
        $pdf->Cell(30,8,"Promotion",1);
        $pdf->Cell(20,8,"Site",1);
        $pdf->Ln();
        
        // Données
        $pdf->SetFont('Arial','',8);
        foreach ($rapport_data as $item) {
            $pdf->Cell(20,8,date('d/m/Y', strtotime($item['datejour'])),1);
            $pdf->Cell(30,8,substr($item['jourheure'],0,15),1);
            $pdf->Cell(40,8,substr($item['cours_nom'],0,20),1);
            $pdf->Cell(40,8,substr(($item['enseignant_nom'] ?? $item['enseignant']).' '.($item['enseignant_autres'] ?? ''),0,20),1);
            $pdf->Cell(30,8,substr($item['promotion_nom'],0,15),1);
            $pdf->Cell(20,8,substr($item['site'],0,10),1);
            $pdf->Ln();
        }
    }
} catch (Exception $e) {
    $pdf->Cell(0,10,"Erreur lors de la génération du rapport: ".$e->getMessage());
}

$pdf->Output();
?>