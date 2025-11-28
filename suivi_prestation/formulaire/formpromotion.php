
    <div class="formulaire">
              
        <form id="connexionForm" action="../script/addpromotion.php" method="POST">
            <label for="arreter">Code Option</label>
            <!--input type="text" id="code" name="codedepart" required placeholder=""-->
            <select name="codedepart" id="mention" class="text">
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
            <label for="sigle">Sigle</label>
            <input type="Text" id="sigle" name="sigle" required placeholder="">
            <label for="nomComplet">Denomination</label>
            <input type="Text" id="nomComplet" name="nomComplet" required placeholder="">
            <label for="description">Description</label>
            <input type="Text" id="description" name="description" required placeholder="">
            <label for="arreter">Date creation </label>
            <input type="date" id="arrete" name="dtcreation" required placeholder="">
            <button type="submit"> Ajouter Promotion</button>
        </form>
    </div>
