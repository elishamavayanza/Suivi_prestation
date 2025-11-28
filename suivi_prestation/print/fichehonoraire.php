<?php
    require("../script/config.php");
    require("../fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,"MINISTERE D'ENSEIGNEMENT UNIVERSITAIRE ET SUPERIEUR ");
    $pdf->ln();
    $pdf->Cell(40,10,"INSTITUT SUPERIEUR PEDAGOGIQUE DE MUHANGI A BUTEMBO");
    $pdf->ln();
    $pdf->Cell(40,10,"CODE : 536 B.P 380 Butembo");
    $pdf->ln();
    ###<img src="../image/logo.jpg" alt="" srcset="">
    $pdf->Cell(40,10,"Secretariat academique ");
    $pdf->ln();
    $pdf->Cell(40,10,"Fiche des honoraire");
    $pdf->ln();
    
    
    $pdf->Output();

