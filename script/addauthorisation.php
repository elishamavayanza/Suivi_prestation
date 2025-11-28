<?php 
    include("../config/connexion.php");

     if(!empty($_POST['code'])){
        $code = $_POST['code'];
        $matricule =$_POST['matricule'];
        $enseignant =$_POST['enseignant'];
        $percent = $_POST['percentparti'];
        $sigle =$_POST['sigle'];
        try{
            $sql ="insert into participeraucours(`code_cours`,`codepromotion`, `matriculeEtudiant`, `matriculeEnseignant`, `presenceInPercent`) values ('$code','$sigle','$matricule','$enseignant','$percent')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array());
            // if($stmt){
            //     header("Location:../index.php");
            // }else {
            //     header("Location:../auth_evaluation.php");
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
            alert('Echec Enregistrement ! ');
            window.location.href='../auth_evaluation.php';
        </script>
    ";
}
    } else {
        print("Try again ");
    }