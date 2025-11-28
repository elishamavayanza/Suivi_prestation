<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les inscriptions
    $inscriptions_result = mysqli_query($con, "
        SELECT i.*, e.nom as etudiant_nom, e.postnom as etudiant_postnom, 
               p.nomComplet as promotion_nom, m.nomComplet as mention_nom, s.nomComplet as section_nom
        FROM inscription i
        LEFT JOIN etudiant e ON i.matriculeEtudiant = e.matriculeEtudiant
        LEFT JOIN promotion p ON i.codepromotion = p.sigle_promotion
        LEFT JOIN mention m ON i.code_mention = m.code_mention
        LEFT JOIN section s ON i.code_section = s.code_section
    ");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Inscriptions</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Inscriptions</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Inscrire un étudiant</h3>
        <?php
            include("../formulaire/forminscription.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des inscriptions</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Étudiant</th>
                    <th>Promotion</th>
                    <th>Mention</th>
                    <th>Section</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($inscription = mysqli_fetch_assoc($inscriptions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inscription['code_inscription']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['etudiant_nom'] . ' ' . $inscription['etudiant_postnom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['promotion_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['mention_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['section_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($inscription['date_inscription'])); ?></td>
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