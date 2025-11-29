<?php 
    include("config.php");

    if(!empty($_POST['date_debut']) && !empty($_POST['date_fin']) && !empty($_POST['annee_academique'])){
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $description = $_POST['annee_academique'];
        
        try{
            $sql = "INSERT INTO annee(`dt_debut`, `dt_fin`, `description`) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$date_debut, $date_fin, $description]);      

            if($result){
                echo "
                <script>
                    alert('Enregistrement réussi !');
                    window.location.href='../admin/annee.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de enregistrement !');
                    window.location.href='../admin/annee.php';
                </script>";   
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/annee.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs !');
            window.location.href='../admin/annee.php';
        </script>";
    }

    function MessageAlert($message){
        echo "<script>alert('$message');</script>";
    }