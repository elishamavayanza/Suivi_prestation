<?php
include("config.php");
    if(isset($_POST['matricule'])){
            $section = $_POST['section'];
            $mention =$_POST['mention'];
            $promotion = $_POST['promotion'];
            $cours =$_POST['cours'];            
            $matricule =$_POST['matricule'];
            $description = $_POST['description'];
            try{
                $sql = "INSERT INTO coursfini(`code_section`, `code_mention`, `code_promotion`, `code_cours`, `matricule_enseignant`,`description`) VALUE ('$section','$mention','$promotion','$cours','$matricule','$description')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                if($stmt){
                echo "
                    <script>
                        alert('Enregistrement reussi !');
                        window.location.href='../coursfini.php';
                    </script>      
                "; 
                } else {
                echo "
                    <script>
                        alert(' Echec d Enregistrement !');
                        window.location.href='../coursfini.php';
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
    }  else {
         echo "
            <script>
                alert(' Aucun element n a ete selectionne !');
                window.location.href='../index.php';
            </script>      
        ";
    }
