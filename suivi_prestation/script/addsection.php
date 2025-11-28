<?php 
    include("config.php");

     if(!empty($_POST['sigle'])){
        $code =$_POST['code'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dt = $_POST['dtcreation'];
        try{
    $sql ="insert into Section(`dt_creation`, `sigle`, `nomComplet`, `description`, `code_isp`) values ('$dt','$sigle','$denomination','$description','$code')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array());
    if($stmt){
     echo "
        <script>
            alert(' Enregistrement reussi !');
            window.location.href='../admin/index.php';
        </script>      
    ";  } else {
         echo "
        <script> 
            alert('Enregistrement echoue ! ');
            window.location.href='../admin/section.php';
        </script>
    ";
    } 
} catch(PDOException $ex){
    echo "
        <script> 
            alert('Une erreur est survnue ! ');
            window.location.href='../admin/section.php';
        </script>
    ";
}
    } else {
        print("Try again ");
    }
    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}