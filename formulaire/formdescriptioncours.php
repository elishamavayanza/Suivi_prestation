<div>
    <form action="script/addDescription.php" method="POST">
        <label for="">Section</label>
            <select name="section" id="section" class="txt">
               <?php
                 $sql ="select *from section";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['code_section'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
        <label for="">Mention</label>
            <select name="mention" id="mention" class="txt">
                <?php
                 $sql ="select *from mention";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){ ?>
                        <option  value="<?php echo $res['code_mention'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
        <label for="">Courrs</label>
        <select name="cours" id="cours" class="txt">
                <?php
                $sql ="select *from cours";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['id'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
        <label for="">Promotion</label>
        <select name="promotion" id="promotion" class="txt">
                <?php
                $sql ="select *from promotion";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['id'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
        <label for="">Enseignant</label>
        <select name="matricule" id="" class="txt">
                <?php
                $sql ="select *from Enseignant";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['matriculeEnseignant'];?>"><?php echo $res['nom'].' '.$res['postnom'].' '.$res['prenom']; ?></option>
                    <?php  } 
                    ?>
            </select>
        <label for="">Date</label>
        <input type="date" name="dt" id="" class="txt">
        <label for="">Objectifs du cours</label>
        <input type="text" name="objectif" id="" class="txt">
        <label for="">Contenu du cours </label>
        <input type="text" name="contenu" id="" class="txt">
        <label for="">Methode de transmission du cours</label>
        <input type="text" name="methode" id="" class="txt">
        <label for="">Ressource de la notice Pedagogique</label>
        <input type="text" name="ressource" id="" class="txt">
        <label for="">Nature de travaux ppratique</label>
        <input type="text" name="nature" id="" class="txt">
        <label for="">Strategies d'evaluations</label>
        <input type="text" name="evaluation" id="" class="txt">
        <label for="">Notice bibliographique</label>
        <textarea name="biblio" id="" class="txt"></textarea>
        <input type="submit" value="Commpleter" class="btn">
    </form>
</div>