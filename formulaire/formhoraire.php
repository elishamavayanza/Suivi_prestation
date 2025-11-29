<?php
include("../script/connexion.php");
		$cours=mysqli_query($con,"SELECT * FROM cours");
		$dep=mysqli_query($con,"SELECT * FROM mention");
		$pro=mysqli_query($con,"SELECT * FROM promotion");
		$ens =mysqli_query($con,"SELECT * FROM enseignant");
$admin= isset($_SESSION['Admin']);

// Fonction pour créer une nouvelle année universitaire
function creerNouvelleAnnee($con) {
    // Vérifier si une année existe déjà pour l'année en cours
    $current_year = date('Y');
    $next_year = $current_year + 1;
    $annee_description = $current_year . "-" . $next_year;
    
    // Vérifier si cette année existe déjà
    $check_query = mysqli_query($con, "SELECT * FROM annee WHERE description = '$annee_description'");
    
    if(mysqli_num_rows($check_query) == 0) {
        // Créer la nouvelle année (du 15 septembre de l'année en cours au 15 juillet de l'année suivante)
        $date_debut = $current_year . "-09-15";
        $date_fin = $next_year . "-07-15";
        
        $insert_query = "INSERT INTO annee(dt_debut, dt_fin, description) VALUES('$date_debut', '$date_fin', '$annee_description')";
        mysqli_query($con, $insert_query);
    }
}

if(isset($_POST['ajouter'])){
    // Créer automatiquement une nouvelle année lors de l'ajout d'un horaire
    creerNouvelleAnnee($con);
    
    // Utiliser les bons noms de champs depuis le nouveau formulaire
    $idcours = $_POST['idcours'];
    $jourheure = $_POST['jourheure'];
    $codemention = $_POST['codemention'];
    $codepromotion = $_POST['codepromotion'];
    $enseignant = $_POST['enseignant'];
    $site = $_POST['site'];
    $periode = $_POST['periode'];
    $observation = !empty($_POST['observation']) ? $_POST['observation'] : '';
    $datejour = !empty($_POST['datejour']) ? $_POST['datejour'] : date('Y-m-d');
    
    if(mysqli_query($con,"INSERT INTO horaire(idcours,jourheure,codemention,codepromotion,enseignant,site,periode,observation,datejour) VALUES('$idcours','$jourheure','$codemention','$codepromotion','$enseignant','$site','$periode','$observation','$datejour')")){
        echo "<script>alert('Horaire postee  avec succes');</script>;";
        echo "<script>window.location.href='../admin/horaire.php';</script>";
    }else
    {
        echo "<center>".mysqli_error($con)."</center>";
    }
}

if(isset($_GET['supp'])){
	include("../script/connexion.php");
	if(mysqli_query($con,"DELETE FROM horaire WHERE idhoraire=".$_GET['supp'])){
		echo "<script>alert('Supprimé avec succees');</script>;";
        echo "<script>window.location.href='../admin/horaire.php';</script>";
	}else{
		echo "<center>".mysqli_error($con)."</center>";
	}
}
?>
    <!-- --------------------------------------
    <div class="formulaire">
        
        <form id="connexionForm" action="" method="POST">
            <label for="fac">Section : </label>
            <select name="fac" id="fac">
                <option value="">..........</option>
            </select>
            <label for="depart">Option : </label>
            <select name="dapart" id="depart">
                <option value="">..........</option>
            </select>
            <label for="promotion">Promotion : </label>
            <select name="promotion" id="promotion">
                <option value=""> ........... </option>
            </select>
            <label for="code">Code cours</label>
             <select name="codeCours" id="codecours">
                <option value=""> ........... </option>
            </select>
            <label for="sigle">Date debut </label>
            <input type="date" id="sigle" name="dt_debut" required placeholder="">
            <label for="nomComplet">Date fin </label>
            <input type="date" id="datefin" name="datefin" required placeholder="">
            <label for="description">Description : </label>
            <input type="description" id="description" name="description" required placeholder="">
            <button type="submit">preogrammer COurs</button>
        </form>
    </div>

<h1> second form </h1 -->
    <!-- =============================================== -->

     <nav class="sdb_holder ">
		<div class="formulaire">
		 <form action="" method="POST" enctype="multipart/form-data">
            
              <div class="form-group">
                  <label for="name">Cours</label>
                  <select name="cours" id="name" class="form-control">
                     <?php 
                        while($row=mysqli_fetch_array($cours)){
                            ?>
                        <option value="<?php echo $row["code_cours"]?>"><?php echo $row["nomComplet"]?></option>
                        <?php
                        }
                        ?>
                  </select>
              </div>
           
              <div class="form-group">
                  <label for="dep">Mention</label>
                  <select name="dep" id="dep" class="form-control">
                     <?php 
                        while($row=mysqli_fetch_array($dep)){
                            ?>
                        <option value="<?php echo $row["code_mention"]?>"><?php echo $row["nomComplet"]?></option>
                        <?php
                        }
                        ?>
                  </select>
              </div>
			
              <div class="form-group">
                  <label for="pro">Promotion</label>
                  <select name="pro" id="pro" class="form-control">
                     <?php 
                        while($row=mysqli_fetch_array($pro)){
                            ?>
                        <option value="<?php echo $row["sigle_promotion"]?>"><?php echo $row["nomComplet"]?></option>
                        <?php
                        }
                        ?>
                  </select>
              </div>
              
              <div class="form-group">
                  <label for="annee" class="text">Année académique</label>
                  <select name="annee" id="annee" class="form-control text">
                    <?php for($i=2010;$i<=date('Y')+10;$i++){
                        $in=$i+1;
                        ?>
                        <option value="<?php echo $i."-".$in;?>"><?php echo $i."-".$in;?></option>
                        <?php
                    }?>
                  </select>
              </div>
            
              <div class="form-group">
                  <label for="periode">Période</label>
                  <select name="periode" id="periode" class="form-control">
                    <option value="AM">Avant Midi</option>
                    <option value="PM">Après Midi</option>
                 </select>
              </div>
              
              <div class="form-group">
                  <label for="jour">Desc. Jour et Heure</label>
                  <textarea name="jour" id="jour" class="form-control" cols="25" rows="3"></textarea>
              </div>
              
              <div class="form-group">
                  <label for="dte">Date</label>
                  <input type="date" name="dte" id="dte" class="form-control" size="25">
              </div>
              
              <div class="form-group">
                  <label for="enseignant">Enseignant</label>
                  <select name="enseignant" id="enseignant" class="form-control">
                     <?php 
                        while($row=mysqli_fetch_array($ens)){
                            ?>
                        <option value="<?php echo $row["matriculeEnseignant"]?>"><?php echo $row["nom"]." ".$row["postnom"]." ".$row["prenom"];?></option>
                        <?php
                        }
                        ?>
                  </select>
              </div>
              
              <div class="form-group">
                  <label for="site">Site</label>
                  <input type="text" name="site" id="site" class="form-control" size="25">
              </div>
			  
              <div class="form-group">
                  <label for="observation">Observation</label>
                  <textarea name="observation" id="observation" class="form-control" cols="100" rows="5"></textarea>
              </div>
			
              <div class="form-group">
                  <button type="submit" name="ajouter" id="ajouter" class="btn btn-success">Ajouter</button>
              </div>
            
           </form>
		</div>
    </nav>