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
    <title>Rapports Financiers - Interface AB</title>
    <link rel="stylesheet" href="ab_styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="ab-header">
        <h1><i class="fas fa-chart-line"></i> Rapports Financiers - Interface AB</h1>
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
                        <a href="financial_reports.php" class="active">
                            <i class="fas fa-chart-line"></i>
                            <span>Rapports Financiers</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage_honoraires.php">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Gérer les Honoraires</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h2><i class="fas fa-file-invoice-dollar"></i> Rapports Financiers</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil AB</a></li>
                    <li>Rapports Financiers</li>
                </ul>
            </div>
            
            <div class="academic-section">
                <h3><i class="fas fa-filter"></i> Filtres</h3>
                <form id="report-filter-form" class="form-inline">
                    <div class="form-group">
                        <label for="date_debut"><i class="fas fa-calendar-alt"></i> Date de début :</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date_fin"><i class="fas fa-calendar-alt"></i> Date de fin :</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrer</button>
                    <button type="button" id="reset-filters" class="btn btn-secondary"><i class="fas fa-redo"></i> Réinitialiser</button>
                </form>
            </div>
            
            <div class="academic-section">
                <h3><i class="fas fa-chart-pie"></i> Résumé Financier</h3>
                <div class="summary-cards">
                    <div class="summary-card">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-content">
                            <h4>Total Paiements</h4>
                            <p id="total-payments">0 USD</p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="card-icon bg-success">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-content">
                            <h4>Nombre d'Enseignants</h4>
                            <p id="total-teachers">0</p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="card-icon bg-info">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-content">
                            <h4>Nombre de Cours</h4>
                            <p id="total-courses">0</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="academic-section">
                <h3><i class="fas fa-table"></i> Détails des Paiements</h3>
                <div class="table-container academic-table">
                    <table id="payments-table">
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Montant</th>
                                <th>Devise</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Les données seront chargées dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Charger les données initiales
            loadFinancialReport();
            
            // Gérer la soumission du formulaire de filtre
            $('#report-filter-form').submit(function(e) {
                e.preventDefault();
                loadFinancialReport();
            });
            
            // Réinitialiser les filtres
            $('#reset-filters').click(function() {
                $('#date_debut, #date_fin').val('');
                loadFinancialReport();
            });
        });
        
        function loadFinancialReport() {
            const dateDebut = $('#date_debut').val();
            const dateFin = $('#date_fin').val();
            
            $.ajax({
                url: 'financial_report.php',
                method: 'GET',
                data: {
                    date_debut: dateDebut,
                    date_fin: dateFin
                },
                dataType: 'json',
                success: function(data) {
                    // Mettre à jour le résumé
                    $('#total-payments').text((data.summary.total_payments || 0) + ' USD');
                    $('#total-teachers').text(data.summary.total_teachers || 0);
                    $('#total-courses').text(data.summary.total_courses || 0);
                    
                    // Mettre à jour le tableau des détails
                    const tbody = $('#payments-table tbody');
                    tbody.empty();
                    
                    if (data.details && data.details.length > 0) {
                        data.details.forEach(function(payment) {
                            const row = `
                                <tr>
                                    <td>${payment.nom} ${payment.postnom} ${payment.prenom}</td>
                                    <td>${payment.cours}</td>
                                    <td>${payment.montant}</td>
                                    <td>${payment.devise}</td>
                                    <td>${formatDate(payment.datesve)}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-details" data-id="${payment.id}">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="6" class="text-center">Aucun paiement trouvé</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors du chargement du rapport financier:', error);
                    alert('Erreur lors du chargement du rapport financier.');
                }
            });
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        }
    </script>
</body>
</html>