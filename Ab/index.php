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
    <title>Interface AB - Fiches de Prestation et Honoraires</title>
    <link rel="stylesheet" href="ab_styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Interface AB - Gestion des Prestations et Honoraires</h1>
            <div>
                <?php echo $_SESSION['username']; ?> | 
                <a href="../script/logout.php" style="color: white;">Déconnexion</a>
            </div>
        </div>
        
        <div class="user-info">
            <div>Bienvenue, <?php echo $_SESSION['username']; ?> (Administrateur Budget)</div>
            <div>Rôle: <?php echo $_SESSION['role']; ?></div>
        </div>
        
        <div class="section-title">
            <h2>Consultation des Fiches de Prestations</h2>
        </div>
        
        <div class="card">
            <div class="form-group">
                <label for="fiche">Sélectionner une fiche de prestation :</label>
                <select name="entetefiche" id="fiche" class="form-control">
                    <option value="">-- Sélectionnez une fiche --</option>
                    <?php
                    $sql = "SELECT entetefiche.id as entete, cours.id as idcours, cours.nomComplet as noms 
                            FROM entetefiche, cours 
                            WHERE cours.id=entetefiche.code_cours";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array());
                    while($res = $stmt->fetch()){
                        echo "<option value='".$res['entete']."'>".$res['noms']."</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div id="prestation-details" style="display: none;">
                <h3>Détails de la fiche de prestation</h3>
                <div class="table-container">
                    <table id="prestation-table" class="table table-striped">
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
                        <tbody>
                            <!-- Les données seront chargées dynamiquement -->
                        </tbody>
                    </table>
                </div>
                
                <div class="action-buttons">
                    <button class="btn btn-success" id="generate-honoraire">
                        <i class="fas fa-file-invoice-dollar"></i> Générer la Fiche d'Honoraires
                    </button>
                    <button class="btn" onclick="window.location.href='../print/ficheprestation.php'">
                        <i class="fas fa-print"></i> Imprimer la Prestation
                    </button>
                </div>
            </div>
        </div>
        
        <div class="section-title">
            <h2>Fiches d'Honoraires</h2>
        </div>
        
        <div class="card">
            <div id="honoraire-section" style="display: none;">
                <h3>Fiche d'honoraires générée</h3>
                <div class="notification">
                    La fiche d'honoraires a été générée avec succès. 
                    Un message sera envoyé à l'enseignant pour le retrait de son salaire.
                </div>
                
                <div class="table-container">
                    <table id="honoraire-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Contenu</th>
                                <th>Nbre H</th>
                                <th>Taux Horaire ($)</th>
                                <th>Total ($)</th>
                            </tr>
                        </thead>
                        <tbody>
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
                
                <div class="action-buttons">
                    <button class="btn btn-success" id="send-notification">
                        <i class="fas fa-envelope"></i> Envoyer Notification à l'Enseignant
                    </button>
                    <button class="btn" onclick="window.location.href='../print/fichehonoraire.php'">
                        <i class="fas fa-print"></i> Imprimer la Fiche d'Honoraires
                    </button>
                </div>
            </div>
            
            <div id="no-honoraire" class="notification">
                Aucune fiche d'honoraires générée. Sélectionnez une fiche de prestation et générez les honoraires.
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                document.getElementById('no-honoraire').style.display = 'none';
                loadHonoraireDetails(ficheId);
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
                    const tbody = document.querySelector('#prestation-table tbody');
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
                    const tbody = document.querySelector('#honoraire-table tbody');
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
    </script>
</body>
</html>