<?php 
    include("config.php");

    if(!empty($_POST['id']) && !empty($_POST['codefac']) && !empty($_POST['sigle']) && !empty($_POST['nomComplet']) && !empty($_POST['description']) && !empty($_POST['dtcreation'])){
        $id = $_POST['id'];
        $code_section = $_POST['codefac'];
        $sigle = $_POST['sigle'];
        $nomComplet = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dtcreation = $_POST['dtcreation'];
        
        try{
            $sql = "UPDATE mention SET code_section = ?, sigle = ?, nomComplet = ?, description = ?, dtcreation = ? WHERE code_mention = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$code_section, $sigle, $nomComplet, $description, $dtcreation, $id]);      

            if($result){
                echo "
                <script>
                    alert('Modification réussie !');
                    window.location.href='../admin/mention.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de modification !');
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
?>