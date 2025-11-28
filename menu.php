<?php session_start();
  #if(isset($_SESSION['role']) && $_SESSION['role']!="None"){
  include("script/config.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>ISP-Muhangi A BUTEMBO</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!--link rel="stylesheet" href="style.css"-->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstap/js/bootstrap.min.js">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>
<body id="top">
<div class="container"> 
<div class="wrapper row0">
  <div id="topbar" class="clear"> 
   
    <nav>
      <ul>
        
       <?phpif(isset($_SESSION['username']) && $_SESSION['username']!="None"){
                echo "<li><a href='script/logout.php'>Deconnexion</a></li>";
            } else {
                echo " <li><a href='login.php'>Connexion</a></li>";
            }
            ?>
     </ul>
    </nav>
    </div>
</div>
<div class="wrapper row1">
  <header id="header" class="clear"> 
       <div id="logo" class="fl_left">
	<div>
	 <img src="image/logo.jpg" style="width:50px;height:50px;margin:12px;margin-left:-50px;margin-bottom:-35px;border-radius:30px/30px;"width="50px" height="50px"/>
      <h1><a href="index.php">Isp-Muhangi</a></h1>
      <p>ISP-MUHANGI A BUTEMBO</p>	 
	 </div>
    </div>
    <div class="fl_right">
      <form class="clear" method="post" action="#">
        <fieldset>
          <legend>Recherche:</legend>
          <input type="text" value="" placeholder="Rechercher ici">
          <button class="fa fa-search" type="submit" title="Search"><em>Recherche</em></button>
        </fieldset>
      </form>
    </div>
   
  </header>
</div>
<div class="wrapper row2">
<div class="rounded">
  <span style="float:right;color:green"><?php 
  #include("layout/scripts/pvc.php");
  if(isset($_SESSION['role']) && $_SESSION['role']!="None"){
	  echo $_SESSION['username']."/".$_SESSION['role'];
  } else {
        echo"Disconned";
  }
  ?></span>
 <nav id="mainav" class="clear"> 
      <!-- ################################################################################################ -->
<ul class="clear">
       
	<?php 
if(isset($_SESSION['role']) && $_SESSION['role']!="None"){
    if($_SESSION['role']=="Admin")
		{
				
			?>
            <li class="active"><a href="index.php">Accueil</a></li>
            <li><a href="admin/horaire.php">Horaire</a></li>
             <li ><a href="admin/index.php">Admin</a></li>
            <!--li><a href="rapports.php">Rapports</a></li>
             <li ><a href="configuration.php">Configuration</a></li-->
			<?php
		}else if($_SESSION['role']=="Chefpromotion"){
			?>
			<!--<li><a href="pubhoraire.php">Publier Horaire</a></li>-->
              <li class="active"><a href="index.php">Accueil</a></li>
                 <li><a href="cp/horaire.php">Gérer les horaires</a></li>
                 <li><a href="prestation.php">Fiche de prestation</a></li>
                 <li><a href="cp/prestation.php">Gérer les prestations</a></li>
			         
			<?php
		}
		else if($_SESSION['role']=="Chefdesection"){
			?>
            <li class="active"><a href="index.php">Accueil</a></li>
            <li><a href="afficherhoraire.php">horaire</a></li>
            <li><a href="chargehoraire.php">Charge horaire</a></li>
			      <li><a href="cours.php">Programmer Cours</a></li>
            
			<?php
		}else if($_SESSION['role']=="SGA"){
			?>
            <li class="active"><a href="index.php">Accueil</a></li>
            <li><a href="horiaire.php">Horaire</a></li>
<li><a href="chargehoraire.php">Charge horaire</a></li>
            <!--li><a href="cours.php">Programmer Cours</a></li-->
            <!--li ><a href="admin/index.php">Admin</a></li-->
           
			<?php
		}else if($_SESSION['role']=="AB"){
			?>
            <li class="active"><a href="index.php">Accueil</a></li>
            <li><a href="horaire.php">Horaire</a></li>
			      <li><a href="honoraire.php">Honoraire</a></li>
             
			<?php
      	}else if($_SESSION['role']=="secretaire"){
			?>
            <li class="active"><a href="index.php">Accueil</a></li>
            <li><a href="horaire.php">Horaire</a></li>
<li><a href="coursfini.php">Cours Fini</a></li>
             
			<?php
		} else if($_SESSION['role']=="Enseignant"){
			?>
            <li class="active"><a href="enseignant/index_ens.php">Accueil</a></li>
             <li><a href="enseignant/horaire_ens.php">Horaire</a></li>
             <!--li><a href="enseignant/prestation_ens.php">Prestation</a></li-->
             <li><a href="enseignant/description_ens.php">Description</a></li>
             <li><a href="enseignant/charge_horaire_ens.php">Charge horaire</a></li>
			<?php
		} 
   }
        else {
		
		?>
         
        <!--li class="active"><a href="index.php">Accueil</a></li>
        <li><a href="horaire.php">Horaire</a></li>
        <li><a href="sign.php">s'inscription</a></li-->
<!--li><a href="apropos.php">Apropos</a></li-->
        <?php 
        header("Location:login.php");
        }
        ?>
      </ul>
      <!-- ################################################################################################ --> 
    </nav>
    </div>
    </div>