 
<?php
include("../script/connexion.php");
		$cours=mysqli_query($con,"SELECT * FROM cours");
		$dep=mysqli_query($con,"SELECT * FROM mention");
		$pro=mysqli_query($con,"SELECT * FROM promotion");
		$ens =mysqli_query($con,"SELECT * FROM enseignant");
$admin= isset($_SESSION['Admin']);
if(isset($_POST['ajouter'])){
	
				#include("connexion_inscription.php");
				if(mysqli_query($con,"INSERT INTO horaire(idcours,jourheure,codemention,codepromotion,enseignant,site,periode,observation,datejour) VALUES('".$_POST['cours']."','".$_POST['jour']."','".$_POST['dep']."','".$_POST['pro']."','".$_POST['enseignant']."','".$_POST['site']."','".$_POST['periode']."','".$_POST['observation']."','".$_POST['dte']."')")){
					echo "<script>alert('Horaire postee  avec succes');</script>;";
				}else
				{
					echo "<center>".mysqli_error()."</center>";
				}
}
if(isset($_POST['modifier'])){
	if(isset($_FILES['photo']) && !empty($_FILES['photo'])){
		$dossier="PiecesJointes/";
		$fichier=basename($_FILES['photo']['name']);
		if(move_uploaded_file($_FILES['photo']['tmp_name'],$dossier.$fichier))
				{
				$photo=$_FILES['photo']['name'];
				}
				else
				{
			    $photo=$_POST['photo2'];
				}
								
	}else{
		$photo=$_POST['photo2'];
		
	}
				include("../script/connexion.php");
				if(mysqli_query($con,"UPDATE  tbpublication set Titre='".$_POST['tit']."', Categorie='".$_POST['cat2']."',Contenu='".$_POST['cont']."',FichierJoint='".$photo."' WHERE Id=" .$_POST['id'])){
					echo "<script>alert('Publication modifiee avec succes');</script>;";
				}else
				{
					echo "<center>".mysqli_error()."</center>";
				}
}
if(isset($_GET['supp'])){
	include("connexion_inscription.php");
	if(mysqli_query($con,"DELETE FROM tbpublication WHERE Id=".$_GET['supp'])){
		echo "<script>alert('Supprimé avec succees');</script>;";
	}else{
		echo "<center>".mysqli_error()."</center>";
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
		<?php if(isset($_GET['modif']))
		{
			//include("connexion_inscription.php");
			$query=mysqli_query($con,"SELECT * FROM horaire WHERE Id=".$_GET['modif']);
			$res=mysqli_fetch_array($query);
		}	
		?>

     <nav class="sdb_holder ">
		<div class="formulaire">
		 <form action="../formulaire/formhoraire.php" method="POST" enctype="multipart/form-data">
            
              <label for="name">Cours </label>
             <select name="cours" id="name" >
			 <?php 
				while($row=mysqli_fetch_array($cours)){
					?>
				<option value="<?php echo $row["code_cours"]?>"><?php echo $row["nomComplet"]?></option>
				<?php
				}
				?>
				
			</select>
           
		
              <label for="name">mention </label>
             <select name="dep" id="name" >
			 <?php 
				while($row=mysqli_fetch_array($dep)){
					?>
				<option value="<?php echo $row["code_mention"]?>"><?php echo $row["nomComplet"]?></option>
				<?php
				}
				?>
				
			</select>
           
			
              <label for="name">Promotion</label>
             <select name="pro" id="name" >
			 <?php 
				while($row=mysqli_fetch_array($pro)){
					?>
				<option value="<?php echo $row["sigle_promotion"]?>"><?php echo $row["nomComplet"]?></option>
				<?php
				}
				?>
				
			</select>			
              <label for="name" class="text">Annee academique </label>
              <select name="annee" id="name" class="text" >
				<?php for($i=2010;$i<=date('Y')+10;$i++){
					$in=$i+1;
					?>
					<option  value="<?php echo $i."-".$in;?>"><?php echo $i."-".$in;?></option>
					<?php
				}?>
			  </select>
            
			<label for="name">Periode </label>
             <select name="periode">
				<option value="AM">Avant Midi</option>
				<option value="PM">Apres Midi</option>
			 </select>
              
			  
			  <label for="name">Desc. Jour et Heure </label>
            	<textarea name="jour" id="comment" cols="25" rows="3"><?php if(isset($_GET['modif'])) echo $res['Contenu'];?></textarea>
           
              
			  <label for="name">Date </label>
              <input type="date" name="dte" id="name" value="<?php if(isset($_GET['modif'])) echo $res['Titre'];?>" size="25">
			  <label for="name">Enseignant </label>

              <!--input type="text" name="enseignant" id="name" value="<?php if(isset($_GET['modif'])) echo $res['Titre'];?>" size="25"-->
			  <select name="enseignant" id="name" >
			 <?php 
				while($row=mysqli_fetch_array($ens)){
					?>
				<option value="<?php echo $row["matriculeEnseignant"]?>"><?php echo $row["nom"]." ".$row["postnom"]." ".$row["prenom"];?></option>
				<?php
				}
				?>
				
			</select>	
			  <label for="name">Site </label>
              <input type="text" name="site" id="name" value="<?php if(isset($_GET['modif'])) echo $res['Titre'];?>" size="25">
			  
            
              <label for="comment">Observation</label>
            <textarea name="observation" id="comment" cols="100" rows="5"><?php if(isset($_GET['modif'])) echo $res['Contenu'];?></textarea>
			
			
			 <?php if(isset($_GET['modif'])) 
			 {
			 ?>
           
             <label for="name"></label>
            	<input type="submit" name="modifier" id="name" value="Modifier" size="32">
            
			<?php
			 }else{
				  ?>
            <div class="one_third first">
             <label for="name"></label>
              <button type="submit" name="ajouter" id="name" value="Ajouter" > Ajouter </button>
            
			<?php
			 }
			?>
           </form>
			</div>
        </nav>
  
       
