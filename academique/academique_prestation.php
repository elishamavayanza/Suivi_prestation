<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiches de Prestation - Académique</title>
    <link rel="stylesheet" href="academic_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="academic-header">
        <h1><i class="fas fa-graduation-cap"></i> Service Académique - Validation des Prestations</h1>
        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <span><?php echo $_SESSION['username']; ?> (SGA)</span>
            </div>
            <a href="../index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="academic-container">
        <!-- Sidebar -->
        <aside class="academic-sidebar">
            <div class="academic-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Service Académique</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="academic-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php" class="active"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="academic-main">
            <div class="content-header">
                <h2><i class="fas fa-file-invoice"></i> Validation des Fiches de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Fiches de Prestation</li>
                </ul>
            </div>

            <div class="academic-alert academic-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Processus de validation:</strong> 
                    Le SGA consulte les fiches de prestations complètement remplies, les valide puis les transmet à l'AB (Agent Budgétaire).
                </div>
            </div>

            <!-- Process Steps -->
            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-project-diagram"></i> Processus de Validation</h3>
                <div class="process-steps">
                    <div class="step completed">
                        <div class="step-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="step-label">Remplissage par Enseignant</div>
                    </div>
                    <div class="step active">
                        <div class="step-icon"><i class="fas fa-user-check"></i></div>
                        <div class="step-label">Validation SGA</div>
                    </div>
                    <div class="step">
                        <div class="step-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="step-label">Transmission AB</div>
                    </div>
                    <div class="step">
                        <div class="step-icon"><i class="fas fa-euro-sign"></i></div>
                        <div class="step-label">Traitement Budgétaire</div>
                    </div>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-tasks"></i> Fiches de Prestation à Valider</h3>
                <p>Voici la liste des fiches de prestation qui ont été complétées par les enseignants et sont en attente de votre validation.</p>
                
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Fiche</th>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Total Heures</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#PR-2025-11-27-001</td>
                                <td>2025-11-27</td>
                                <td>Dupont Jean</td>
                                <td>Mathématiques Avancées</td>
                                <td>2 heures</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> À valider</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info" onclick="viewDetails(1)"><i class="fas fa-eye"></i> Détails</button>
                                    <button class="btn btn-sm btn-success" onclick="validatePrestation(1)"><i class="fas fa-check"></i> Valider</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#PR-2025-11-27-002</td>
                                <td>2025-11-27</td>
                                <td>Martin Marie</td>
                                <td>Physique Quantique</td>
                                <td>2 heures</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> À valider</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info" onclick="viewDetails(2)"><i class="fas fa-eye"></i> Détails</button>
                                    <button class="btn btn-sm btn-success" onclick="validatePrestation(2)"><i class="fas fa-check"></i> Valider</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#PR-2025-11-26-001</td>
                                <td>2025-11-26</td>
                                <td>Bernard Pierre</td>
                                <td>Chimie Organique</td>
                                <td>2 heures</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> À valider</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info" onclick="viewDetails(3)"><i class="fas fa-eye"></i> Détails</button>
                                    <button class="btn btn-sm btn-success" onclick="validatePrestation(3)"><i class="fas fa-check"></i> Valider</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-check-double"></i> Détails de la Fiche de Prestation</h3>
                <div class="validation-card">
                    <div class="validation-card-header">
                        <h4 class="validation-card-title"><i class="fas fa-file-alt"></i> Fiche de Prestation #PR-2025-11-27-001</h4>
                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente validation</span>
                    </div>
                    
                    <div class="validation-details">
                        <div class="detail-item">
                            <div class="detail-label">Date de création</div>
                            <div class="detail-value">27 Novembre 2025</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Enseignant</div>
                            <div class="detail-value">Dupont Jean (MCF)</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Cours</div>
                            <div class="detail-value">Mathématiques Avancées</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Durée totale</div>
                            <div class="detail-value">2 heures</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Salle</div>
                            <div class="detail-value">Salle A101</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Nombre d'étudiants</div>
                            <div class="detail-value">32 étudiants</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Section</div>
                            <div class="detail-value">Sciences Fondamentales</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Promotion</div>
                            <div class="detail-value">Licence 3</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Observations de l'enseignant</div>
                        <div class="detail-value">Cours terminé avec succès. Tous les étudiants ont participé activement. Chapitres 5 à 8 couverts selon le programme prévu.</div>
                    </div>
                    
                    <div class="academic-section">
                        <h4><i class="fas fa-list"></i> Détail des activités</h4>
                        <div class="academic-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Contenu</th>
                                        <th>Heure début</th>
                                        <th>Heure fin</th>
                                        <th>Nb heures</th>
                                        <th>Signature CP</th>
                                        <th>Signature Ens.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2025-11-27</td>
                                        <td>Calcul différentiel - Applications</td>
                                        <td>08:00</td>
                                        <td>10:00</td>
                                        <td>2h</td>
                                        <td><span class="status-badge status-approved"><i class="fas fa-check"></i> OK</span></td>
                                        <td><span class="status-badge status-approved"><i class="fas fa-check"></i> OK</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="validation-actions">
                        <button class="btn btn-danger btn-lg" onclick="rejectPrestation(1)"><i class="fas fa-times"></i> Rejeter avec motif</button>
                        <button class="btn btn-success btn-lg" onclick="validatePrestation(1)"><i class="fas fa-check"></i> Valider et Envoyer à l'AB</button>
                    </div>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-history"></i> Historique des validations</h3>
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Fiche</th>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Status</th>
                                <th>Date de validation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#PR-2025-11-25-001</td>
                                <td>2025-11-25</td>
                                <td>Robert Claire</td>
                                <td>Biologie Moléculaire</td>
                                <td><span class="status-badge status-valid"><i class="fas fa-check"></i> Validé</span></td>
                                <td>26 Nov 2025</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-print"></i> Imprimer</button>
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#PR-2025-11-24-002</td>
                                <td>2025-11-24</td>
                                <td>Leroy Sophie</td>
                                <td>Informatique Théorique</td>
                                <td><span class="status-badge status-sent"><i class="fas fa-paper-plane"></i> Envoyé à l'AB</span></td>
                                <td>25 Nov 2025</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-print"></i> Imprimer</button>
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#PR-2025-11-23-001</td>
                                <td>2025-11-23</td>
                                <td>Moreau Paul</td>
                                <td>Thermodynamique</td>
                                <td><span class="status-badge status-reviewed"><i class="fas fa-check-double"></i> Traitée</span></td>
                                <td>24 Nov 2025</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-print"></i> Imprimer</button>
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="academic-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>

    <script>
        function viewDetails(id) {
            alert('Afficher les détails de la fiche #' + id);
            // Cette fonction ouvrira une modale ou une page de détails
        }
        
        function validatePrestation(id) {
            if(confirm('Êtes-vous sûr de vouloir valider cette fiche de prestation et la transmettre à l\'Agent Budgétaire ?')) {
                alert('Fiche de prestation #' + id + ' validée et transmise à l\'AB avec succès !');
                // Cette fonction effectuera la validation et l'envoi réel dans l'implémentation complète
            }
        }
        
        function rejectPrestation(id) {
            var reason = prompt("Veuillez indiquer le motif du rejet:");
            if (reason != null && reason != "") {
                alert('Fiche de prestation #' + id + ' rejetée. Motif: ' + reason);
                // Cette fonction effectuera le rejet réel dans l'implémentation complète
            }
        }
    </script>
</body>
</html>