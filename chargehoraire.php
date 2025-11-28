<?php //
//    include("menu.php");
//?>
<!--<div class="wrapper container-body">-->
<!--<div class="content-prestation">-->
<!--    <div class="form-prestation">-->
<!--        --><?php //
//                include("formulaire/formChargeHoraire.php");
//            ?>
<!--    </div>-->
<!--    <div classe="prestation">-->
<!--        <table class="contente-table">-->
<!--            <select name="matricule" id="" class="txt">-->
<!--                --><?php
//                $sql ="select *from Enseignant";
//                $stmt = $pdo->prepare($sql);
//                $stmt->execute(array());
//                $res =null;
//                    while($res=$stmt->fetch()){
//                        ?>
<!--                        <option  value="--><?php //echo $res['matriculeEnseignant'];?><!--">--><?php //echo $res['nom'].' '.$res['postnom'].' '.$res['prenom']; ?><!--</option>-->
<!--                    --><?php // }
//                    ?>
<!--            </select>-->
<!--            <tr>-->
<!--                <td>Code cours</td>-->
<!--                <td>Cours</td>-->
<!--                <td>Nbre Heure</td>-->
<!--                <td>Max point</td>-->
<!--                <td>Section</td>-->
<!--                <td>Mention</td>-->
<!--            </tr>-->
<!--             --><?php
//           # $id =$_POST['entetefiche'];
//            $sql= "SELECT cours.code_cours as code,cours.nomComplet as nom,nbreHeure as heure,cours.ponderation as max, section.nomComplet as nomsection,mention.nomComplet as nommention  FROM cours,section,mention WHERE cours.code_section =section.code_section and cours.code_mention=mention.code_mention";
//            $stmt=$pdo->prepare($sql);
//            $stmt->execute(array());
//            while($row = $stmt->fetch()){
//            ?>
<!--            <tr>-->
<!--                <td>--><?php //echo $row['code'];?><!--</td>-->
<!--                <td>--><?php //echo $row['nom'];?><!--</td>-->
<!--                <td>--><?php //echo $row['heure'];?><!--</td>-->
<!--                <td>--><?php //echo $row['max'];?><!--</td>-->
<!--                <td>--><?php //echo $row['nomsection'];?><!--</td>-->
<!--                <td>--><?php //echo $row['nommention'];?><!--</td>-->
<!--               -->
<!--            </tr>-->
<!--            --><?php //} ?>
<!--                <tfoot>-->
<!--                     <td colspan="7">-->
<!--                        <button class="btn">Imprimer</button>-->
<!--                     </td>-->
<!--                </tfoot>-->
<!--        </table>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--</div>-->
<?php //
//    include("footer.php");
//?>