<?php 
    include("menu.php");
    include("script/config.php");
?>
<div class="wrapper container-body">
<div class="content-prestation">
    <div class="form-prestation">
        <?php 
                include("formulaire/formCoursFini.php");
            ?>
    </div>
    <div classe="prestation">
        <table class="contente-table">
            <tr class="contente-ligne">
                <td>Date</td>
                <td>Cours</td>
                <td>Nbre Heure</td>
                <td>Enseignant</td>
                <td>Description</td>
                <td>Section</td>
                <td>Promotion</td>
            </tr>
        <?php
           # $id =$_POST['entetefiche']; 
           $matri= $_SESSION['matricule'];
            $sql= "SELECT concat_ws(enseignant.nom,enseignant.postnom) as nomens promotion.nomComplet as prom , coursfini.matricule as matri, cours.code_cours as code,cours.nomComplet as nom,nbreHeure as heure,cours.ponderation as max, section.nomComplet as nomsection,mention.nomComplet as nommention  FROM cours,section,mention, coursfini, promotion WHERE cours.code_section =section.code_section and cours.code_mention=mention.code_mention and coursfini.matricule =utilisateur.matricule and cours.id=chargehoraire.codecours and promotion.id=chargehoraire.codepromotion";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(array());
            while($row = $stmt->fetch()){
            ?>
            <tr>
                <td><?php echo $row['code'];?></td>
                <td><?php echo $row['nom'];?></td>
                <td><?php echo $row['heure'];?></td>
                <td><?php echo $row['nomens'];?></td>
                <td><?php echo $row['nomsection'];?></td>
                <td><?php echo $row['nommention'];?></td>
                <td><?php echo $row['prom'];?></td>
               
               
            </tr>
            <?php } ?>
                <tfoot>
                     <td colspan="7">
                        <button class="btn">Imprimer</button>
                     </td>
                </tfoot>
        </table>
    </div>
</div>

</div>
<?php 
    include("footer.php");
?>