<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si on est en mode édition
    $editing_inscription = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "
            SELECT i.*, e.nom as etudiant_nom, e.postnom as etudiant_postnom, 
                   p.nomComplet as promotion_nom, m.nomComplet as mention_nom, s.nomComplet as section_nom
            FROM inscription i
            LEFT JOIN etudiant e ON i.matriculeEtudiant = e.matriculeEtudiant
            LEFT JOIN promotion p ON i.codepromotion = p.sigle_promotion
            LEFT JOIN mention m ON i.code_mention = m.code_mention
            LEFT JOIN section s ON i.code_section = s.code_section
            WHERE i.code_inscription = '$edit_id'
        ");
        $editing_inscription = mysqli_fetch_assoc($edit_result);
    }
    
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
    
    // Récupérer les données pour les listes déroulantes
    $sections = mysqli_query($con, "SELECT * FROM section");
    $mentions = mysqli_query($con, "SELECT * FROM mention");
    $promotions = mysqli_query($con, "SELECT * FROM promotion");
    $etudiants = mysqli_query($con, "SELECT * FROM etudiant");
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
        <h3 class="form-title"><?php echo $editing_inscription ? 'Modifier une inscription' : 'Inscrire un étudiant'; ?></h3>
        <div class="formulaire">
            <form id="inscriptionForm" action="../script/<?php echo $editing_inscription ? 'update_inscription.php' : 'addinscription.php'; ?>" method="POST">
                <?php if ($editing_inscription): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_inscription['code_inscription']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="Section">Section :</label>
                        <select name="Section" id="Section" class="form-control" required>
                            <option value="">Sélectionner une section</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $sections = mysqli_query($con, "SELECT * FROM section");
                            while($section = mysqli_fetch_assoc($sections)): ?>
                            <option value="<?php echo $section['code_section']; ?>" 
                                <?php echo ($editing_inscription && $editing_inscription['code_section'] == $section['code_section']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="dapartement">Mention :</label>
                        <select name="dapartement" id="dapartement" class="form-control" required>
                            <option value="">Sélectionner une mention</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $mentions = mysqli_query($con, "SELECT * FROM mention");
                            while($mention = mysqli_fetch_assoc($mentions)): ?>
                            <option value="<?php echo $mention['code_mention']; ?>" 
                                <?php echo ($editing_inscription && $editing_inscription['code_mention'] == $mention['code_mention']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mention['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="promotion">Promotion :</label>
                        <select name="promotion" id="promotion" class="form-control" required>
                            <option value="">Sélectionner une promotion</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $promotions = mysqli_query($con, "SELECT * FROM promotion");
                            while($promo = mysqli_fetch_assoc($promotions)): ?>
                            <option value="<?php echo $promo['sigle_promotion']; ?>" 
                                <?php echo ($editing_inscription && $editing_inscription['codepromotion'] == $promo['sigle_promotion']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($promo['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="matricule">Matricule :</label>
                        <input type="text" id="matricule" name="matricule" class="form-control" required 
                               placeholder="Matricule de l'étudiant" 
                               value="<?php echo $editing_inscription ? htmlspecialchars($editing_inscription['matriculeEtudiant']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="dtinscription">Date d'inscription :</label>
                        <input type="date" id="dtinscription" name="dtinscription" class="form-control" required 
                               value="<?php echo $editing_inscription ? htmlspecialchars($editing_inscription['date_inscription']) : date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-col">
                        <label for="description">Description :</label>
                        <input type="text" id="description" name="description" class="form-control" 
                               placeholder="Description de l'inscription" 
                               value="<?php echo $editing_inscription ? htmlspecialchars($editing_inscription['description']) : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_inscription ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_inscription ? 'Modifier Inscription' : 'Inscrire Étudiant'; ?>
                </button>
                
                <?php if ($editing_inscription): ?>
                    <a href="inscription.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
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
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée
                $inscriptions_result = mysqli_query($con, "
                    SELECT i.*, e.nom as etudiant_nom, e.postnom as etudiant_postnom, 
                           p.nomComplet as promotion_nom, m.nomComplet as mention_nom, s.nomComplet as section_nom
                    FROM inscription i
                    LEFT JOIN etudiant e ON i.matriculeEtudiant = e.matriculeEtudiant
                    LEFT JOIN promotion p ON i.codepromotion = p.sigle_promotion
                    LEFT JOIN mention m ON i.code_mention = m.code_mention
                    LEFT JOIN section s ON i.code_section = s.code_section
                ");
                while ($inscription = mysqli_fetch_assoc($inscriptions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inscription['code_inscription']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['etudiant_nom'] . ' ' . $inscription['etudiant_postnom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['promotion_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['mention_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['section_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($inscription['date_inscription'])); ?></td>
                    <td class="table-actions">
                        <a href="inscription.php?edit=<?php echo $inscription['code_inscription']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_inscription.php?id=<?php echo $inscription['code_inscription']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')">
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