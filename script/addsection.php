<?php 
    include("config.php");

    if(!empty($_POST['sigle']) && !empty($_POST['code']) && !empty($_POST['nomComplet']) && !empty($_POST['description']) && !empty($_POST['dtcreation'])){
        $code = $_POST['code'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dt = $_POST['dtcreation'];
        
        try{
            $sql = "INSERT INTO section(`dt_creation`, `sigle`, `nomComplet`, `description`, `code_isp`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$dt, $sigle, $denomination, $description, $code]);
            
            if($result){
                echo "
                <script>
                    alert('Enregistrement réussi !');
                    window.location.href='../admin/section.php';
                </script>";   
            } else {
                echo "
                <script> 
                    alert('Enregistrement échoué ! ');
                    window.location.href='../admin/section.php';
                </script>";
            } 
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/section.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs !');
            window.location.href='../admin/section.php';
        </script>";
    }
    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}