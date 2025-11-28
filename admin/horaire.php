<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir tous les horaires
    $horaires_result = mysqli_query($con, "
        SELECT h.*, c.nomComplet as cours_nom, m.nomComplet as mention_nom, p.nomComplet as promo_nom
        FROM horaire h
        LEFT JOIN cours c ON h.idcours = c.code_cours
        LEFT JOIN mention m ON h.codemention = m.code_mention
        LEFT JOIN promotion p ON h.codepromotion = p.sigle_promotion
    ");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Horaires</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Horaires</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Programmer un cours</h3>
        <?php
            include("../formulaire/formhoraire.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Emploi du temps</h3>
        <table>
            <thead>
                <tr>
                    <th>Cours</th>
                    <th>Mention</th>
                    <th>Promotion</th>
                    <th>Jour & Heure</th>
                    <th>Enseignant</th>
                    <th>Site</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($horaire = mysqli_fetch_assoc($horaires_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($horaire['cours_nom'] ?? $horaire['idcours']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['mention_nom'] ?? $horaire['codemention']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['promo_nom'] ?? $horaire['codepromotion']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['jourheure']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['enseignant']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['site']); ?></td>
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