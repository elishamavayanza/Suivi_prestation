<?php
global $pdo;
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/connexion.php';

if (isset($_GET['id'])) {
    $ficheId = $_GET['id'];
    
    try {
        // Récupérer les détails de la fiche de prestation
        $stmt = $pdo->prepare("
            SELECT cf.*, 
                   ef.code_cours, ef.matricule_enseignant,
                   e.nom AS enseignant_nom, e.postnom AS enseignant_postnom, e.prenom AS enseignant_prenom,
                   c.nomComplet AS cours_nom,
                   s.nomComplet AS section_nom,
                   m.nomComplet AS mention_nom,
                   p.nomComplet AS promotion_nom
            FROM contenufiche cf
            JOIN entetefiche ef ON cf.identetefiche = ef.id
            JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
            JOIN cours c ON ef.code_cours = c.code_cours
            JOIN section s ON ef.code_section = s.code_section
            JOIN mention m ON ef.code_mention = m.code_mention
            JOIN promotion p ON ef.code_promotion = p.sigle_promotion
            WHERE ef.id = ?
            ORDER BY cf.datejoure ASC
        ");
        $stmt->execute([$ficheId]);
        $ficheDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($ficheDetails)) {
            die("Fiche de prestation non trouvée.");
        }
// Utiliser les informations de la première ligne pour l'en-tête
        $fiche = $ficheDetails[0];
        
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des données : " . $e->getMessage());
    }
} else {
die("ID de fiche non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Prestation - Impression</title>
    <link rel="stylesheet" href="../chef_section_cp_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                font-size: 12px;
}
            table {
                font-size: 11px;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        .header {
            text-align:center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            color: #333;
        }
        
        .info-grid {
           display: grid;
            grid-template-columns: 1fr 1fr;
gap: 10px;
            margin-bottom: 20px;
        }
        
        .info-item {
            padding: 5px;
        }
        
        .info-label {
            font-weight: bold;
            display:inline-block;
            width: 150px;
        }
        
        table{
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #333;
            padding:8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top:40px;
        }
        
        .signature-box {
            text-align: center;
padding: 20px;
        }
        
        .actions {
            text-align: center;
            margin: 20px 0;
        }
        
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="actions no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimer la fiche</button>
        <button class="btn btn-secondary" onclick="window.close()">
            <i class="fas fa-times"></i> Fermer
        </button>
    </div>
    
    <div class="header">
        <h1>FICHE DE PRESTATION</h1>
       <p>INSTITUT SUPERIEUR PEDAGOGIQUE DE MUHANGI/BUTEMBO</p>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Enseignant :</span>
            <?php echo htmlspecialchars($fiche['enseignant_nom'] . ' ' . $fiche['enseignant_postnom']. ' ' . $fiche['enseignant_prenom']); ?>
        </div>
        <div class="info-item">
            <span class="info-label">Cours :</span>
            <?php echo htmlspecialchars($fiche['cours_nom']); ?>
        </div>
        <div class="info-item">
<span class="info-label">Section :</span>
            <?php echo htmlspecialchars($fiche['section_nom']); ?>
        </div>
        <div class="info-item">
            <span class="info-label">Mention :</span>
            <?php echo htmlspecialchars($fiche['mention_nom']); ?>
        </div>
        <div class="info-item">
            <span class="info-label">Promotion :</span>
            <?php echo htmlspecialchars($fiche['promotion_nom']); ?>
        </div>
        <div class="info-item">
            <span class="info-label">Total Heures :</span>
            <?php$totalHours = array_sum(array_column($ficheDetails, 'nbreH'));
            echo htmlspecialchars($totalHours) . ' heures';
            ?>
        </div>
    </div>
    
    <table>
<thead>
            <tr>
                <th>Date</th>
                <th>Contenudu cours</th>
                <th>Heure Entrée</th>
                <th>Heure Sortie</th>
                <th>Nbre H</th>
                <th>Signature CP</th>
                <th>Signature Enseignant</th>
            </tr>
        </thead>
       <tbody>
            <?php foreach ($ficheDetails as $detail): ?>
            <tr>
                <td><?php echo htmlspecialchars($detail['datejoure']); ?></td>
                <td><?php echo htmlspecialchars($detail['contenu']); ?></td>
                <td><?php echo htmlspecialchars($detail['heureEntree']); ?></td>
                <td><?php echo htmlspecialchars($detail['heureSortie']); ?></td>
                <td><?php echo htmlspecialchars($detail['nbreH']); ?></td>
                <td><?phpecho htmlspecialchars($detail['signatureCP']); ?></td>
                <td><?php echo htmlspecialchars($detail['signatureEnseignant']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="signatures">
        <div class="signature-box">
           <p>Signature du Chef de Prestation</p>
            <br><br>
            <p>........................................</p>
        </div>
        <div class="signature-box">
            <p>Signature de l'Enseignant</p>
            <br><br>
            <p>........................................</p>
        </div>
    </div>
    
    <div class="actions no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimer la fiche
        </button>
        <button class="btn btn-secondary"onclick="window.close()">
            <i class="fas fa-times"></i> Fermer
       </button>
    </div>
</body>
</html>