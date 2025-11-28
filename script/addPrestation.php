<?php
include("config.php");
    if(isset($_POST['matricule'])){
            $section = $_POST['section'];
            $mention =$_POST['mention'];
            $promotion = $_POST['promotion'];
            $cours =$_POST['cours'];            
            $matricule =$_POST['matricule'];
            try{
                $sql = "INSERT INTO entetefiche(`code_section`, `code_mention`, `code_promotion`, `code_cours`, `matricule_enseignant`) VALUE ('$section','$mention','$promotion','$cours','$matricule')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                if($stmt){
                echo "
                    <script>
                        alert(' Enregistrement reussi !');
                        window.location.href='../prestation.php';
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
    }  else if(isset($_POST['entetefiche'])){
        $entete = $_POST['entetefiche'];
        $dtjour = $_POST['dtjour'];
        $contenu =$_POST['description'];
        $hentree =$_POST['h_entree'];
        $hsortie= $_POST['h_sortie'];
        $nbreH =  $hsortie -$hentree;
        $sigCp =$_POST['sigcp'];
        $sigEns =$_POST['sigens'];
        try{
        $sql = "INSERT INTO contenufiche(`identetefiche`, `datejoure`, `contenu`, `heureEntree`, `heureSortie`, `nbreH`, `signatureCP`, `signatureEnseignant`) VALUES ('$entete','$dtjour','$contenu','$hentree','$hsortie','$nbreH','$sigCp','$sigEns')";
         $stmt = $pdo->prepare($sql);
                $stmt->execute(array());
                if($stmt){
                echo "
                    <script>
                        alert(' Enregistrement reussi !');
                        window.location.href='../horaire.php';
                    </script>      
                "; 
                } else {
                echo "
                    <script>
                        alert(' Echec d Enregistrement !');
                        window.location.href='../horaire.php';
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
    } else {
         echo "
            <script>
                alert(' Aucun element n a ete selectionne !');
                window.location.href='../index.php';
            </script>      
        ";
    }
