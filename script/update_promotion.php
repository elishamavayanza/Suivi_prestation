<?php 
    include("config.php");

    if(!empty($_POST['id']) && !empty($_POST['codedepart']) && !empty($_POST['sigle']) && !empty($_POST['nomComplet']) && !empty($_POST['description']) && !empty($_POST['dtcreation'])){
        $id = $_POST['id'];
        $code_mention = $_POST['codedepart'];
        $sigle_promotion = $_POST['sigle'];
        $nomComplet = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dtcreation = $_POST['dtcreation'];
        
        try{
            $sql = "UPDATE promotion SET sigle_promotion = ?, nomComplet = ?, code_mention = ?, description = ?, dtcreation = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$sigle_promotion, $nomComplet, $code_mention, $description, $dtcreation, $id]);      

            if($result){
                echo "
                <script>
                    alert('Modification réussie !');
                    window.location.href='../admin/promotion.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de modification !');
                    window.location.href='../admin/promotion.php';
                </script>";   
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/promotion.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs !');
            window.location.href='../admin/promotion.php';
        </script>";
    }
?>