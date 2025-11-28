<?php
    require("../script/config.php");
    require("../fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,8,"MINISTERE D'ENSEIGNEMENT UNIVERSITAIRE ET SUPERIEUR ");
    $pdf->ln();
    $pdf->Cell(40,8,"INSTITUT SUPERIEUR PEDAGOGIQUE DE MUHANGI A BUTEMBO");
    $pdf->ln();
    $pdf->Cell(40,8,"CODE : 536 B.P 380 Butembo");
    $pdf->ln();
    ###<img src="../image/logo.jpg" alt="" srcset="">
    $pdf->Cell(40,8,"Secretariat academique ");
    $pdf->ln();
    $pdf->Cell(40,8,"Fiche de prestation");
    $pdf->ln();
    $pdf->Cell(40,8,"Intitule du Cours");
    $pdf->ln();
    $pdf->Cell(25,8,'Date',1);
    $pdf->Cell(55,8,'Matiere enseignee',1);
    $pdf->Cell(20,8,'Hr E',1);
    $pdf->Cell(20,8,'Heure S',1);
    $pdf->Cell(20,8,'H prestee',1);
    $pdf->Cell(25,8,'H cumulee',1);
    $pdf->Cell(15,8,'Sig cp',1);
    $pdf->Cell(15,8,'Sig En',1);
    $pdf->Ln();
  
    $sql= "SELECT contenufiche.datejoure as dtjr,contenufiche.contenu as cont,
   contenufiche.heureEntree as h_e ,contenufiche.heureSortie as h_s,contenufiche.nbreH as nbre,
   contenufiche.nbreH as c_h,contenufiche.signatureCp as sigcp,contenufiche.signatureEnseignant as sigens FROM contenufiche"; # WHERE identetefiche";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(array());
            #$rows = $stmt->fetch()
            while($row = $stmt->fetch()){
                $pdf->Cell(25,8,$row['dtjr'],1);
                $pdf->Cell(55,8,$row['cont'],1);
                $pdf->Cell(20,8,$row['h_e'],1);
                $pdf->Cell(20,8,$row['h_s'],1);
                $pdf->Cell(20,8,$row['nbre'],1);
                $pdf->Cell(25,8,$row['c_h'],1);                
                $pdf->Cell(15,8,$row['sigcp'],1);
                $pdf->Cell(15,8,$row['sigens'],1);
                $pdf->Ln();
           } 
    $pdf->Output();

