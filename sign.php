<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="auth_styles.css">
    <title>Création de compte - Suivi des Prestations</title>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <img src="image/logo.jpg" alt="Logo" class="auth-logo">
            <h2>Création de compte</h2>
            <p>Créez votre compte utilisateur</p>
        </div>
        <form id="signupForm" class="auth-form" action="script/sign.php" method="POST">
            <div class="form-group">
                <label for="matricule">Matricule</label>
                <input type="text" name="matricule" id="matricule" class="form-control" required placeholder="Entrez votre matricule">
            </div>
            
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="Choisissez un nom d'utilisateur">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Choisissez un mot de passe">
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
                <input type="checkbox" name="terms" id="terms" required>
                <label for="terms">J'accepte les conditions d'utilisation</label>                    
            </div>
            
            <button type="submit" class="btn btn-primary">Créer le compte</button>
        </form>
        <div class="auth-footer">
            <p>Vous avez déjà un compte ?</p>
            <a href="login.php" class="btn btn-secondary">Se connecter</a>
        </div>
    </div>
</body>
</html>