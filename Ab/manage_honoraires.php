<?php
session_start();
include("../script/config.php");

// Vérifier si l'utilisateur est connecté et a le rôle AB
if (!isset($_SESSION['role']) || $_SESSION['role'] != "AB") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Honoraires - Interface AB</title>
    <link rel="stylesheet" href="ab_styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="ab-header">
        <h1><i class="fas fa-money-check-alt"></i> Gestion des Honoraires - Interface AB</h1>
        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <div>
                    <div>Bienvenue, <?php echo $_SESSION['username']; ?></div>
                    <div>Administrateur Budget</div>
                </div>
            </div>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </div>
    
    <div class="ab-container">
        <!-- Sidebar Navigation -->
        <div class="ab-sidebar">
            <div class="ab-sidebar-header">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <h2><?php echo $_SESSION['username']; ?></h2>
                <p>Administrateur Budget</p>
            </div>
            <div class="ab-nav-menu">
                <ul>
                    <li>
                        <a href="index.php">
                            <i class="fas fa-home"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="financial_reports.php">
                            <i class="fas fa-chart-line"></i>
                            <span>Rapports Financiers</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage_honoraires.php" class="active">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Gérer les Honoraires</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h2><i class="fas fa-list"></i> Liste des Honoraires</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil AB</a></li>
                    <li>Gestion des Honoraires</li>
                </ul>
            </div>
            
            <div class="academic-section">
                <div class="table-container academic-table">
                    <table id="honoraires-table">
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Promotion</th>
                                <th>Montant</th>
                                <th>Devise</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT h.*, e.nom as enseignant_nom, e.postnom as enseignant_postnom, 
                                           e.prenom as enseignant_prenom, c.nomComplet as cours_nom, 
                                           p.nomComplet as promotion_nom
                                    FROM honoraire h
                                    JOIN enseignant e ON h.matricule_enseignant = e.matriculeEnseignant
                                    JOIN cours c ON h.code_cours = c.code_cours
                                    JOIN promotion p ON h.code_promotion = p.sigle_promotion
                                    ORDER BY h.datesve DESC";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['enseignant_nom'] . " " . $row['enseignant_postnom'] . " " . $row['enseignant_prenom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['cours_nom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['promotion_nom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['montant']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['devise']) . "</td>";
                                echo "<td>" . htmlspecialchars(date('d/m/Y', strtotime($row['datesve']))) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>
                                        <button class='btn btn-sm btn-primary edit-honoraire' data-id='" . $row['id'] . "'>
                                            <i class='fas fa-edit'></i> Modifier
                                        </button>
                                        <button class='btn btn-sm btn-danger delete-honoraire' data-id='" . $row['id'] . "'>
                                            <i class='fas fa-trash'></i> Supprimer
                                        </button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Gérer la suppression d'un honoraire
            $('.delete-honoraire').click(function() {
                const honoraireId = $(this).data('id');
                if (confirm('Êtes-vous sûr de vouloir supprimer cet honoraire ?')) {
                    $.ajax({
                        url: '../script/delete_honoraire.php',
                        method: 'POST',
                        data: { id: honoraireId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert('Honoraire supprimé avec succès');
                                location.reload();
                            } else {
                                alert('Erreur lors de la suppression: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('Erreur lors de la suppression de l\'honoraire');
                        }
                    });
                }
            });
            
            // Gérer la modification d'un honoraire
            $('.edit-honoraire').click(function() {
                const honoraireId = $(this).data('id');
                alert('Fonction de modification à implémenter pour l\'honoraire #' + honoraireId);
            });
        });
    </script>
</body>
</html>