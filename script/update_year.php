<?php 
    include("config.php");

    if(!empty($_POST['id']) && !empty($_POST['date_debut']) && !empty($_POST['date_fin']) && !empty($_POST['description'])){
        $id = $_POST['id'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $description = $_POST['description'];
        
        try{
            $sql = "UPDATE annee SET dt_debut = ?, dt_fin = ?, description = ? WHERE code_annee = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$date_debut, $date_fin, $description, $id]);      

            if($result){
                echo "
                <script>
                    alert('Modification réussie !');
                    window.location.href='../admin/annee.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de modification !');
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
?>