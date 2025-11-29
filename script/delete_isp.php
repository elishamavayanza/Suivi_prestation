<?php 
    include("config.php");

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        
        try{
            $sql = "DELETE FROM isp WHERE code_isp = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$id]);      

            if($result){
                echo "
                <script>
                    alert('Suppression réussie !');
                    window.location.href='../admin/isp.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de suppression !');
                    window.location.href='../admin/isp.php';
                </script>";   
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/isp.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('ID invalide !');
            window.location.href='../admin/isp.php';
        </script>";
    }
?>