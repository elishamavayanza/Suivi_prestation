<?php 
    include("config.php");

     if(!empty($_POST['code_cours']) && !empty($_POST['nomComplet']) && !empty($_POST['nbreHeure']) && !empty($_POST['ponderation']) && !empty($_POST['code_mention']) && !empty($_POST['code_section']) && !empty($_POST['promotion']) && !empty($_POST['description']) && !empty($_POST['semestre'])){
        $code = $_POST['code_cours'];
        $denomination =$_POST['nomComplet'];
        $promotion = $_POST['promotion'];
        $mention =$_POST['code_mention'];
        $ec =$_POST['code_section'];
        $heure = $_POST['nbreHeure'];
        $ponderation = $_POST['ponderation'];
        $description = $_POST['description'];
        $semestre = $_POST['semestre'];
        
        // Créer automatiquement une année académique
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $academic_year_desc = $current_year.'-'.$next_year;
        $academic_start = $current_year.'-09-01'; // Début d'année académique
        $academic_end = $next_year.'-06-30'; // Fin d'année académique
        
        try{
            // Insérer le cours
            $sql ="INSERT INTO cours(`code_cours`, `nomComplet`, `nbreHeure`, `ponderation`, `code_mention`, `code_section`, `promotion`, `description`, `semestre`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$code, $denomination, $heure, $ponderation, $mention, $ec, $promotion, $description, $semestre]);
            
            if($result){
                // Créer une année académique automatiquement
                $sql_year = "INSERT INTO annee(`dt_debut`, `dt_fin`, `description`) VALUES (?, ?, ?)";
                $stmt_year = $pdo->prepare($sql_year);
                $stmt_year->execute([$academic_start, $academic_end, $academic_year_desc]);
                
                echo "
                <script>
                    alert('Cours ajouté avec succès et année académique créée !');
                    window.location.href='../admin/cours.php';
                </script>";
            } else {
                echo "
                <script>
                    alert('Échec de l\'ajout du cours !');
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
            alert('Veuillez remplir tous les champs requis !');
            window.location.href='../admin/cours.php';
        </script>";
    }
    
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}