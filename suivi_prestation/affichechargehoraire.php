<?php 
    include("menu.php");
?>
<div class="wrapper container-body">
<div class="content-prestation">
    
    <div classe="prestation">
        <table class="contente-table">
            <tr>
                <td>Code cours</td>
                <td>Cours</td>
                <td>Nbre Heure</td>
                <td>Max point</td>
                <td>Section</td>
                <td>Mention</td>
                <td>Promotion</td>
            </tr>
             <?php
           # $id =$_POST['entetefiche']; 
            $matri= $_SESSION['matricule'];
            $sql= "SELECT promotion.nomComplet as prom , chargehoraire.matricule as matri, cours.code_cours as code,cours.nomComplet as nom,nbreHeure as heure,cours.ponderation as max, section.nomComplet as nomsection,mention.nomComplet as nommention  FROM cours,section,mention, chargehoraire, promotion WHERE cours.code_section =section.code_section and cours.code_mention=mention.code_mention and chargehoraire.matricule ='$matri' and cours.id=chargehoraire.codecours and promotion.id=chargehoraire.codepromotion";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(array());
            while($row = $stmt->fetch()){
            ?>
            <tr>
                <td><?php echo $row['code'];?></td>
                <td><?php echo $row['nom'];?></td>
                <td><?php echo $row['heure'];?></td>
                <td><?php echo $row['max'];?></td>
                <td><?php echo $row['nomsection'];?></td>
                <td><?php echo $row['nommention'];?></td>
                <td><?php echo $row['prom'];?></td>              
               
            </tr>
            <?php 
                } ?>
                <tfoot>
                     <td colspan="7">
                        <button class="btn"><a href="print/chargehoraire.php?mat=<?$matri;?>">Imprimer</a></button>
                     </td>
                </tfoot>
        </table>
    </div>
</div>

</div>
<?php 
    include("footer.php");
?>