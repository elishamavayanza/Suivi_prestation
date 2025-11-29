-- Ajout des colonnes pour les nouvelles fonctionnalités dans la table entetefiche
ALTER TABLE entetefiche 
ADD COLUMN IF NOT EXISTS volume_horaire_prevu INT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS heures_reelles_prestees INT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS description TEXT DEFAULT NULL;