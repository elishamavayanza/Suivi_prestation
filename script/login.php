<?php       
    session_start();
    include("config.php");
    
    // 确保数据库连接有效
    if ($pdo === null) {
        die("Échec de la connexion à la base de données");
    }
    
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role']; 
        try{            
            $sql = "SELECT * FROM `utilisateur` WHERE `username` =:username AND `password` =:password AND `role` =:role";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array('username'=>$username,'password'=>$password, 'role'=>$role));
            $result = $stmt->fetch();    
            if($result){
                $_SESSION['username']=$username;
                $_SESSION['password']=$password;
                $_SESSION['role']=$result['role'];
                $_SESSION['matricule']=$result['matricule'];
                echo "<script>
                                alert('Vous etes maintenant connecte !');
                                window.location.href='../index.php';
                        </script>"; 
            }else{
                echo "<script>
                                alert('Echec de Connexion ! Vérifiez vos identifiants et rôle.');
                                window.location.href='../login.php';
                            </script>"; 
            }            
           
        } catch(PDOException $ex){
            echo "
                <script> 
                    alert('Erreur de base de données : " . addslashes($ex->getMessage()) . "');
                    window.location.href='../login.php';
                </script>
            ";
        }
    } else {
        echo "<script>
                        alert('Veuillez remplir tous les champs !');
                        window.location.href='../login.php';
              </script>";
    }

function MessageAlert($message){
    echo "<script>alert('" . addslashes($message) . "');</script>";
}