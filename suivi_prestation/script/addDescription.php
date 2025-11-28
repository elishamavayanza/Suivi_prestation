<?php
include("config.php"); 
    if(isset($_POST['matricule'])){
        $section = $_POST['section'];
        $mention =$_POST['mention'];
        $promotion = $_POST['promotion'];
        $cours =$_POST['cours'];            
        $matricule =$_POST['matricule'];
        $dt =$_POST['dt'];
        $obj =$_POST['objectif'];
        $contenu =$_POST['contenu'];
        $methode=$_POST['methode'];
        $ressource =$_POST['ressource'];
        $nature =$_POST['nature'];
        $evaluation =$_POST['evaluation'];
        $bibio =$_POST['biblio'];
        try{
                $sql = "INSERT INTO descriptionfiche(`code_section`, `code_mention`, `code_promotion`, `code_cours`, `matricule_enseignant`,`dt`, `objectif`, `contenu`, `methode`, `ressource`, `nature`, `evaluation`, `bibliographie`) VALUE ('$section','$mention','$promotion','$cours','$matricule','$dt','$obj','$contenu','$methode','$ressource','$nature','$evaluation','$bibio')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                if($stmt){
                echo "
                    <script>
                        alert(' Enregistrement reussi !');
                        window.location.href='../description.php';
                    </script>      
                "; 
                } else {
                echo "
                    <script>
                        alert(' Echec d Enregistrement !');
                        window.location.href='../description.php';
                    </script>      
                "; 
                }
            }catch(PDOException $e){
                                echo "
                    <script>
                        alert(' Une erreure est survenue !');
                        window.location.href='../index.php';
                    </script>      
                ";
            }



    }