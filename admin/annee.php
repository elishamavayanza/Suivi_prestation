<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les années académiques
    $annees_result = mysqli_query($con, "SELECT * FROM annee");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Années Académiques</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Années académiques</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter une nouvelle année académique</h3>
        <?php
            include("../formulaire/formannee.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des années académiques</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($annee = mysqli_fetch_assoc($annees_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($annee['code_annee']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($annee['dt_debut'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($annee['dt_fin'])); ?></td>
                    <td><?php echo htmlspecialchars($annee['description']); ?></td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>