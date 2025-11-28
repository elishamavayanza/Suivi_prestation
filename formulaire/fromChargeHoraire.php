<div>
    <form action="script/addchargehoraire.php" method="POST">
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
            <label for="">Observation</label>
            <input type="text" name="observation" id="">
    </form>
</div>