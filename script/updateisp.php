<?php 
    include("config.php");

    if(!empty($_POST['id']) && !empty($_POST['arrete']) && !empty($_POST['sigle']) && !empty($_POST['nomComplet']) && !empty($_POST['description']) && !empty($_POST['boitepostale']) && !empty($_POST['dtcreation'])){
        $id = $_POST['id'];
        $arrete = $_POST['arrete'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $boit = $_POST['boitepostale'];
        $dt = $_POST['dtcreation'];
        
        try{
            $sql = "UPDATE isp SET code_isp = ?, dt_creation = ?, sigle = ?, nomComplet = ?, numArreterminister = ?, description = ?, boitepostal = ? WHERE code_isp = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$arrete, $dt, $sigle, $denomination, $arrete, $description, $boit, $id]);      

            if($result){
                echo "
                <script>
                    alert('Modification réussie !');
                    window.location.href='../admin/isp.php';
                </script>";   
            } else {
                echo "
                <script>
                    alert('Échec de modification !');
                    window.location.href='../admin/isp.php';
                </script>";   
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/isp.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs !');
            window.location.href='../admin/isp.php';
        </script>";
    }
?>