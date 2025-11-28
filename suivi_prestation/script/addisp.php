<?php 
    include("config.php");

     if(!empty($_POST['arrete'])){
        $arrete = $_POST['arrete'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $boit = $_POST['boitepostale'];
        $dt = $_POST['dtcreation'];
        try{
    $sql ="insert into isp(`code_isp`, `dt_creation`, `sigle`, `nomComplet`, `numArreterminister`, `description`, `boitepostal`) values ('$arrete','$dt','$sigle','$denomination','$arrete','$description','$boit')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array());
   
    if($stmt){
        MessageAlert("Enregistrement reussi");
        header("Location:../admin/index.php");
    }else {     
   
    echo "
        <script>
            alert(' Enregistrement reussi !');
            window.location.href='../admin/index.php';
        </script>      
    "; 
     }  
} catch(PDOException $ex){
    echo "
        <script> 
            alert('Echec Enregistrement une error est survenu ! ');
            window.location.href='../admin/isp.php';
        </script>
    ";
}
 } else {
        print("Try again ");
    }
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}