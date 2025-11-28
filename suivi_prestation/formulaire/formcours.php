
    <div class="formulaire">
        
        <form id="connexionForm" action="../script/addcours.php" method="POST">
            <label for="section">Section : </label>
            <select name="section" id="section">
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
            <label for="mention">Mention : </label>
            <select name="mention" id="mention">
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
            <label for="code">Code cours</label>
            <input type="text" id="code" name="code" required placeholder="">
            <label for="sigle">Sigle </label>
            <input type="text" id="sigle" name="sigle" required placeholder="">
            <label for="nomComplet">Nom complet: </label>
            <input type="text" id="nomComplet" name="nomComplet" required placeholder="">
            <label for="nomComplet">Nombre d'heure : </label>
            <input type="text" id="nomComplet" name="nbreHeure" required placeholder="">
            <label for="description">Description : </label>
            <input type="description" id="description" name="description" required placeholder="">
            <button type="submit">Nouvel cours</button>
        </form>
    </div>
