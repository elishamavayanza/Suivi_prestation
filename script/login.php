    <?php       
    session_start();
    include("config.php");
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role']; 
        try{            
        $sql = "SELECT * FROM `utilisateur` WHERE `username` =:username AND `password` =:password";
        $stmt =$pdo->prepare($sql);
        $stmt->execute(array('username'=>$username,'password'=>$password));
        $result = $stmt->fetch();    
            if($result){
                $_SESSION['username']=$username;
                $_SESSION['password']=$password;
                $_SESSION['role']=$result['role'];
                $_SESSION['matricule']=$result['matricule'];
            if(isset($_SESSION['username'])){
                  echo "<script>
                                alert('Vous etes maintenant connecte !');
                                window.location.href='../index.php';
                        </script>      
                        "; 
                    }else{
                   echo "<script>
                                alert('Echec de Connexion !');
                                window.location.href='../login.php';
                            </script>      
                        "; 
                     }            
           
    } } catch(PDOException $ex){
    echo "
        <script> 
            alert('Echec Enregistrement ! ');
            window.location.href='../creerCompteUser.php';
        </script>
    ";
}
} else {
    echo "Try again";
}

function MessageAlert($message){
    echo "<script>alert('$message');</script>";
}