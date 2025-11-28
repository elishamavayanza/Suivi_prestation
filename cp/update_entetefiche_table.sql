-- Mettre à jour la structure de la table entetefiche pour le CP
ALTER TABLE entetefiche 
ADD COLUMN IF NOT EXISTS enseignant VARCHAR(255) DEFAULT NULL AFTER matricule_enseignant,
ADD COLUMN IF NOT EXISTS datecreation DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS statut ENUM('en_attente', 'approuvé', 'envoyé') DEFAULT 'en_attente',
ADD COLUMN IF NOT EXISTS date_approbation DATETIME DEFAULT NULL,
ADD COLUMN IF NOT EXISTS date_envoi DATETIME DEFAULT NULL;

-- Mettre à jour les enregistrements existants pour inclure l'enseignant
UPDATE entetefiche e
JOIN utilisateur u ON e.matricule_enseignant = u.matricule
SET e.enseignant = u.username
WHERE e.enseignant IS NULL;