<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si la connexion a réussi
    if (!$con) {
        die("Échec de la connexion à la base de données");
    }
    
    // Requêtes pour obtenir les statistiques
    $etudiants_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM etudiant"));
    $enseignants_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM enseignant"));
    $cours_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM cours"));
    $horaire_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM horaire"));
    
    // Obtenir les statistiques pour l'année en cours
    $current_year = date('Y');
    $etudiants_this_year = mysqli_num_rows(mysqli_query($con, "SELECT * FROM etudiant WHERE YEAR(dt_save) = '$current_year'"));
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
            <div class="card-value"><?php echo $etudiants_count; ?></div>
            <div class="card-footer"><?php echo $etudiants_this_year; ?> inscrits cette année</div>
        </div>
        
        <div class="card charges">
            <div class="card-title">Enseignants</div>
            <div class="card-value"><?php echo $enseignants_count; ?></div>
            <div class="card-footer">Enseignants enregistrés</div>
        </div>
        
        <div class="card prestations">
            <div class="card-title">Cours</div>
            <div class="card-value"><?php echo $cours_count; ?></div>
            <div class="card-footer">Cours définis</div>
        </div>
        
        <div class="card horaires">
            <div class="card-title">Horaires</div>
            <div class="card-value"><?php echo $horaire_count; ?></div>
            <div class="card-footer">Programmes établis</div>
        </div>
    </div>

    <?php
    // Récupérer les dernières activités
    $activites_result = mysqli_query($con, "
        (SELECT 'Ajout d\'étudiant' as activite, dt_save as date, CONCAT(nom, ' ', postnom) as detail FROM etudiant ORDER BY dt_save DESC LIMIT 2)
        UNION ALL
        (SELECT 'Ajout d\'enseignant' as activite, dtsave as date, CONCAT(nom, ' ', postnom) as detail FROM enseignant ORDER BY dtsave DESC LIMIT 2)
        UNION ALL
        (SELECT 'Programmation de cours' as activite, datejour as date, idcours as detail FROM horaire WHERE datejour IS NOT NULL ORDER BY datejour DESC LIMIT 2)
        ORDER BY date DESC LIMIT 5
    ");
    ?>

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

    <?php if (mysqli_num_rows($activites_result) > 0): ?>
    <div class="admin-table mt-20">
        <h3 class="form-title">Activités récentes</h3>
        <table>
            <thead>
                <tr>
                    <th>Activité</th>
                    <th>Détail</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($activity = mysqli_fetch_assoc($activites_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($activity['activite']); ?></td>
                    <td><?php echo htmlspecialchars($activity['detail']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($activity['date'])); ?></td>
                    <td><span style="color: green;">Terminé</span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <?php
    // Statistiques mensuelles des inscriptions
    $monthly_stats = mysqli_query($con, "
        SELECT 
            MONTH(dt_save) as month,
            COUNT(*) as count
        FROM etudiant 
        WHERE YEAR(dt_save) = '$current_year'
        GROUP BY MONTH(dt_save)
        ORDER BY month
    ");
    
    $monthly_data = array();
    for ($i = 1; $i <= 12; $i++) {
        $monthly_data[$i] = 0;
    }
    
    while ($row = mysqli_fetch_assoc($monthly_stats)) {
        $monthly_data[$row['month']] = $row['count'];
    }
    ?>
    
    <div class="admin-table mt-20">
        <h3 class="form-title">Inscriptions par mois (<?php echo $current_year; ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Jan</th>
                    <th>Fév</th>
                    <th>Mar</th>
                    <th>Avr</th>
                    <th>Mai</th>
                    <th>Juin</th>
                    <th>Juil</th>
                    <th>Aoû</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Déc</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Inscriptions</th>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                    <td><?php echo $monthly_data[$i]; ?></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>