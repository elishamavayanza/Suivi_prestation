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
        <h2>Veuillez vous Connectez</h2>
        <img src="image/logo.jpg" alt="Profile" width="20px">
        <form id="connexionForm" action="script/login.php" method="POST">
            <label for="NomUt">Nom d'utilisateur</label>
            <input type="text" id="nom_util" name="username" required placeholder="DieulaRaphael">

            <label for="Mot_passe">Mot de passe</label>
            <input type="password" id="passe_word" name="password" required placeholder="1234567890">
            <div class="check_box">
                <input type="checkbox" name=""id>
                <label for="">Se souvenir de moi</label>                    
            </div>
            <button type="submit">Connection</button>
            <button type="submit"><a href="sign.php"> Creer un compte </a></button>
        </form>
    </div>
</body>     
</html>