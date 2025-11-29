<?php 
    include("config.php");

    if(!empty($_POST['id']) && !empty($_POST['code']) && !empty($_POST['sigle']) && !empty($_POST['nomComplet']) && !empty($_POST['description']) && !empty($_POST['dtcreation'])){
        $id = $_POST['id'];
        $code_isp = $_POST['code'];
        $sigle = $_POST['sigle'];
        $nomComplet = $_POST['nomComplet'];
        $description = $_POST['description'];
        $dt_creation = $_POST['dtcreation'];
        
        try{
            $sql = "UPDATE section SET dt_creation = ?, sigle = ?, nomComplet = ?, description = ?, code_isp = ? WHERE code_section = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$dt_creation, $sigle, $nomComplet, $description, $code_isp, $id]);      

            if($result){
                echo "
                <script>
                    alert('Modification réussie !');
                    window.location.href='../admin/section.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de modification !');
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
?>