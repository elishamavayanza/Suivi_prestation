<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les sections
    $sections_result = mysqli_query($con, "
        SELECT s.*, i.nomComplet as isp_nom 
        FROM section s 
        LEFT JOIN isp i ON s.code_isp = i.code_isp
    ");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Sections</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Sections</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter une nouvelle section</h3>
        <?php
            include("../formulaire/formsection.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des sections</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>ISP</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($section = mysqli_fetch_assoc($sections_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($section['code_section']); ?></td>
                    <td><?php echo htmlspecialchars($section['sigle']); ?></td>
                    <td><?php echo htmlspecialchars($section['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($section['isp_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($section['dt_creation'])); ?></td>
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