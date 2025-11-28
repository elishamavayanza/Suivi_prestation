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
    $pdf->Cell(40,10,"Fiche de Charge horaire");
    $pdf->ln();
    $pdf->Cell(40,10,"A IDENTITE : ");
    $pdf->ln();
    $pdf->Cell(40,10,'Noms du titutaire :');
    $pdf->ln();
    $pdf->Cell(40,10,"B Charge Horaire ");
    $pdf->ln();
    $pdf->Cell(25,8,'Code E.U',1);
    $pdf->Cell(50,8,'Intitule U.E',1);
    $pdf->Cell(40,8,'Intitule E.C.U.E.',1);
    $pdf->Cell(20,8,'Promotion',1);
   # $pdf->Cell(20,8,'Semestre',1);
    $pdf->Cell(25,8,'V. Horaire',1);
    #$pdf->Cell(25,8,'Date du cours',1);
    $pdf->ln();
          # $m =$_POST['mat'];
            $sql= "SELECT promotion.nomComplet as prom , chargehoraire.matricule as matri, cours.code_cours as code,cours.nomComplet as nom,nbreHeure as heure,cours.ponderation as max, section.nomComplet as nomsection,mention.nomComplet as nommention  FROM cours,section,mention, chargehoraire, promotion"; #WHERE cours.code_section =section.code_section and cours.code_mention=mention.code_mention and chargehoraire.matricule ='$m' and cours.id=chargehoraire.codecours and promotion.id=chargehoraire.codepromotion";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(array());
            while($row = $stmt->fetch()){
                $pdf->Cell(25,8,$row['code'],1);
                $pdf->Cell(50,8,$row['nom'],1);
                $pdf->Cell(40,8,$row['nom'],1);
                $pdf->Cell(20,8,$row['prom'],1);
                $pdf->Cell(20,8,$row['heure'],1);
                #$pdf->Cell(25,8,$row['c_h'],1);                
                #$pdf->Cell(15,8,$row['sigcp'],1);
                #$pdf->Cell(15,8,$row['sigens'],1);
                $pdf->Ln();
            } 

    $pdf->Output();

