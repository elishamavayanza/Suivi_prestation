
    <div class="formulaire">
      
        <form id="connexionForm" action="../script/addSection.php" method="POST">
            <label for="">Code isp </label>
            <!--input type="text" id="arrete" name="code" required placeholder=""-->
            <select name="code" id="fac">
            <?php
                 $sql ="select *from isp";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                $res =null;
                    while($res=$stmt->fetch()){
                        ?>
                        <option  value="<?php echo $res['code_isp'];?>"><?php echo $res['nomComplet']; ?></option>
                    <?php  } 
                    ?>
            </select>
            <label for="sigle">Sigle</label>
            <input type="Text" id="sigle" name="sigle" required placeholder="">
            <label for="nomComplet">Denomination</label>
            <input type="Text" id="nomComplet" name="nomComplet" required placeholder="">
            <label for="nomComplet">Description</label>
            <input type="Text" id="" name="description" required placeholder="">
            <label for="arreter">Date creation </label>
            <input type="date" id="" name="dtcreation" required placeholder="">
            <button type="submit"> Ajouter Section</button>
        </form>
    </div>
