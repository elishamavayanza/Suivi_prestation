<?php 
    include("config.php");

     if(!empty($_POST['dt_debut'])){
        $dt_debut = $_POST['dt_debut'];
        $dt_fin= $_POST['dt_fin'];
        $_description = $_POST['description'];
        try{
            $sql ="insert into annee(`dt_debut`, `dt_fin`, `description`) values ('$dt_debut','$dt_fin','$_description')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array());      
            

    if($stmt){
          echo "
        <script>
            alert(' Enregistrement reussi !');
            window.location.href='../admin/index.php';
        </script>      
    ";   
    }else {
        echo "
        <script>
            alert('Echec d' Enregistrement !');
            window.location.href='../admin/annee.php';
        </script>      
    ";   
     }
      
} catch(PDOException $ex){
    echo "
        <script> 
            alert('Une erreure est survenu ! ');
            window.location.href='../admin/annee.php';
        </script>
    ";
}
    } else {
        print("Try again ");
    }

    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}