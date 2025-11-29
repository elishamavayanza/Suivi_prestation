<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="auth_styles.css">
    <title>Connexion - Suivi des Prestations</title>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <img src="image/logo.jpg" alt="Logo" class="auth-logo">
            <h2>Connexion</h2>
            <p>Veuillez vous connecter à votre compte</p>
        </div>
        <form id="connexionForm" class="auth-form" action="script/login.php" method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="Entrez votre nom d'utilisateur">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Entrez votre mot de passe">
            </div>
            
            <div class="form-group">
                <label for="role">Rôle</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Sélectionnez votre rôle</option>
                    <option value="Admin">Administrateur</option>
                    <option value="Chefdesection">Chef de section</option>
                    <option value="Chefpromotion">Chef de promotion</option>
                    <option value="SGA">SGA</option>
                    <option value="AB">AB</option>
                    <option value="secretaire">Secrétaire</option>
                    <option value="Enseignant">Enseignant</option>
                    <option value="Etudiant">Étudiant</option>
                </select>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Se souvenir de moi</label>                    
            </div>
            
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <div class="auth-footer">
            <p>Vous n'avez pas encore de compte ?</p>
            <a href="sign.php" class="btn btn-secondary">Créer un compte</a>
        </div>
    </div>
</body>     
</html>