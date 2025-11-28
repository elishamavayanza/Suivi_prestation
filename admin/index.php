<?php 
    include("nav_dash.php");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Bienvenue dans le tableau de bord</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Tableau de bord</li>
        </ul>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <div class="card-title">Nombre d'étudiants</div>
            <div class="card-value">120</div>
            <div class="card-footer">Total inscrits cette année</div>
        </div>
        
        <div class="card charges">
            <div class="card-title">Charges horaires</div>
            <div class="card-value">24</div>
            <div class="card-footer">Heures programmées cette semaine</div>
        </div>
        
        <div class="card prestations">
            <div class="card-title">Prestations</div>
            <div class="card-value">85%</div>
            <div class="card-footer">Suivi des cours effectués</div>
        </div>
        
        <div class="card horaires">
            <div class="card-title">Enseignants</div>
            <div class="card-value">28</div>
            <div class="card-footer">Actifs ce semestre</div>
        </div>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Vue d'ensemble du système</h3>
        <p>Bienvenue dans le système de suivi des prestations de cours. En tant qu'administrateur, vous pouvez :</p>
        <ul style="margin-left: 20px; margin-top: 15px;">
            <li>Gérer les années académiques</li>
            <li>Inscrire et gérer les étudiants</li>
            <li>Ajouter et gérer les enseignants</li>
            <li>Créer et organiser les sections, mentions et promotions</li>
            <li>Programmer les cours et établir les horaires</li>
            <li>Consulter les fiches de prestation quotidienne</li>
            <li>Suivre les analyses de performance</li>
        </ul>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Activités récentes</h3>
        <table>
            <thead>
                <tr>
                    <th>Activité</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ajout d'enseignant</td>
                    <td>Nouvel enseignant ajouté en Informatique</td>
                    <td>12/11/2025</td>
                    <td><span style="color: green;">Terminé</span></td>
                </tr>
                <tr>
                    <td>Programmation de cours</td>
                    <td>Horaire de Mathématiques mis à jour</td>
                    <td>11/11/2025</td>
                    <td><span style="color: green;">Terminé</span></td>
                </tr>
                <tr>
                    <td>Inscription d'étudiants</td>
                    <td>15 nouveaux étudiants inscrits en L1</td>
                    <td>10/11/2025</td>
                    <td><span style="color: green;">Terminé</span></td>
                </tr>
                <tr>
                    <td>Mise à jour de prestation</td>
                    <td>Rapport de prestation mensuel généré</td>
                    <td>09/11/2025</td>
                    <td><span style="color: orange;">En cours</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>