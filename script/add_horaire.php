<?php 
    include("connexion.php");

    // Fonction pour créer une nouvelle année universitaire
    function creerNouvelleAnnee($con) {
        // Vérifier si une année existe déjà pour l'année en cours
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $annee_description = $current_year . "-" . $next_year;
        
        // Vérifier si cette année existe déjà
        $check_query = mysqli_query($con, "SELECT * FROM annee WHERE description = '$annee_description'");
        
        if(mysqli_num_rows($check_query) == 0) {
            // Créer la nouvelle année (du 15 septembre de l'année en cours au 15 juillet de l'année suivante)
            $date_debut = $current_year . "-09-15";
            $date_fin = $next_year . "-07-15";
            
            $insert_query = "INSERT INTO annee(dt_debut, dt_fin, description) VALUES('$date_debut', '$date_fin', '$annee_description')";
            mysqli_query($con, $insert_query);
        }
    }

    if(!empty($_POST['idcours']) && !empty($_POST['jourheure']) && !empty($_POST['codemention']) && !empty($_POST['codepromotion']) && !empty($_POST['enseignant']) && !empty($_POST['site']) && !empty($_POST['periode'])){
        // Créer automatiquement une nouvelle année lors de l'ajout d'un horaire
        creerNouvelleAnnee($con);
        
        $idcours = $_POST['idcours'];
        $jourheure = $_POST['jourheure'];
        $codemention = $_POST['codemention'];
        $codepromotion = $_POST['codepromotion'];
        $enseignant = $_POST['enseignant'];
        $site = $_POST['site'];
        $periode = $_POST['periode'];
        $observation = !empty($_POST['observation']) ? $_POST['observation'] : '';
        $datejour = !empty($_POST['datejour']) ? $_POST['datejour'] : date('Y-m-d');
        
        // Ajout de la valeur 0 pour le champ 'id' qui ne peut pas être NULL
        $sql = "INSERT INTO horaire(idcours,id,jourheure,codemention,codepromotion,enseignant,site,periode,observation,datejour) VALUES('$idcours',0,'$jourheure','$codemention','$codepromotion','$enseignant','$site','$periode','$observation','$datejour')";
        $result = mysqli_query($con, $sql);

        if($result){
            echo "
            <script>
                alert('Ajout réussi !');
                window.location.href='../admin/horaire.php';
            </script>";   
        } else {
            echo "
            <script>
                alert('Échec de l\\'ajout !');
                window.location.href='../admin/horaire.php';
            </script>";   
        }
    } else {
        echo "
        <script>
            alert('Veuillez remplir tous les champs requis !');
            window.location.href='../admin/horaire.php';
        </script>";
    }
?>