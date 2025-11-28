<?php 
    include("config.php");

     if(!empty($_POST['code'])){
        $code = $_POST['code'];
        $sigle =$_POST['sigle'];
        $denomination =$_POST['nomComplet'];
        $promotion = $_POST['promotion'];
        $mention =$_POST['mention'];
        $ec =$_POST['section'];
        $heure = $_POST['nbreHeure'];
        $max = (20/15)*$heure;
        $description = $_POST['description'];
    $sql ="insert into cours( `code_cours`, `nomComplet`, `nbreHeure`, `ponderation`, `code_mention`, `code_section`, `promotion`, `description`) values ('$code','$denomination','$heure','$max','$mention','$ec','$promotion','$description')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array());
    if($stmt){
        header("Location:../admin/index.php");
    }else {
        header("Location:../admin/index.php");
    }
    } else {
        print("Try again ");
    }
    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}