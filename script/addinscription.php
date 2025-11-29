<?php 
    include("config.php");

    if(!empty($_POST['Section']) && !empty($_POST['dapartement']) && !empty($_POST['promotion']) && !empty($_POST['matricule']) && !empty($_POST['dtinscription'])){
        $code_section = $_POST['Section'];
        $code_mention = $_POST['dapartement'];
        $codepromotion = $_POST['promotion'];
        $matriculeEtudiant = $_POST['matricule'];
        $date_inscription = $_POST['dtinscription'];
        $description = !empty($_POST['description']) ? $_POST['description'] : '';
        
        try{
            $sql = "INSERT INTO inscription(`matriculeEtudiant`, `codepromotion`, `code_mention`, `code_section`, `date_inscription`, `description`) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$matriculeEtudiant, $codepromotion, $code_mention, $code_section, $date_inscription, $description]);
            
            if($result){
                echo "
                <script>
                    alert('Inscription réussie !');
                    window.location.href='../admin/inscription.php';
                </script>";   
            } else {
                echo "
                <script> 
                    alert('Échec de l\\'inscription !');
                    window.location.href='../admin/inscription.php';
                </script>";
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Une erreur est survenue : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/inscription.php';
                </script>";
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs obligatoires !');
            window.location.href='../admin/inscription.php';
        </script>";
    }
?>