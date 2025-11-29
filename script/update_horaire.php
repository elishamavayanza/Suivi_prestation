<?php 
    include("connexion.php");

    if(!empty($_POST['id']) && !empty($_POST['idcours']) && !empty($_POST['jourheure']) && !empty($_POST['codemention']) && !empty($_POST['codepromotion']) && !empty($_POST['enseignant']) && !empty($_POST['site']) && !empty($_POST['periode'])){
        $id = $_POST['id'];
        $idcours = $_POST['idcours'];
        $jourheure = $_POST['jourheure'];
        $codemention = $_POST['codemention'];
        $codepromotion = $_POST['codepromotion'];
        $enseignant = $_POST['enseignant'];
        $site = $_POST['site'];
        $periode = $_POST['periode'];
        $observation = !empty($_POST['observation']) ? $_POST['observation'] : '';
        $datejour = !empty($_POST['datejour']) ? $_POST['datejour'] : date('Y-m-d');
        
        $sql = "UPDATE horaire SET idcours='$idcours', jourheure='$jourheure', codemention='$codemention', codepromotion='$codepromotion', enseignant='$enseignant', site='$site', periode='$periode', observation='$observation', datejour='$datejour' WHERE idhoraire=$id";
        $result = mysqli_query($con, $sql);

        if($result){
            echo "
            <script>
                alert('Modification réussie !');
                window.location.href='../admin/horaire.php';
            </script>";   
        } else {
            echo "
            <script>
                alert('Échec de modification !');
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