<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include("../../config/connexion.php");

if(!empty($_GET['supp'])){
    $id = $_GET['supp'];
    
    $sql = "DELETE FROM horaire WHERE idhoraire = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$id]);      

    if($result){
        echo "
        <script>
            alert('Suppression réussie !');
            window.location.href='../horaire.php';
        </script>";   
    } else {
        echo "
        <script>
            alert('Échec de suppression !');
            window.location.href='../horaire.php';
        </script>";   
    }
} else {
    echo "
    <script>
        alert('ID invalide !');
        window.location.href='../horaire.php';
    </script>";
}
?>