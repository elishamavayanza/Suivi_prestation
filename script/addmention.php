<?php 
    include("config.php");

    if(!empty($_POST['sigle']) && !empty($_POST['codefac']) && !empty($_POST['nomComplet']) && !empty($_POST['description']) && !empty($_POST['dtcreation'])){
        $code_section = $_POST['codefac'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dt = $_POST['dtcreation'];
        
        try{
            $sql = "INSERT INTO mention(`code_section`, `sigle`, `nomComplet`, `description`, `dtcreation`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$code_section, $sigle, $denomination, $description, $dt]);
            
            if($result){
                echo "
                <script>
                    alert('Enregistrement réussi !');
                    window.location.href='../admin/mention.php';
                </script>";   
            } else {
                echo "
                <script> 
                    alert('Échec d\\'enregistrement !');
                    window.location.href='../admin/mention.php';
                </script>";
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/mention.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs !');
            window.location.href='../admin/mention.php';
        </script>";
    }
    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}