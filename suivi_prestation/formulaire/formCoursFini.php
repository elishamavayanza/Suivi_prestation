   <form action="script/addcoursfini.php" method="POST">
        <label for="">Section</label>
            <select name="section" id="section" class="txt">
               <?php
                 $sql ="select *from Section";
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
        <select name="matricule" id="matricule" class="txt">
                <?php
                $sql ="select *from enseignant";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['matriculeEnseignant'];?>"><?php echo $res['nom']." ".$res['postnom']." ".$res['prenom']; ?></option>
                    <?php  } 
                    ?>
            </select>
            <label for="">Descption </label> 
            <input type="text"name='description' class="txt">       
        <input type="submit" value="Cours Fini" class="btn">
    </form>