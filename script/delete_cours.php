<?php 
    include("config.php");

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        
        try{
            $sql = "DELETE FROM cours WHERE code_cours = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$id]);      

            if($result){
                echo "
                <script>
                    alert('Suppression réussie !');
                    window.location.href='../admin/cours.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de suppression !');
                    window.location.href='../admin/cours.php';
                </script>";   
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/cours.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('ID invalide !');
            window.location.href='../admin/cours.php';
        </script>";
    }
?>