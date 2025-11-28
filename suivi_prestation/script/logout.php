<?php
function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}
    session_start();
    session_unset();
    session_destroy();
    ##header("Location:../index.php");
     echo "
        <script> 
            alert('Vous etes maintenant deconnecte! Merci de penser a se reconnecter');
            window.location.href='../index.php';
        </script>
    ";
    ?>