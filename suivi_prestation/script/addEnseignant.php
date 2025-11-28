<?php 
   include("config.php");
   include("utilisateur.php");

    class Enseignant extends Utilisateur{
        protected string $grade;
        protected String $domaine;
        function __constructor($_nom,$_username,$_password){
            parent::__constructor($_nom,$_username,$_password);
            $this->grade = "xxxxxx";
        }

        function SetGrade($_grade){
            $this->grade=$_grade;   
        }
        function GetGrade(){
            return $this->grade;
        }
        function SetDomaine($_domaine){
           $this->domaine = $_domaine; 
        }
        function GetDomaine(){
            return $this->domaine;
        }
    }

    $enseignant = new Enseignant();
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
    $_grade=$_POST['grade'];
    $_domaine = $_POST['domaine'];
    //$etud->saveEtudiant( $_mat,$_nom,$_postnom, $_prenom,$_genre,$_datenaissance, $_etatcivil,
    //$_nationalite,$_adresse, $_telephone,$_mail);
    try{
     $sql ="insert into enseignant(`matriculeEnseignant`, `nom`, `postnom`, `prenom`, `genre`, `dtnaissance`, `nationalite`, `etatCivil`, `adresse`, `adresseMail`, `telephone`, `grade`, `domainEnseignant`) values ('$_mat','$_nom','$_postnom','$_prenom','$_genre','$_datenaissance','$_etatcivil','$_nationalite','$_adresse','$_mail','$_telephone','$_grade','$_domaine')";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array());
        // 
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
            window.location.href='../admin/enseignant.php';
        </script>
    ";
}
    } else {
        echo "Try again ";
    }