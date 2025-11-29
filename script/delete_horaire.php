<?php 
    include("connexion.php");

    if(!empty($_GET['supp'])){
        $id = $_GET['supp'];
        
        $sql = "DELETE FROM horaire WHERE idhoraire = ".$id;
        $result = mysqli_query($con, $sql);      

        if($result){
            echo "
            <script>
                alert('Suppression réussie !');
                window.location.href='../admin/horaire.php';
            </script>";   
        } else {
            echo "
            <script>
                alert('Échec de suppression !');
                window.location.href='../admin/horaire.php';
            </script>";   
        }
    } else {
        echo "
        <script>
            alert('ID invalide !');
            window.location.href='../admin/horaire.php';
        </script>";
    }
?>