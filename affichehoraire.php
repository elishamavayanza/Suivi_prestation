<?php 
#session_start();
#include("connexion_inscription.php");
include("script/config.php");
		$cours="SELECT * FROM cours";
		$dep="SELECT * FROM departement";
		$pro="SELECT * FROM promotion";

if(isset($_SESSION['Admin'])|| isset($_SESSION['Chefsdesection'])){
if(isset($_POST['ajouter'])){				
		$queri = "INSERT INTO horaire(idcours,jourheure,codedepartement,codepromotion,enseignant,site,periode,observation,datejour) VALUES('".$_POST['cours']."','".$_POST['jour']."','".$_POST['dep']."','".$_POST['pro']."','".$_POST['enseignant']."','".$_POST['site']."','".$_POST['periode']."','".$_POST['observation']."','".$_POST['dte']."')";
          $state = $pdo->prepare($queri);
          $state->execute(array());
          if($state){
					echo "<script>alert('Horaire postee  avec succes');</script>;";
					header("Location:index.php");
				}else
				{
					echo "<script>alert('Echec de poste');

					</script>;";
					header("Location:index.php");
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
				#include("connexion_inscription.php");
				$up = "UPDATE  horaire set Titre='".$_POST['tit']."', Categorie='".$_POST['cat2']."',Contenu='".$_POST['cont']."',FichierJoint='".$photo."' WHERE Id=" .$_POST['id'];
        $stmtup = $pdo->prepare($up);
        $stmtup->execute(array());
        if($stmtup){
					echo "<script>alert('Publication modifiee avec succes');</script>;";
				}else
				{
					echo "<center>".mysqli_error()."</center>";
				}
}
if(isset($_GET['supp'])){
	#include("connexion_inscription.php");
	$del = "DELETE FROM horaire WHERE Id=".$_GET['supp'];
  $statement = $pdo->prepare($del);
  $statement->execute(array());
  if($statement){
		echo "<script>alert('Supprimé avec succees');</script>;";
	}else{
		echo "<center> Echec </center>";
	}
}
} else{
?>

<!-- ################################################################################################ --> 
<!-- ################################################################################################ --> 
<!-- ################################################################################################ -->
<div class="wrapper row2">
  <div class="rounded">
   <?php #include("menu.php");?>
  </div>
</div>
<!-- ################################################################################################ --> 
<!-- ################################################################################################ --> 
<!-- ################################################################################################ -->
<div class="wrapper row3">
  <div class="rounded">
    <main class="container clear"> 
      <!-- main body --> 
      <!-- ################################################################################################ -->
      <div class="sidebar one_quarter first"> 
        <!-- ################################################################################################ -->
        <h6><b>Afficher l'horaire personalise</b></h6>
		<?php if(isset($_GET['modif']))
		{
			//include("connexion_inscription.php");
			$query="SELECT * FROM horaire WHERE Id=".$_GET['modif'];
			$res= $pdo->prepare($query);
		}	
		?>
        <nav class="sdb_holder">
		 <form action="affichehoraire.php" method="post" enctype="multipart/form-data">
           
			<div  >
              <label for="name">Annee academique </label>
              <select name="annee" id="name" >
				<?php for($i=2010;$i<=date('Y')+10;$i++){
					$in=$i+1;
					?>
					<option  value="<?php echo $i."-".$in;?>"><?php echo $i."-".$in;?></option>
					<?php
				}?>
			  </select>
            </div>
			<div >
			<label for="name">Periode </label>
             <select name="periode">
				<option value="AM">Avant Midi</option>
				<option value="PM">Apres Midi</option>
			 </select>
              
			 
			  <label for="name">Date du </label>
              <input type="date" name="dte" id="name" value="<?php if(isset($_GET['modif'])) echo $res['Titre'];?>" size="25">
			  <label for="name">Au </label>
              <input type="date" name="dte2" id="name" value="<?php if(isset($_GET['modif'])) echo $res['Titre'];?>" size="25">
			  
            </div>
		
			
			
			 <?php if(isset($_GET['modif'])) 
			 {
			 ?>
            <div >
             <label for="name"></label>
              <input type="submit" name="modifier" id="name" value="Modifier" size="32">
            </div>
			<?php
			 }else{
				  ?>
            <div class="one_third first">
             <label for="name"></label>
              <input type="submit" name="ajouter" id="name" value="Ajouter" size="32">
            </div>
			<?php
			 }
			?>
           
        </nav>
  
        <!-- ################################################################################################ --> 
      </div>
      <!-- ################################################################################################ --> 
      <!-- ################################################################################################ -->
      <div id="content" class="three_quarter"> 
        <!-- ################################################################################################ -->
        <h1><b>Affichage Horaire  de L1 IG du 27-09-2024 au 27-09-2024 /Annee:2024-2025</b></h1>
		<?php 
			$sql="SELECT jourheure,h.codepromotion pro,h.codemention dep,c.nomComplet cours,enseignant,site,observation,datejour  from horaire h,cours  c WHERE h.idcours=c.code_cours and h.codepromotion='L1' ORDER BY Datejour DESC";
      		$stmt=$pdo->prepare($sql);
			$stmt->execute(array());
		?>
         <div class="scrollable">
        <table>
          <thead>
            <tr>
              <th>JOURS/HEURE</th>
              <th>PROMOTION/FAC</th>
              <th>COURS</th>
              <th>ENSEIGNANT</th>
              <th>SITE</th>
              <th>OBS</th>
            </tr>
          </thead>
          <tbody>     
			<?php 
			
				while($row=$stmt->fetch()){
					?>
            <tr>
              <td style="color:black;"><?php echo $row['jourheure'];?></td>
              <td style="color:black;"><?php echo $row['pro'].'/'.$row["dep"];?></td>
              <td style="color:black;"><?php echo $row['cours'];?></td>
              <td style="color:black;"><?php echo $row['enseignant'];?></td>
              <td style="color:black;"><?php echo $row['site'];?></td>
              <td style="color:black;"><?php echo $row['observation'];?></td>
              
            </tr>
           
        
					<?php
				}			
			?>
           </tbody>
        </table>
      </div>
		
        <!-- ################################################################################################ --> 
      </div>
      <!-- ################################################################################################ --> 
      <!-- / main body -->
      <div class="clear"></div>
    </main>
  </div>
</div>
<?php } ?>
<!-- ################################################################################################ --> 
<!-- ################################################################################################ --> 
<!-- ################################################################################################ -->

