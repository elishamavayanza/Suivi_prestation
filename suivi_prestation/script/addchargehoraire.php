<?php
include("config.php");
if(isset($_POST['matricule'])){            
            $promotion = $_POST['promotion'];
            $cours =$_POST['cours'];            
            $matricule =$_POST['matricule'];
            $obser =$_POST['observation'];
            try{
                $sql = "INSERT INTO chargehoraire( `matricule`, `codecours`, `codepromotion`,`observation`) VALUE ('$matricule','$cours','$promotion','$obser')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                if($stmt){
                echo "
                    <script>
                        alert(' Enregistrement reussi !');
                        window.location.href='../chargehoraire.php';
                    </script>      
                "; 
                } else {
                echo "
                    <script>
                        alert(' Echec d Enregistrement !');
                        window.location.href='../prestation.php';
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
        }else {
         echo "
            <script>
                alert(' Aucun element n a ete selectionne !');
                window.location.href='../index.php';
            </script>      
        ";
    }
