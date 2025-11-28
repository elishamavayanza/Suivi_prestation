<?php 
include("config.php");
include("utilisateur.php");
class Etudiant extends Utilisateur
    {
        
        function __constructor($_nom,$_username,$_password){
            parent::__constructor($_nom,$_username,$_password);
            $this->matriculeEtudiant = "XXXXXXXXXXXXXXXXXXXXX";
        }
        function GetMatriculeEtudiant(){
            return $this->$matriculeEtudiant;
        }
        function SetMatriculeEtudiant($_matriculeEtudiant){
            $this->matriculeEtudiant = $_matriculeEtudiant;
        }
        function sinscrireau_cours($matricule,$password){

        }
        function participercours($matricule){

        }
        function evaluerEnseignant(){
            
        }
       public function saveEtudiant($_mat,$_nom,$_postnom,$_prenom,$_genre,$_datenaissance,$_etatcivil,$_nationalite,$_adresse,$_mail,$_telephone){
        #$this->id =$id;
        $this->matricule =$_mat;
        $this->nom =$_nom;
        $this->post_nom=$_postnom;
        $this->prenom =$_prenom;
        $this->genre=$_genre;
        $this->date_naissance =$_datenaissance;
        $this->etat_civil =$_etatcivil;
        $this->nationalite =$_nationalite;
        $this->adresse=$_adresse;
        $this->telephone =$_telephone;
        $this->mail=$_mail;
       /*
         $sql ="insert into etudiant(`matriculeEtudiant`, `nom`, `postnom`, `prenom`, `genre`, `dtnaissance`, `nationalite`, `etatCivil`, `adresse`, `adresseMail`, `telephone`) values ($_mat,$_nom,$_postnom,$_prenom,$_genre,$_datenaissance,$_etatcivil,$_nationalite,$_adresse,$_mail,$_telephone)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array());
        if($stmt){
            header("Location : ../admin/dashaboard.php");
        } else {
            header("Location : ../admin/eutudiant.php");
        }
        */
        }
        
    }
    function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}
   
    #echo("Test poo");
    $etud = new Etudiant();
    if(!empty($_POST['matricule'])){
    $_mat=$_POST['matricule'];
    $_nom=$_POST['nom'];
    $_postnom=$_POST['postnom'];
    $_prenom=$_POST['prenom'];
    $_genre=$_POST['genre'];
    $_datenaissance=$_POST['date_naissance'];
    $_etatcivil=$_POST['etat_civil'];
    $_nationalite=$_POST['nationalite'];
    $_adresse=$_POST['adresse'];
    $_telephone=$_POST['telephone'];
    $_mail=$_POST['mail'];
    //$etud->saveEtudiant( $_mat,$_nom,$_postnom, $_prenom,$_genre,$_datenaissance, $_etatcivil,
    //$_nationalite,$_adresse, $_telephone,$_mail);
    try{
     $sql ="insert into etudiant(`matriculeEtudiant`, `nom`, `postnom`, `prenom`, `genre`, `dtnaissance`, `nationalite`, `etatCivil`, `adresse`, `adresseMail`, `telephone`) values ('$_mat','$_nom','$_postnom','$_prenom','$_genre','$_datenaissance','$_etatcivil','$_nationalite','$_adresse','$_mail','$_telephone')";
        $stmt=$pdo->prepare($sql);
    
            $stmt->execute(array());
        
        
        // if($stmt){
        //     MessageAlert("Enregistrement reussi");
        //     echo "<script>alert('Enregistrement reussi');</script>";
        //     header("Location:../admin/index.php");
        // } else {
        //     MessageAlert("Echec d'enregistrement");
        //     header("Location:../admin/eutudiant.php");
        // }
         echo "
        <script>
            alert(' Enregistrement reussi !');
            window.location.href='../admin/index.php';
        </script>      
    ";   
} catch(PDOException $ex){
    echo "
        <script> 
            alert('Enregistrement echoue ! ');
            window.location.href='../admin/etudiant.php';
        </script>
    ";
}
    } else {
        echo "Try again ";
    }

    
