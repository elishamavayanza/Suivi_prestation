<?php 
    include("config.php");

    if(!empty($_POST['id']) && !empty($_POST['code_cours']) && !empty($_POST['nomComplet']) && !empty($_POST['nbreHeure']) && !empty($_POST['ponderation']) && !empty($_POST['code_mention']) && !empty($_POST['code_section']) && !empty($_POST['promotion']) && !empty($_POST['description'])){
        $id = $_POST['id'];
        $code = $_POST['code_cours'];
        $denomination =$_POST['nomComplet'];
        $promotion = $_POST['promotion'];
        $mention =$_POST['code_mention'];
        $ec =$_POST['code_section'];
        $heure = $_POST['nbreHeure'];
        $ponderation = $_POST['ponderation'];
        $description = $_POST['description'];
        
        try{
            $sql = "UPDATE cours SET code_cours = ?, nomComplet = ?, nbreHeure = ?, ponderation = ?, code_mention = ?, code_section = ?, promotion = ?, description = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$code, $denomination, $heure, $ponderation, $mention, $ec, $promotion, $description, $id]);      

            if($result){
                echo "
                <script>
                    alert('Modification réussie !');
                    window.location.href='../admin/cours.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de modification !');
                    window.location.href='../admin/cours.php';
                </script>";   
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/cours.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs obligatoires !');
            window.location.href='../admin/cours.php';
        </script>";
    }
?>