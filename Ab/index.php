<?php
session_start();
include("../script/config.php");

// Vérifier si l'utilisateur est connecté et a le rôle AB
if (!isset($_SESSION['role']) || $_SESSION['role'] != "AB") {
    header("Location: ../login.php");
    exit();
}

// Récupérer les statistiques du tableau de bord
try {
    // Nombre total de fiches de prestation
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM entetefiche");
    $stmt->execute();
    $prestationsCount = $stmt->fetch()['total'];
    
    // Nombre total de fiches d'honoraires
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM honoraire");
    $stmt->execute();
    $honorairesCount = $stmt->fetch()['total'];
    
    // Montant total des honoraires
    $stmt = $pdo->prepare("SELECT SUM(montant) as total FROM honoraire");
    $stmt->execute();
    $honorairesTotal = $stmt->fetch()['total'] ?? 0;
    
    // Nombre total d'enseignants
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM enseignant");
    $stmt->execute();
    $enseignantsCount = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    echo "Erreur de base de données: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface AB - Fiches de Prestation et Honoraires</title>
    <link rel="stylesheet" href="ab_styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="ab-header">
        <h1><i class="fas fa-user-tie"></i> Interface AB - Gestion des Prestations et Honoraires</h1>
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
                <img src="../image/logo.jpg" alt="Logo">
                <h2><?php echo $_SESSION['username']; ?></h2>
                <p>Administrateur Budget</p>
            </div>
            <div class="ab-nav-menu">
                <ul>
                    <li>
                        <a href="index.php" class="active">
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
                <h2><i class="fas fa-home"></i> Tableau de bord</h2>
                <ul class="breadcrumb">
                    <li><a href="#">Accueil</a></li>
                    <li>Tableau de bord</li>
                </ul>
            </div>
            
            <!-- Statistiques du tableau de bord -->
            <div class="stats-summary">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $prestationsCount; ?></div>
                    <div class="stat-label">Fiches de Prestation</div>
                </div>

                <div class="stat-card charges">
                    <div class="stat-value"><?php echo $enseignantsCount; ?></div>
                    <div class="stat-label">Enseignants</div>
                </div>

                <div class="stat-card prestations">
                    <div class="stat-value"><?php echo $honorairesCount; ?></div>
                    <div class="stat-label">Fiches d'Honoraires</div>
                </div>

                <div class="stat-card horaires">
                    <div class="stat-value"><?php echo number_format($honorairesTotal, 2); ?> $</div>
                    <div class="stat-label">Total Honoraires</div>
                </div>
            </div>
            
            <div class="content-header">
                <h2><i class="fas fa-file-invoice"></i> Consultation des Fiches de Prestations</h2>
                <ul class="breadcrumb">
                    <li><a href="#">Accueil</a></li>
                    <li>Prestation/Honoraire</li>
                </ul>
            </div>
            
            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-list"></i> Sélectionner une fiche de prestation</h3>
                <div class="form-group">
                    <label for="fiche"><i class="fas fa-list"></i> Sélectionner une fiche de prestation :</label>
                    <select name="entetefiche" id="fiche" class="form-control">
                        <option value="">-- Sélectionnez une fiche --</option>
                        <?php
                        $sql = "SELECT entetefiche.id as entete, cours.id as idcours, cours.nomComplet as noms 
                                FROM entetefiche 
                                JOIN cours ON cours.code_cours = entetefiche.code_cours";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        while($res = $stmt->fetch()){
                            echo "<option value='".$res['entete']."'>".$res['noms']."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div id="prestation-details" style="display: none;">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Détails de la fiche de prestation</h3>
                    <div class="table-container section-chief-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Contenu</th>
                                    <th>H. Entrée</th>
                                    <th>H. Sortie</th>
                                    <th>Nbre H</th>
                                    <th>Signature CP</th>
                                    <th>Signature Enseignant</th>
                                </tr>
                            </thead>
                            <tbody id="prestation-table-body">
                                <!-- Les données seront chargées dynamiquement -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="section-chief-actions">
                        <button class="btn btn-success" id="generate-honoraire">
                            <i class="fas fa-file-invoice-dollar"></i> Générer la Fiche d'Honoraires
                        </button>
                        <button class="btn btn-primary" onclick="window.location.href='../print/ficheprestation.php'">
                            <i class="fas fa-print"></i> Imprimer la Prestation
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="content-header">
                <h2><i class="fas fa-money-check-alt"></i> Fiches d'Honoraires</h2>
                <ul class="breadcrumb">
                    <li><a href="#">Accueil</a></li>
                    <li>Honoraires</li>
                </ul>
            </div>
            
            <div class="section-chief-actions">
                <a href="financial_reports.php" class="btn btn-info">
                    <i class="fas fa-chart-line"></i> Voir les Rapports Financiers
                </a>
                <a href="manage_honoraires.php" class="btn btn-secondary">
                    <i class="fas fa-cogs"></i> Gérer les Honoraires
                </a>
            </div>
            
            <div class="section-chief-section">
                <div id="honoraire-section" style="display: none;">
                    <h3 class="section-title"><i class="fas fa-check-circle"></i> Fiche d'honoraires générée</h3>
                    <div class="section-chief-alert section-chief-alert-success">
                        <i class="fas fa-info-circle"></i>
                        <div>La fiche d'honoraires a été générée avec succès. Un message sera envoyé à l'enseignant pour le retrait de son salaire.</div>
                    </div>
                    
                    <div class="table-container section-chief-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Contenu</th>
                                    <th>Nbre H</th>
                                    <th>Taux Horaire ($)</th>
                                    <th>Total ($)</th>
                                </tr>
                            </thead>
                            <tbody id="honoraire-table-body">
                                <!-- Les données seront chargées dynamiquement -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align: right;"><strong>Total Général:</strong></td>
                                    <td id="total-general"><strong>0 $</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="section-chief-actions">
                        <button class="btn btn-success" id="send-notification">
                            <i class="fas fa-envelope"></i> Envoyer Notification à l'Enseignant
                        </button>
                        <button class="btn btn-primary" onclick="window.location.href='../print/fichehonoraire.php'">
                            <i class="fas fa-print"></i> Imprimer la Fiche d'Honoraires
                        </button>
                    </div>
                </div>
                
                <div class="section-chief-alert section-chief-alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>Aucune fiche d'honoraires générée. Sélectionnez une fiche de prestation et générez les honoraires.</div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('fiche').addEventListener('change', function() {
            const ficheId = this.value;
            if(ficheId) {
                // Afficher les détails de la prestation
                document.getElementById('prestation-details').style.display = 'block';
                loadPrestationDetails(ficheId);
            } else {
                document.getElementById('prestation-details').style.display = 'none';
            }
        });
        
        document.getElementById('generate-honoraire').addEventListener('click', function() {
            const ficheId = document.getElementById('fiche').value;
            if(ficheId) {
                // Générer la fiche d'honoraires
                document.getElementById('honoraire-section').style.display = 'block';
                loadHonoraireDetails(ficheId);
                
                // Sauvegarder les honoraires dans la base de données
                saveHonoraire(ficheId);
            } else {
                alert("Veuillez sélectionner une fiche de prestation d'abord.");
            }
        });
        
        document.getElementById('send-notification').addEventListener('click', function() {
            alert("Notification envoyée à l'enseignant : vous pouvez passer au bureau pour le retrait de votre salaire.");
        });
        
        function loadPrestationDetails(ficheId) {
            $.ajax({
                url: 'prestation_details.php?id=' + ficheId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const tbody = document.querySelector('#prestation-table-body');
                    tbody.innerHTML = '';
                    
                    data.forEach(function(row) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.dtjr}</td>
                            <td>${row.cont}</td>
                            <td>${row.h_e}</td>
                            <td>${row.h_s}</td>
                            <td>${row.nbre}</td>
                            <td>${row.sigcp}</td>
                            <td>${row.sigens}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                },
                error: function() {
                    alert("Erreur lors du chargement des détails de la prestation.");
                }
            });
        }
        
        function loadHonoraireDetails(ficheId) {
            $.ajax({
                url: 'generate_honoraire.php',
                method: 'POST',
                dataType: 'json',
                data: { fiche_id: ficheId },
                success: function(data) {
                    const tbody = document.querySelector('#honoraire-table-body');
                    tbody.innerHTML = '';
                    let totalGeneral = 0;
                    
                    data.forEach(function(row) {
                        const tr = document.createElement('tr');
                        const total = parseFloat(row.nbre) * parseFloat(row.taux_horaire);
                        totalGeneral += total;
                        
                        tr.innerHTML = `
                            <td>${row.dtjr}</td>
                            <td>${row.cont}</td>
                            <td>${row.nbre}</td>
                            <td>${row.taux_horaire}</td>
                            <td>${total.toFixed(2)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                    
                    document.getElementById('total-general').innerHTML = `<strong>${totalGeneral.toFixed(2)} $</strong>`;
                },
                error: function() {
                    alert("Erreur lors de la génération de la fiche d'honoraires.");
                }
            });
        }
        
        function saveHonoraire(ficheId) {
            $.ajax({
                url: 'save_honoraire.php',
                method: 'POST',
                dataType: 'json',
                data: { fiche_id: ficheId },
                success: function(response) {
                    if (response.status === 'success') {
                        console.log('Honoraires sauvegardés avec succès');
                    } else {
                        console.log('Erreur lors de la sauvegarde des honoraires: ' + response.message);
                    }
                },
                error: function() {
                    console.log('Erreur lors de la sauvegarde des honoraires');
                }
            });
        }
    </script>
</body>
</html>