<?php //
//    include("menu.php");
//?>
<!--<div class="wrapper container-body">-->
<!--<div class="content-prestation">-->
<!--    <div class="form-prestation">-->
<!--        --><?php //
//                include("formulaire/formdescriptioncours.php");
//            ?>
<!--    </div>-->
<!--    <div classe="prestation">-->
<!--            <table class="contente-table">-->
<!--                <select name="entetefiche" id="fiche" class="txt">-->
<!--                    --><?php
//                    $sql ="SELECT  entetefiche.id as entete,cours.id as idcours,cours.nomComplet as noms from entetefiche,cours WHERE cours.id = entetefiche.code_cours";
//                    $stmt = $pdo->prepare($sql);
//                    $stmt->execute(array());
//                    $res =null;
//                        while($res=$stmt->fetch()){
//                            ?>
<!--                           -->
<!--                            <option  value="--><?php
//                             $codecours = $res['idcours'];
//                             echo $res['entete'];?><!--">--><?php //echo $res['noms']; ?><!--</option>-->
<!--                        --><?php // }
//                        ?>
<!--                </select>-->
<!--               <thead>-->
<!--                    <td>-->
<!--                        Date-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        Objectifs du cours-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        contenu du cours-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        Methodes de transmission du cours-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        Ressource de la notice Pedagogique-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        Nature de travaux ppratique-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        Strategies d'evaluations-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        Notice bibliographique-->
<!--                    </td>-->
<!--               </thead>-->
<!--                --><?php
//           # $id =$_POST['entetefiche'];
//            $sql= "SELECT * FROM descriptionfiche WHERE code_cours='$codecours'";
//            $stmt=$pdo->prepare($sql);
//            $stmt->execute(array());
//            while($row = $stmt->fetch()){
//            ?>
<!--            <tr>-->
<!--                <td>--><?php //echo $row['dt'];?><!--</td>-->
<!--                <td>--><?php //echo $row['objectif'];?><!--</td>-->
<!--                <td>--><?php //echo $row['contenu'];?><!--</td>-->
<!--                <td>--><?php //echo $row['methode'];?><!--</td>-->
<!--                <td>--><?php //echo $row['ressource'];?><!--</td>-->
<!--                <td>--><?php //echo $row['nature'];?><!--</td>-->
<!--                <td>--><?php //echo $row['evaluation'];?><!--</td>-->
<!--                <td>--><?php //echo $row['bibliographie'];?><!--</td>-->
<!--            </tr>-->
<!--            --><?php //} ?>
<!--                <tfoot>-->
<!--                     <td colspan="8">-->
<!--                        <button class="btn"><a href="print/fichedescription.php">Imprimer</a></button>-->
<!--                     </td>-->
<!--                </tfoot>-->
<!--            </table>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--</div>-->
<?php //
//    include("footer.php");
//?>