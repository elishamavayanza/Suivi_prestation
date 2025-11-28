<?php 
    include("config.php");

     if(!empty($_POST['sigle'])){
        $code =$_POST['codefac'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dt = $_POST['dtcreation'];
        try{
    $sql ="insert into mention(`code_section`, `sigle`, `nomComplet`, `description`, `dtcreation`) values ('$code','$sigle','$denomination','$description','$dt')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array());
   if($stmt){
         echo "
        <script>
            alert(' Enregistrement reussi !');
            window.location.href='../admin/index.php';
        </script>      
    ";   
   } else {
            echo "
        <script> 
            alert('Echec Enregistrement ! ');
            window.location.href='../admin/mention.php';
        </script>
    ";
   }
    
} catch(PDOException $ex){
    echo "
        <script> 
            alert('une erreure est survenue ! ');
            window.location.href='../admin/mention.php';
        </script>
    ";
}
    } else {
        print("Try again ");
    }
    
        function MessageAlert($message){
            echo "<script>alert('$message');</script>";
        }