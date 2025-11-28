
    <div class="formulaire">
        <form id="connexionForm" action="../script/inscrire.php" method="POST">
            <label for="fac">Section : </label>
            <select name="Section" id="fac">
            <?php
                 $sql ="select *from Section";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['code_fac'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
            <label for="depart">Option : </label>
            <select name="dapartement" id="depart">
                 <?php
                 $sql ="select *from Option";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){                 
                        ?>
                        <option  value="<?php echo $res['code_depart'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
            <label for="promotion">Promotion : </label>
            <select name="promotion" id="promotion">
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
            <label for="matricule">MAtricule</label>
            <input type="text" id="matricule" name="matricule" required placeholder="">
            <label for="date">Date inscription : </label>
            <input type="date" id="date" name="dtinscription" required placeholder="">
            <label for="description">Description : </label>
            <input type="text" name="description" id="description">
            <button type="submit">Inscrire</button>
        </form>
    </div>
