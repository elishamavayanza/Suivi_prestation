<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Evaluation </title>
</head>
<body>
    <div class="container">
        <h2>Creer votre compte utilisateur</h2>
        <img src="image/logo.jpg" alt="logo" width="20px">
        <form id="connexionForm" action="script/sign.php" method="POST">
            <label for="">Matricule</label>
            <input type="text" name="matricule" id="matri" riquired placeholder="isp-MuHangi">
            <label for="NomUt">Nom d'utilisateur</label>
            <input type="text" id="nom_util" name="username" required placeholder="Isp-Muhangi">
            <label for="Mot_passe">Mot de passe</label>
            <input type="password" id="passe_word" name="password" required placeholder="1234567890">
            <label for="role">Role : </label>
            <input type="text" id="role" name="role" required placeholder="role">
            <div class="check_box">
                <input type="checkbox" name=""id>
                <label for="">Se souvenir de moi</label>                    
            </div>
            <button type="submit">Creer compte</button>
            <button type="submit"><a href="login.php">Connexion</a></button>
        </form>
    </div>
</body>
</html>