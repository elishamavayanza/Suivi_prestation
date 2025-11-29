<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les années académiques
    $annees_result = mysqli_query($con, "SELECT * FROM annee");
    
    // Vérifier si on est en mode édition
    $editing_year = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "SELECT * FROM annee WHERE code_annee = '$edit_id'");
        $editing_year = mysqli_fetch_assoc($edit_result);
    }
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
        <h3 class="form-title"><?php echo $editing_year ? 'Modifier une année académique' : 'Ajouter une nouvelle année académique'; ?></h3>
        <div class="formulaire">
            <form id="anneeForm" action="../script/<?php echo $editing_year ? 'update_year.php' : 'addyear.php'; ?>" method="POST">
                <?php if ($editing_year): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_year['code_annee']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="annee_academique">Année académique :</label>
                        <input type="text" id="annee_academique" name="annee_academique" class="form-control" 
                               placeholder="Ex: 2025-2026" 
                               value="<?php echo $editing_year ? htmlspecialchars($editing_year['description']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-col">
                        <label for="date_debut">Date de début :</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control" 
                               value="<?php echo $editing_year ? htmlspecialchars($editing_year['dt_debut']) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" 
                               value="<?php echo $editing_year ? htmlspecialchars($editing_year['dt_fin']) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_year ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_year ? 'Modifier Année' : 'Ajouter Année'; ?>
                </button>
                
                <?php if ($editing_year): ?>
                    <a href="annee.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
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
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée par $editing_year
                $annees_result = mysqli_query($con, "SELECT * FROM annee");
                while ($annee = mysqli_fetch_assoc($annees_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($annee['code_annee']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($annee['dt_debut'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($annee['dt_fin'])); ?></td>
                    <td><?php echo htmlspecialchars($annee['description']); ?></td>
                    <td class="table-actions">
                        <a href="annee.php?edit=<?php echo $annee['code_annee']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_year.php?id=<?php echo $annee['code_annee']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette année académique ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
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