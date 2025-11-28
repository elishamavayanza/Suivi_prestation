-- Ajout des colonnes manquantes dans la table entetefiche
ALTER TABLE entetefiche 
ADD COLUMN IF NOT EXISTS enseignant VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS datecreation DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS statut ENUM('en_attente', 'approuvé', 'envoyé') DEFAULT 'en_attente',
ADD COLUMN IF NOT EXISTS date_approbation DATETIME DEFAULT NULL,
ADD COLUMN IF NOT EXISTS date_envoi DATETIME DEFAULT NULL;