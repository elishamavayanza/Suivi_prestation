<?php
// Fonctions pour la gestion des horaires

function createAcademicYearIfNotExists($con) {
    // Créer automatiquement une année académique si elle n'existe pas
    $current_year = date('Y');
    $next_year = $current_year + 1;
    $academic_year_desc = $current_year.'-'.$next_year;
    $academic_start = $current_year.'-09-01'; // Début d'année académique
    $academic_end = $next_year.'-06-30'; // Fin d'année académique
    
    // Vérifier si cette année existe déjà
    $check_year = mysqli_query($con, "SELECT * FROM annee WHERE description = '".$academic_year_desc."'");
    if(mysqli_num_rows($check_year) == 0){
        // Si l'année n'existe pas, la créer
        mysqli_query($con, "INSERT INTO annee(dt_debut, dt_fin, description) VALUES('".$academic_start."','".$academic_end."','".$academic_year_desc."')");
        return true;
    }
    return false;
}

function addHoraire($con, $data) {
    // Créer l'année académique si nécessaire
    createAcademicYearIfNotExists($con);
    
    // Insérer l'horaire
    $sql = "INSERT INTO horaire(idcours,jourheure,codemention,codepromotion,enseignant,site,periode,observation,datejour) 
            VALUES('".$data['cours']."','".$data['jour']."','".$data['dep']."','".$data['pro']."','".$data['enseignant']."','".$data['site']."','".$data['periode']."','".$data['observation']."','".$data['dte']."')";
    
    return mysqli_query($con, $sql);
}

function updateHoraire($con, $data) {
    $sql = "UPDATE horaire SET 
                idcours='".$data['cours']."', 
                jourheure='".$data['jour']."',
                codemention='".$data['dep']."',
                codepromotion='".$data['pro']."',
                enseignant='".$data['enseignant']."',
                site='".$data['site']."',
                periode='".$data['periode']."',
                observation='".$data['observation']."',
                datejour='".$data['dte']."' 
            WHERE idhoraire=" . $data['id'];
    
    return mysqli_query($con, $sql);
}

function deleteHoraire($con, $id) {
    $sql = "DELETE FROM horaire WHERE idhoraire = " . intval($id);
    return mysqli_query($con, $sql);
}
?>