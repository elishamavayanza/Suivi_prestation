<?php 
    include("config.php");
    if(!empty($_POST['matricule'])){
        $_matri = $_POST['matricule'];
        $_user = $_POST['username'];
        $_pass = $_POST['password'];
        $_role = $_POST['role'];
        try{
    $sql ="insert into utilisateur(`matricule`, `username`, `password`,`role`) values ('$_matri','$_user','$_pass','$_role')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array());
    if($stmt){
    //     header("Location:../login.php");
    // }else {
    //     header("Location:../join.php");
    // }
     echo "
        <script>
            alert(' Enregistrement du compte utilisateur reussi !');
            window.location.href='../login.php';
        </script>      
    ";   }else {
            echo "
        <script> 
            alert('Echec Enregistrement ! ');
            window.location.href='../sign.php';
        </script>
    ";}

} catch(PDOException $ex){
    echo  $ex->getMessage();
       
}
    } else {
        print("Try again ");
    }
    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}