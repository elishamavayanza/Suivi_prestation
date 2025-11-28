<div>
    <form action="script/addPrestation.php" method="POST">
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
        <input type="submit" value="Nouvelle fiche" class="btn">
    </form>
	<p id="promptCompat"> </p> 
	<dialog id="mydialog"> 
        <form action="script/addPrestation.php" method="POST">
            <h1>Remplir la fiche de prestation </h1>
            <label for="">Entete de la fiche</label>
            <select name="entetefiche" id="fiche" class="txt">
                    <?php
                    $sql ="SELECT entetefiche.id as entete,cours.nomComplet as noms from entetefiche,cours WHERE entetefiche.code_cours=cours.id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array());
                    $res =null;
                        while($res=$stmt->fetch()){
                            ?>
                            <option  value="<?php echo $res['entete'];?>"><?php echo $res['noms']; ?></option>
                        <?php  } 
                        ?>
                </select>
            <label for="">Date</label>
            <input type="date" name="dtjour" id="" class="txt">
            <label for="">Description</label>
            <input type="text" name="description" id="" class="txt">
            <label for="">Heure entree</label>
            <input type="time" name="h_entree" id="" class="txt">
            <label for="">Heure sortie</label>
            <input type="time" name="h_sortie" id="" class="txt">
            <label for="">signaturecp</label>
            <input type="text" name="sigcp" id="" class="txt">
            <label for="">Signatureenseignant</label>
            <input type="text" name="sigens" id="" class="txt">
            <input type="submit" value="Enregistrer" class="btn">
        </form>
		<div class="boutons"><button onclick="$dialog.close()" class="btn">Fermer</button><!--button onclick="$dialog.returnValue = document.getElementById('closeMsg').value" class="btn">Completer la ficher</button--></div> 
	</dialog> 
	<div ><!--button onclick="$dialog.show()" class="boutons btn">Ouvrir</button--><button onclick="$dialog.showModal()" class="boutons btn">Completer la fiche</button></div> 
	<!--div class="boutons btn"><button onclick="alert($dialog.open)">Vérifier l'attribut <code>open</code></button></div--> 
</div>

<!-- 
        <h1>Remplir la fiche de prestation </h1>


                    -->

    
