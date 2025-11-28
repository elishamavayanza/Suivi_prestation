<?php 
    include("menu.php");
    include("script/config.php");
?>
<div class="wrapper container-body">
<div class="content-prestation">
    <div class="form-prestation">
        <?php 
                include("formulaire/formPrestation.php");
            ?>
    </div>
    <div classe="prestation">
        <table class="contente-table">
            <select name="entetefiche" id="fiche" class="txt">
                    <?php
                    $sql ="SELECT entetefiche.id as entete,cours.id as idcours,cours.nomComplet as noms from entetefiche,cours WHERE cours.id=entetefiche.code_cours";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array());
                    $res =null;
                        while($res=$stmt->fetch()){
                            ?>
                            <option  value="<?php 
                            $code = $res['entete'];
                            echo $res['entete'];?>"><?php echo $res['noms']; ?></option>
                        <?php  } 
                        ?>
                </select>
            <tr class="contente-ligne">
                <td>Date</td>
                <td>Contenu</td>
                <td>H entree</td>
                <td>H sortie</td>
                <td>Nbre H</td>
                <td>Signature cp</td>
                <td>Signature enseignant</td>
            </tr>
             <?php
           # $id =$_POST['entetefiche']; 
            $sql= "SELECT * FROM contenufiche WHERE identetefiche='$code'";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(array());
            while($row = $stmt->fetch()){
            ?>
            <tr>
                <td><?php echo $row['datejoure'];?></td>
                <td><?php echo $row['contenu'];?></td>
                <td><?php echo $row['heureEntree'];?></td>
                <td><?php echo $row['heureSortie'];?></td>
                <td><?php echo $row['nbreH'];?></td>
                <td><?php echo $row['signatureCP'];?></td>
                <td><?php echo $row['signatureEnseignant'];?></td>
            </tr>
            <?php } ?>
                <tfoot>
                     <td colspan="7">
                        <button class="btn"><a href="print/ficheprestation.php">Imprimer</a></button>
                     </td>
                </tfoot>
        </table>
    </div>
</div>

</div>
<?php 
    include("footer.php");
?>