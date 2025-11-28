<?php 
include("config.php");
    if(!empty($_POST['sigle'])){
        $code =$_POST['codedepart'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dt = $_POST['dtcreation'];
        try{
    $sql ="insert into promotion( `sigle_promotion`, `nomComplet`, `code_mention`, `description`, `dtcreation`) values ('$sigle','$denomination','$code','$description','$dt')";
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
            alert('Enregistrement echoue ! ! ');
            window.location.href='../admin/promotion.php';
        </script>
    ";
   }   
} catch(PDOException $ex){
    echo "
        <script> 
            alert('une erreur est survenue ');
            window.location.href='../admin/promotion.php';
        </script>
    ";
}
    } else {
        print("Try again ");
    }
    
    function MessageAlert($message){
        echo "<script>alert('$message');</script>";
    }