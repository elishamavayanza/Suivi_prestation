
    <div class="formulaire">
             
        <form id="connexionForm" action="../script/addmention.php" method="POST">
            <label for="">id Section</label>
            <!--input type="text" id="" name="codefac" required placeholder=""-->
            <select name="codefac">           
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
            <label for="sigle">Sigle</label>
            <input type="Text" id="sigle" name="sigle" required placeholder="">
            <label for="nomComplet">Denomination</label>
            <input type="Text" id="nomComplet" name="nomComplet" required placeholder="">
             <label for="nomComplet">Description</label>
            <input type="Text" id="nomComplet" name="description" required placeholder="">
            <label for="arreter">Date creation </label>
            <input type="date" id="arrete" name="dtcreation" required placeholder="">
            <button type="submit"> Ajouter Mention</button>
        </form>
    </div>
