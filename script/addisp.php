<?php 
    include("config.php");

     if(!empty($_POST['arrete'])){
        $arrete = $_POST['arrete'];
        $sigle = $_POST['sigle'];
        $denomination = $_POST['nomComplet'];
        $description = $_POST['description'];
        $boit = $_POST['boitepostale'];
        $dt = $_POST['dtcreation'];
        
        // Créer automatiquement une année académique
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $academic_year_desc = $current_year.'-'.$next_year;
        $academic_start = $current_year.'-09-01'; // Début d'année académique
        $academic_end = $next_year.'-06-30'; // Fin d'année académique
        
        try{
            // Insérer l'ISP
            $sql ="insert into isp(`code_isp`, `dt_creation`, `sigle`, `nomComplet`, `numArreterminister`, `description`, `boitepostal`) values (?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$arrete, $dt, $sigle, $denomination, $arrete, $description, $boit]);
           
            if($result){
                // Créer une année académique automatiquement
                $sql_year = "INSERT INTO annee(`dt_debut`, `dt_fin`, `description`) VALUES (?, ?, ?)";
                $stmt_year = $pdo->prepare($sql_year);
                $stmt_year->execute([$academic_start, $academic_end, $academic_year_desc]);
                
                MessageAlert("Enregistrement reussi et année académique créée");
                header("Location:../admin/isp.php");
            } else {     
                echo "
                    <script>
                        alert(' Échec d\'enregistrement !');
                        window.location.href='../admin/isp.php';
                    </script>      
                "; 
            }
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Echec Enregistrement une error est survenu : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../admin/isp.php';
                </script>
            ";
        }
 } else {
        echo "
            <script>
                alert('Veuillez remplir tous les champs requis !');
                window.location.href='../admin/isp.php';
            </script>
        ";
    }
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}